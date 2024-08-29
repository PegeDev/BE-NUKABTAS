<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }


    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Custom logic, misalnya hashing password atau menambahkan data default
        dd($data);

        return $data;
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Role berhasil dibuat!';
    }
}
