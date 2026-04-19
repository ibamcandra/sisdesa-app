<?php

namespace App\Filament\Pages;

use App\Models\OrganizationStructure;
use Filament\Forms;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class ManageOrganization extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Konten';
    protected static ?string $navigationLabel = 'Struktur Organisasi';
    protected static ?string $title = 'Kelola Struktur Organisasi';
    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.pages.manage-organization';

    public ?array $data = [];

    public function mount(): void
    {
        $record = OrganizationStructure::first();
        
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
                            ->placeholder('Contoh: STRUKTUR ORGANISASI KARANG TARUNA')
                            ->required(),
                        Forms\Components\TextInput::make('subtitle')
                            ->label('Sub Judul / Periode')
                            ->placeholder('Contoh: Masa Bhakti 2024 - 2027'),
                        Forms\Components\MarkdownEditor::make('content')
                            ->label('Detail Pengurus / Isi')
                            ->placeholder('Tuliskan detail struktur organisasi di sini...')
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
        
        $record = OrganizationStructure::first();
        
        if ($record) {
            $record->update($data);
        } else {
            OrganizationStructure::create($data);
        }

        Notification::make()
            ->title('Data berhasil disimpan')
            ->success()
            ->send();
    }
}
