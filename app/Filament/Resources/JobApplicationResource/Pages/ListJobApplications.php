<?php

namespace App\Filament\Resources\JobApplicationResource\Pages;

use App\Filament\Resources\JobApplicationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListJobApplications extends ListRecords
{
    protected static string $resource = JobApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No create action for applications
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua Lamaran'),
            'terkirim' => Tab::make('Terkirim')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Terkirim')),
            'review' => Tab::make('Review')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Review')),
            'interview' => Tab::make('Interview')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Interview')),
            'diterima' => Tab::make('Diterima')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Diterima')),
            'ditolak' => Tab::make('Ditolak')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'Ditolak')),
        ];
    }

    public function getDefaultActiveTab(): string | int | null
    {
        return 'terkirim';
    }
}
