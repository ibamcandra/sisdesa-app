<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
use App\Models\CompanyProfile as CompanyProfileModel;

class CompanyProfile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationGroup = 'General Config';
    protected static ?string $title = 'Profile Perusahaan';

    protected static string $view = 'filament.pages.company-profile';

    public static function canAccess(): bool
    {
        return auth()->user()->role === 'super_admin';
    }

    public ?array $data = [];

    public function mount(): void
    {
        $profile = CompanyProfileModel::first();
        if ($profile) {
            $this->form->fill($profile->toArray());
        } else {
            $this->form->fill();
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Perusahaan')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('name')
                                ->label('Nama Perusahaan')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('phone')
                                ->label('Nomor Telepon')
                                ->tel()
                                ->maxLength(255),
                            TextInput::make('email')
                                ->label('Email')
                                ->email()
                                ->maxLength(255),
                            Textarea::make('address')
                                ->label('Alamat')
                                ->columnSpanFull(),
                        ]),
                    ]),
                Section::make('Logo & Favicon')
                    ->schema([
                        Grid::make(2)->schema([
                            FileUpload::make('logo')
                                ->label('Upload Logo')
                                ->image()
                                ->acceptedFileTypes(['image/jpeg', 'image/png'])
                                ->directory('company-logos')
                                ->maxSize(2048),
                            FileUpload::make('favicon')
                                ->label('Upload Favicon')
                                ->image()
                                ->acceptedFileTypes(['image/jpeg', 'image/png'])
                                ->directory('company-favicons')
                                ->maxSize(1024),
                        ]),
                    ]),
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

        $profile = CompanyProfileModel::first();

        if ($profile) {
            $profile->update($data);
        } else {
            CompanyProfileModel::create($data);
        }

        Notification::make()
            ->title('Berhasil disimpan')
            ->success()
            ->send();
    }
}
