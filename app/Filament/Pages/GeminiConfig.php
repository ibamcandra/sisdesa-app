<?php

namespace App\Filament\Pages;

use App\Models\GeminiConfig as GeminiModel;
use Filament\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Log;

class GeminiConfig extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cpu-chip';
    protected static ?string $navigationGroup = 'General Config';
    protected static ?string $title = 'Konfigurasi AI';

    protected static string $view = 'filament.pages.gemini-config';

    public ?array $data = [];

    public static function canAccess(): bool
    {
        return auth()->user()->role === 'super_admin';
    }

    public function mount(): void
    {
        $config = GeminiModel::first();
        if ($config) {
            $this->form->fill($config->toArray());
        } else {
            $this->form->fill([
                'model' => 'gemini-2.0-flash-lite',
            ]);
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('AI Provider Configuration')
                    ->description('Atur Provider, API Key dan Model yang digunakan untuk analisis CV.')
                    ->schema([
                        Select::make('provider')
                            ->label('AI Provider')
                            ->options([
                                'gemini' => 'Google Gemini',
                                'groq' => 'Groq (Llama/Mixtral)',
                            ])
                            ->required()
                            ->live(),
                        TextInput::make('api_key')
                            ->label(fn (callable $get) => $get('provider') === 'groq' ? 'Groq API Key' : 'Gemini API Key')
                            ->password()
                            ->revealable()
                            ->helperText(fn (callable $get) => $get('provider') === 'groq' ? 'Dapatkan di console.groq.com' : 'Dapatkan di aistudio.google.com')
                            ->required(),
                        Select::make('model')
                            ->label('AI Model')
                            ->options(fn (callable $get) => match ($get('provider')) {
                                'groq' => [
                                    'llama-3.3-70b-versatile' => 'Llama 3.3 70B (Smartest)',
                                    'meta-llama/llama-4-scout-17b-16e-instruct' => 'Llama 4 Scout (Latest)',
                                    'llama-3.1-8b-instant' => 'Llama 3.1 8B (Fast)',
                                    'mixtral-8x7b-32768' => 'Mixtral 8x7B',
                                ],
                                default => [
                                    'gemini-2.0-flash' => 'Gemini 2.0 Flash (Fastest)',
                                    'gemini-2.0-flash-lite' => 'Gemini 2.0 Flash Lite (Lightweight)',
                                    'gemini-1.5-flash' => 'Gemini 1.5 Flash (Legacy)',
                                    'gemma-3-1b-it' => 'Gemma 3 1B IT (Smallest)',
                                ],
                            })
                            ->required(),
                    ])->columns(3),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan Konfigurasi')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $config = GeminiModel::first();

        if ($config) {
            $config->update($data);
        } else {
            GeminiModel::create($data);
        }

        Notification::make()
            ->title('Konfigurasi Gemini berhasil disimpan')
            ->success()
            ->send();
    }
}
