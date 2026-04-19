<?php

namespace App\Filament\Resources\ApplicantProfileResource\Pages;

use App\Filament\Resources\ApplicantProfileResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListApplicantProfiles extends ListRecords
{
    protected static string $resource = ApplicantProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
