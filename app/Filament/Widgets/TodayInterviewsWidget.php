<?php

namespace App\Filament\Widgets;

use App\Models\JobApplication;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\Auth;

class TodayInterviewsWidget extends BaseWidget
{
    protected static ?string $heading = 'Jadwal Interview Hari Ini';
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $user = Auth::user();
        
        $query = JobApplication::query()
            ->where('status', 'Interview')
            ->whereDate('interview_date', now());

        if ($user->role === 'recruitment') {
            $query->whereHas('vacancy', fn ($q) => $q->where('user_id', $user->id));
        }

        return $table
            ->query($query)
            ->columns([
                Tables\Columns\TextColumn::make('interview_time')
                    ->label('Jam')
                    ->icon('heroicon-m-clock')
                    ->badge()
                    ->color('warning')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('applicantProfile.name')
                    ->label('Nama Pelamar')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('vacancy.title')
                    ->label('Posisi / Lowongan'),

                Tables\Columns\TextColumn::make('interview_location')
                    ->label('Tempat / Link')
                    ->limit(30),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('Lihat Detail')
                    ->icon('heroicon-m-eye')
                    ->url(fn (JobApplication $record): string => route('filament.admin.resources.job-applications.index', ['tableFilters[status][value]' => 'Interview'])),
            ]);
    }
}
