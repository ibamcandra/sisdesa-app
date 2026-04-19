<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Password;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Lupa Password')]
class ForgotPassword extends Component
{
    public $email;

    protected $rules = [
        'email' => 'required|email|exists:users,email',
    ];

    protected $messages = [
        'email.exists' => 'Alamat email tidak terdaftar di sistem kami.',
    ];

    public function sendResetLink()
    {
        $this->validate();

        try {
            $status = Password::sendResetLink(
                ['email' => $this->email]
            );

            if ($status === Password::RESET_LINK_SENT) {
                session()->flash('status', 'Kami telah mengirimkan tautan reset password ke email Anda. Jika tidak ditemukan, mohon cek juga di folder Spam.');
                $this->email = '';
            } else {
                $this->addError('email', __($status));
            }
        } catch (\Exception $e) {
            $this->addError('email', 'Error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}
