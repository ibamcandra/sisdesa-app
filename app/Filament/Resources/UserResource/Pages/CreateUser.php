<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use App\Notifications\UserAccountCreated;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $plainPassword = $data['password'];
        
        // Hash the password manually since we removed it from the Resource
        $data['password'] = Hash::make($plainPassword);
        
        $record = parent::handleRecordCreation($data);
        
        // Send notification with the plain password
        $record->notify(new UserAccountCreated(
            $record->name,
            $record->email,
            $plainPassword,
            $record->role
        ));
        
        return $record;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
