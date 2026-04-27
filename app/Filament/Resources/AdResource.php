<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdResource\Pages;
use App\Filament\Resources\AdResource\RelationManagers;
use App\Models\Ad;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AdResource extends Resource
{
    protected static ?string $model = Ad::class;

    protected static ?string $navigationGroup = 'Pengaturan';
    protected static ?string $label = 'Manajemen Iklan';
    protected static ?string $pluralLabel = 'Iklan';
    protected static ?string $navigationIcon = 'heroicon-o-megaphone';

    public static function canAccess(): bool
    {
        return auth()->user()->role === 'super_admin';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Iklan')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\Select::make('location')
                            ->label('Lokasi Penempatan')
                            ->options([
                                'home_middle' => 'Beranda (Tengah)',
                                'sidebar' => 'Sidebar (Detail Kabar)',
                            ])
                            ->required(),

                        Forms\Components\Select::make('type')
                            ->label('Tipe Iklan')
                            ->options([
                                'image' => 'Banner Gambar',
                                'script' => 'Google Ads / Script',
                            ])
                            ->required()
                            ->live(),

                        Forms\Components\FileUpload::make('image_path')
                            ->label('Upload Banner')
                            ->image()
                            ->directory('ads')
                            ->visible(fn (Forms\Get $get) => $get('type') === 'image')
                            ->required(fn (Forms\Get $get) => $get('type') === 'image'),

                        Forms\Components\TextInput::make('url')
                            ->label('Link Tujuan (URL)')
                            ->url()
                            ->visible(fn (Forms\Get $get) => $get('type') === 'image'),

                        Forms\Components\Textarea::make('script_code')
                            ->label('Kode Script (Google Ads)')
                            ->rows(6)
                            ->visible(fn (Forms\Get $get) => $get('type') === 'script')
                            ->required(fn (Forms\Get $get) => $get('type') === 'script'),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->default(true),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Iklan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('location')
                    ->label('Lokasi')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'home_middle' => 'Beranda',
                        'sidebar' => 'Sidebar',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'image' => 'success',
                        'script' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAds::route('/'),
            'create' => Pages\CreateAd::route('/create'),
            'edit' => Pages\EditAd::route('/{record}/edit'),
        ];
    }
}
