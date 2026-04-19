<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApplicantProfileResource\Pages;
use App\Models\ApplicantProfile;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ApplicantProfileResource extends Resource
{
    protected static ?string $model = ApplicantProfile::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Pelamar';
    protected static ?string $navigationLabel = 'Data Pelamar';
    protected static ?string $pluralLabel = 'Data Pelamar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('phone')
                            ->tel()
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('birth_date'),
                        Forms\Components\Select::make('gender')
                            ->options([
                                'Laki-laki' => 'Laki-laki',
                                'Perempuan' => 'Perempuan',
                            ]),
                        Forms\Components\Select::make('education_level_id')
                            ->relationship('educationLevel', 'name'),
                        Forms\Components\TextInput::make('major'),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Pelamar')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('gender')
                    ->label('L/P')
                    ->sortable(),

                TextColumn::make('usia')
                    ->label('Usia')
                    ->getStateUsing(function (ApplicantProfile $record) {
                        if (!$record->birth_date) return '-';
                        return Carbon::parse($record->birth_date)->age . ' Tahun';
                    }),

                TextColumn::make('city.name')
                    ->label('Domisili')
                    ->placeholder('-')
                    ->searchable(),

                TextColumn::make('pendidikan')
                    ->label('Pendidikan Terakhir')
                    ->getStateUsing(function (ApplicantProfile $record) {
                        $edu = $record->educationLevel?->name ?? '-';
                        $major = $record->major ? " - {$record->major}" : "";
                        return $edu . $major;
                    }),

                TextColumn::make('status_kerja')
                    ->label('Sedang Bekerja?')
                    ->badge()
                    ->getStateUsing(function (ApplicantProfile $record) {
                        $isWorking = $record->experiences()->where('is_current', true)->exists();
                        return $isWorking ? 'Ya' : 'Tidak';
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'Ya' => 'success',
                        'Tidak' => 'gray',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('gender')
                    ->label('Jenis Kelamin')
                    ->options([
                        'Laki-laki' => 'Laki-laki',
                        'Perempuan' => 'Perempuan',
                    ]),
                Tables\Filters\SelectFilter::make('education_level_id')
                    ->label('Pendidikan')
                    ->relationship('educationLevel', 'name'),
                Tables\Filters\TernaryFilter::make('working_status')
                    ->label('Sedang Bekerja')
                    ->placeholder('Semua Status')
                    ->queries(
                        true: fn (Builder $query) => $query->whereHas('experiences', fn ($q) => $q->where('is_current', true)),
                        false: fn (Builder $query) => $query->whereDoesntHave('experiences', fn ($q) => $q->where('is_current', true)),
                    )
            ])
            ->actions([
                Action::make('download_cv')
                    ->label('Download CV')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->url(fn (ApplicantProfile $record): ?string => $record->cv_file ? Storage::url($record->cv_file) : null)
                    ->openUrlInNewTab()
                    ->visible(fn (ApplicantProfile $record): bool => !empty($record->cv_file)),
                
                Action::make('view_cv')
                    ->label('Detail')
                    ->icon('heroicon-o-document-text')
                    ->color('info')
                    ->url(fn (ApplicantProfile $record): string => route('admin.applicant.cv', $record))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                // Read-only: No bulk actions
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
            'index' => Pages\ListApplicantProfiles::route('/'),
        ];
    }
}
