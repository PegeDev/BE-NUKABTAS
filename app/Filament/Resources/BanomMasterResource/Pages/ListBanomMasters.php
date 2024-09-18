<?php

namespace App\Filament\Resources\BanomMasterResource\Pages;

use App\Filament\Resources\BanomMasterResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBanomMasters extends ListRecords
{
    protected static string $resource = BanomMasterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
