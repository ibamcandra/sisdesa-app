<?php

namespace App\Filament\Pages;

use App\Models\WhatsappConfig as WhatsappModel;
use Filament\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappConfig extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationGroup = 'General Config';
    protected static ?string $title = 'Konfigurasi Whatsapp';

    protected static string $view = 'filament.pages.whatsapp-config';

    public ?array $data = [];

    public static function canAccess(): bool
    {
        return auth()->user()->role === 'super_admin';
    }

    public function mount(): void
    {
        $config = WhatsappModel::first();
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
                Section::make('Fonnte API Configuration')
                    ->description('Silakan masukkan kredensial API dari fonnte.com')
                    ->schema([
                        TextInput::make('phone_number')
                            ->label('Nomor WA Pengirim / Device')
                            ->placeholder('628123456789')
                            ->helperText('Gunakan format internasional tanpa tanda + (contoh: 628xxx)')
                            ->required(),
                        TextInput::make('token')
                            ->label('API Token')
                            ->password()
                            ->revealable()
                            ->required(),
                    ])->columns(2),
            ])
            ->statePath('data');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('testWhatsapp')
                ->label('Test Kirim WA')
                ->icon('heroicon-o-paper-airplane')
                ->color('info')
                ->form([
                    TextInput::make('target_number')
                        ->label('Nomor WA Tujuan')
                        ->placeholder('628xxx')
                        ->required(),
                ])
                ->action(function (array $data) {
                    $this->sendTestMessage($data['target_number']);
                }),
        ];
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan Perubahan')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $config = WhatsappModel::first();

        if ($config) {
            $config->update($data);
        } else {
            WhatsappModel::create($data);
        }

        Notification::make()
            ->title('Konfigurasi berhasil disimpan')
            ->success()
            ->send();
    }

    public function sendTestMessage(string $target): void
    {
        $formData = $this->form->getState();
        $token = $formData['token'] ?? null;

        if (!$token) {
            Notification::make()->title('Token belum diisi')->danger()->send();
            return;
        }

        try {
            // Menggunakan asMultipart() sesuai standar dokumentasi Fonnte (CURL POSTFIELDS array)
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->asMultipart()->post('https://api.fonnte.com/send', [
                'target' => $target,
                'message' => "Halo! Ini adalah pesan percobaan dari sistem E-Rekrutmen.\n\nJika Anda menerima pesan ini, artinya konfigurasi Fonnte Anda sudah benar.",
                'countryCode' => '62', // Standar Indonesia
            ]);

            $result = $response->json();

            if ($response->successful() && ($result['status'] ?? false)) {
                Notification::make()
                    ->title('Pesan WA berhasil dikirim ke ' . $target)
                    ->success()
                    ->send();
            } else {
                // Log error untuk debug jika gagal
                Log::error('Fonnte API Error: ', $result ?? []);
                
                $reason = $result['reason'] ?? ($result['detail'] ?? 'Gagal mengirim pesan (Cek token/nomor target)');
                throw new \Exception($reason);
            }
        } catch (\Exception $e) {
            Notification::make()
                ->title('Gagal mengirim Whatsapp')
                ->body($e->getMessage())
                ->danger()
                ->persistent()
                ->send();
        }
    }
}
