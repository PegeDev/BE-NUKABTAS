<?php

namespace App\Filament\Resources\JamaahResource\Pages;

use App\Filament\Resources\JamaahResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditJamaah extends EditRecord
{
    protected static string $resource = JamaahResource::class;


    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['provinsi'] = "32";
        return $data;
    }



    protected function getSavedNotificationTitle(): ?string
    {
        return 'Warga berhasil diubah!';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('detail', ["record" => $this->record->id]);
    }
}
