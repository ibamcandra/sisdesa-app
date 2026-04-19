<?php

namespace App\Filament\Widgets;

use App\Models\JobApplication;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ApplicationTrendChart extends ChartWidget
{
    protected static ?string $heading = 'Tren Lamaran Masuk (30 Hari)';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $user = Auth::user();
        $query = JobApplication::query();

        if ($user->role === 'recruitment') {
            $query->whereHas('vacancy', fn ($q) => $q->where('user_id', $user->id));
        }

        // Generate dates for the last 30 days
        $results = $query->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as aggregate')
            )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('aggregate', 'date')
            ->toArray();

        $labels = [];
        $values = [];

        for ($i = 30; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->format('d M');
            $values[] = $results[$date] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Lamaran Baru',
                    'data' => $values,
                    'fill' => 'start',
                    'borderColor' => '#ef4444',
                    'backgroundColor' => 'rgba(239, 68, 68, 0.1)',
                    'tension' => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
