<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;

class ChangePassword extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-key';
    protected static ?string $navigationLabel = 'Ganti Password';
    protected static ?string $title = 'Pengaturan Keamanan';
    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.pages.change-password';

    public ?array $data = [];

    public static function canAccess(): bool
    {
        return in_array(Auth::user()->role, ['super_admin', 'recruitment']);
    }

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Ubah Password Anda')
                    ->description('Pastikan menggunakan password yang kuat untuk menjaga keamanan akun.')
                    ->schema([
                        TextInput::make('current_password')
                            ->label('Password Saat Ini')
                            ->password()
                            ->required()
                            ->currentPassword(),
                        
                        TextInput::make('new_password')
                            ->label('Password Baru')
                            ->password()
                            ->required()
                            ->rule(Password::default())
                            ->different('current_password')
                            ->same('new_password_confirmation'),
                        
                        TextInput::make('new_password_confirmation')
                            ->label('Konfirmasi Password Baru')
                            ->password()
                            ->required()
                            ->dehydrated(false),
                    ])
                    ->columns(2),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan Perubahan')
                ->submit('submit'),
        ];
    }

    public function submit(): void
    {
        $data = $this->form->getState();
        $user = auth()->user();

        $user->update([
            'password' => Hash::make($data['new_password']),
        ]);

        $this->form->fill();

        Notification::make()
            ->title('Berhasil!')
            ->body('Password Anda telah berhasil diperbarui.')
            ->success()
            ->send();
    }
}
