<?php

namespace App\Filament\Pages;

use App\Models\EmailConfig as EmailConfigModel;
use Filament\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;

class EmailConfig extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static ?string $navigationGroup = 'General Config';
    protected static ?string $title = 'Konfigurasi Email';

    protected static string $view = 'filament.pages.email-config';

    public ?array $data = [];

    public static function canAccess(): bool
    {
        return auth()->user()->role === 'super_admin';
    }

    public function mount(): void
    {
        $config = EmailConfigModel::first();
        if ($config) {
            $this->form->fill($config->toArray());
        } else {
            $this->form->fill();
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('SMTP Configuration')
                    ->schema([
                        TextInput::make('mail_mailer')
                            ->label('Mailer')
                            ->default('smtp')
                            ->required(),
                        TextInput::make('mail_host')
                            ->label('Host')
                            ->placeholder('smtp.mailtrap.io')
                            ->required(),
                        TextInput::make('mail_port')
                            ->label('Port')
                            ->numeric()
                            ->default(587)
                            ->required(),
                        TextInput::make('mail_username')
                            ->label('Username')
                            ->required(),
                        TextInput::make('mail_password')
                            ->label('Password')
                            ->password()
                            ->revealable()
                            ->required(),
                        TextInput::make('mail_encryption')
                            ->label('Encryption')
                            ->placeholder('tls'),
                    ])->columns(2),
                Section::make('Sender Information')
                    ->schema([
                        TextInput::make('mail_from_address')
                            ->label('From Address')
                            ->email()
                            ->required(),
                        TextInput::make('mail_from_name')
                            ->label('From Name')
                            ->required(),
                    ])->columns(2),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan Perubahan')
                ->submit('save'),
            Action::make('testEmail')
                ->label('Test Kirim Email')
                ->color('info')
                ->requiresConfirmation()
                ->action('testEmail'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $config = EmailConfigModel::first();

        if ($config) {
            $config->update($data);
        } else {
            EmailConfigModel::create($data);
        }

        Notification::make()
            ->title('Konfigurasi berhasil disimpan')
            ->success()
            ->send();
    }

    public function testEmail(): void
    {
        $data = $this->form->getState();

        try {
            // Set config dynamically
            Config::set('mail.mailers.smtp.host', $data['mail_host']);
            Config::set('mail.mailers.smtp.port', $data['mail_port']);
            Config::set('mail.mailers.smtp.username', $data['mail_username']);
            Config::set('mail.mailers.smtp.password', $data['mail_password']);
            Config::set('mail.mailers.smtp.encryption', $data['mail_encryption']);
            Config::set('mail.from.address', $data['mail_from_address']);
            Config::set('mail.from.name', $data['mail_from_name']);

            Mail::raw('Ini adalah email percobaan dari sistem E-Rekrutmen.', function ($message) use ($data) {
                $message->to(auth()->user()->email)
                    ->subject('Test Email Konfigurasi');
            });

            Notification::make()
                ->title('Email berhasil dikirim ke ' . auth()->user()->email)
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Gagal mengirim email')
                ->body($e->getMessage())
                ->danger()
                ->persistent()
                ->send();
        }
    }
}
