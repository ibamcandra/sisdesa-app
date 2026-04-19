<?php

namespace App\Filament\Widgets;

use App\Models\JobApplication;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class ApplicationStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Status Lamaran';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $user = Auth::user();
        $query = JobApplication::query();

        if ($user->role === 'recruitment') {
            $query->whereHas('vacancy', fn ($q) => $q->where('user_id', $user->id));
        }

        $data = $query->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $labels = array_keys($data);
        $values = array_values($data);

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Lamaran',
                    'data' => $values,
                    'backgroundColor' => [
                        '#94a3b8', // Terkirim (gray)
                        '#0ea5e9', // Review (sky)
                        '#f59e0b', // Interview (amber)
                        '#10b981', // Diterima (emerald)
                        '#ef4444', // Ditolak (red)
                    ],
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
