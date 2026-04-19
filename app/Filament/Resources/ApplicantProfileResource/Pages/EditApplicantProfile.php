<?php

namespace App\Filament\Resources\ApplicantProfileResource\Pages;

use App\Filament\Resources\ApplicantProfileResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditApplicantProfile extends EditRecord
{
    protected static string $resource = ApplicantProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
