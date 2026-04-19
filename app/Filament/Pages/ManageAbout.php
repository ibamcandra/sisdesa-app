<?php

namespace App\Filament\Pages;

use App\Models\AboutPage;
use Filament\Forms;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class ManageAbout extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-information-circle';
    protected static ?string $navigationGroup = 'Konten';
    protected static ?string $navigationLabel = 'Tentang Kami';
    protected static ?string $title = 'Kelola Tentang Kami';
    protected static ?int $navigationSort = 3;

    protected static string $view = 'filament.pages.manage-about';

    public ?array $data = [];

    public function mount(): void
    {
        $record = AboutPage::first();
        
        if ($record) {
            $this->form->fill($record->toArray());
        }
    }

    public static function canAccess(): bool
    {
        return Auth::user()->role === 'super_admin';
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->role === 'super_admin';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Judul Utama')
                            ->placeholder('Contoh: TENTANG KAMI')
                            ->required(),
                        Forms\Components\TextInput::make('subtitle')
                            ->label('Sub Judul')
                            ->placeholder('Contoh: Karang Taruna Desa Campaka - Purwakarta'),
                        Forms\Components\Textarea::make('content')
                            ->label('Isi Konten')
                            ->rows(15)
                            ->placeholder('Tuliskan profil atau sejarah singkat organisasi di sini...')
                            ->columnSpanFull(),
                    ])
            ])
            ->statePath('data');
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
        
        $record = AboutPage::first();
        
        if ($record) {
            $record->update($data);
        } else {
            AboutPage::create($data);
        }

        Notification::make()
            ->title('Data Tentang Kami berhasil disimpan')
            ->success()
            ->send();
    }
}
