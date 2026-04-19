<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EducationLevelResource\Pages;
use App\Filament\Resources\EducationLevelResource\RelationManagers;
use App\Models\EducationLevel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EducationLevelResource extends Resource
{
    protected static ?string $model = EducationLevel::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Master Data';
    protected static ?string $label = 'Tingkat Pendidikan';
    protected static ?string $pluralLabel = 'Tingkat Pendidikan';

    public static function canAccess(): bool
    {
        return in_array(auth()->user()->role, ['super_admin', 'recruitment']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Tingkat Pendidikan')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Tingkat Pendidikan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ListEducationLevels::route('/'),
            'create' => Pages\CreateEducationLevel::route('/create'),
            'edit' => Pages\EditEducationLevel::route('/{record}/edit'),
        ];
    }
}
