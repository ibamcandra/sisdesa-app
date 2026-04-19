<?php

namespace App\Filament\Widgets;

use App\Models\ApplicantProfile;
use App\Models\JobApplication;
use App\Models\Vacancy;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $user = Auth::user();
        $isRecruitment = $user->role === 'recruitment';

        // Base queries
        $vacancyQuery = Vacancy::query();
        $applicationQuery = JobApplication::query();

        if ($isRecruitment) {
            $vacancyQuery->where('user_id', $user->id);
            $applicationQuery->whereHas('vacancy', fn ($q) => $q->where('user_id', $user->id));
        }

        return [
            Stat::make('Total Pelamar', ApplicantProfile::count())
                ->description('Seluruh kandidat terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),
            
            Stat::make('Lowongan Aktif', (clone $vacancyQuery)->where('close_date', '>=', now())->count())
                ->description('Pekerjaan yang sedang dibuka')
                ->descriptionIcon('heroicon-m-briefcase')
                ->color('success'),

            Stat::make('Pending Review', (clone $applicationQuery)->where('status', 'Terkirim')->count())
                ->description('Lamaran baru masuk')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Kandidat Diterima', (clone $applicationQuery)->where('status', 'Diterima')->count())
                ->description('Total rekrutmen berhasil')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),
        ];
    }
}
