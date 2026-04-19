<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Login Pelamar')]
class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required|min:6',
    ];

    public function authenticate()
    {
        $this->validate();

        // 1. Cek kredensial
        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            $this->addError('email', 'Email atau kata sandi salah.');
            return;
        }

        $user = Auth::user();

        // 2. Cek status aktif
        if (!$user->is_active) {
            Auth::logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
            $this->addError('email', 'Akun Anda sedang dinonaktifkan. Silakan hubungi admin.');
            return;
        }

        // 3. Cek role — jangan izinkan admin login lewat sini
        if (in_array($user->role, ['super_admin', 'recruitment'])) {
            Auth::logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
            $this->addError('email', 'Akun admin tidak dapat login di halaman ini. Silakan gunakan halaman Login Admin.');
            return;
        }

        // 4. Regenerate session untuk keamanan
        request()->session()->regenerate();

        // 5. Cek verifikasi email — jika belum, arahkan ke halaman verifikasi
        if (!$user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        // 6. Semua lolos — arahkan ke halaman utama
        return redirect()->intended('/profile');
    }

    public function render()
    {
        return view('livewire.auth.login')
            ->layout('components.layouts.app');
    }
}
