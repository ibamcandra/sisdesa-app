<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VacancyResource\Pages;
use App\Filament\Resources\VacancyResource\RelationManagers;
use App\Models\Vacancy;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class VacancyResource extends Resource
{
    protected static ?string $model = Vacancy::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationGroup = 'Lowongan Pekerjaan';
    protected static ?string $label = 'Data Lowongan';
    protected static ?string $pluralLabel = 'Data Lowongan';
    protected static ?int $navigationSort = 1;

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        
        // Scoping: Recruitment hanya bisa melihat lowongan yang mereka buat
        if (Auth::user()->role === 'recruitment') {
            return $query->where('user_id', Auth::id());
        }

        return $query;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Dasar')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Judul Lowongan')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('branch_id')
                            ->label('Branch')
                            ->relationship('branch', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\DatePicker::make('close_date')
                            ->label('Tanggal Tutup')
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y'),
                        Forms\Components\Select::make('job_category_id')
                            ->label('Kategori')
                            ->relationship('jobCategory', 'name')
                            ->required()
                            ->searchable()
                            ->preload(),
                        Forms\Components\Select::make('type')
                            ->label('Tipe Pekerjaan')
                            ->options([
                                'Full-time' => 'Full-time',
                                'Part-time' => 'Part-time',
                                'Kontrak' => 'Kontrak',
                                'Magang' => 'Magang',
                            ])
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Detail Pekerjaan')
                    ->schema([
                        Forms\Components\RichEditor::make('description')
                            ->label('Deskripsi Pekerjaan')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\RichEditor::make('requirement')
                            ->label('Requirement')
                            ->required()
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Keahlian')
                    ->schema([
                        Forms\Components\Select::make('skills')
                            ->label('Keahlian yang Dibutuhkan')
                            ->relationship('skills', 'name')
                            ->multiple()
                            ->required()
                            ->searchable()
                            ->preload(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Lowongan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('branch.name')
                    ->label('Branch')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jobCategory.name')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Full-time' => 'success',
                        'Part-time' => 'warning',
                        'Kontrak' => 'info',
                        'Magang' => 'gray',
                        default => 'primary',
                    }),
                Tables\Columns\TextColumn::make('applications_count')
                    ->label('Pelamar')
                    ->counts('applications')
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('close_date')
                    ->label('Close Date')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Filter Tipe')
                    ->options([
                        'Full-time' => 'Full-time',
                        'Part-time' => 'Part-time',
                        'Kontrak' => 'Kontrak',
                        'Magang' => 'Magang',
                    ]),
                Tables\Filters\SelectFilter::make('branch_id')
                    ->label('Filter Branch')
                    ->relationship('branch', 'name'),
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
            RelationManagers\ApplicationsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVacancies::route('/'),
            'create' => Pages\CreateVacancy::route('/create'),
            'edit' => Pages\EditVacancy::route('/{record}/edit'),
        ];
    }
}
