<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityLogResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\HtmlString;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class ActivityLogResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Laporan';
    protected static ?string $navigationLabel = 'Log Aktivitas';
    protected static ?string $pluralModelLabel = 'Log Aktivitas';

    public static function canAccess(): bool
    {
        return auth()->user()?->role === 'super_admin';
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('causer.name')
                    ->label('Pelaku')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Sistem'),
                Tables\Columns\TextColumn::make('description')
                    ->label('Aksi')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'created' => 'success',
                        'updated' => 'warning',
                        'deleted' => 'danger',
                        default => 'primary',
                    }),
                Tables\Columns\TextColumn::make('subject_type')
                    ->label('Tipe Subjek')
                    ->formatStateUsing(fn ($state) => class_basename($state))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                // Read-only, no bulk delete
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Informasi Utama')
                    ->schema([
                        Infolists\Components\TextEntry::make('causer.name')->label('Pelaku')->placeholder('Sistem'),
                        Infolists\Components\TextEntry::make('description')->label('Aksi'),
                        Infolists\Components\TextEntry::make('subject_type')->label('Tipe Subjek')->formatStateUsing(fn ($state) => class_basename($state)),
                        Infolists\Components\TextEntry::make('created_at')->label('Waktu')->dateTime('d M Y, H:i'),
                    ])->columns(2),

                Infolists\Components\Section::make('Detail Perubahan')
                    ->schema([
                        Infolists\Components\KeyValueEntry::make('properties.old')
                            ->label('Data Lama')
                            ->visible(fn ($record) => isset($record->properties['old'])),
                        Infolists\Components\KeyValueEntry::make('properties.attributes')
                            ->label('Data Baru / Saat Ini')
                            ->visible(fn ($record) => isset($record->properties['attributes'])),
                    ])->columns(2),
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
            'index' => Pages\ListActivityLogs::route('/'),
            'view' => Pages\ViewActivityLog::route('/{record}'),
        ];
    }
}
