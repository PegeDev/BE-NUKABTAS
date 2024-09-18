<?php

namespace App\Filament\Resources\LembagaMasterResource\Pages;

use App\Filament\Resources\LembagaMasterResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLembagaMasters extends ListRecords
{
    protected static string $resource = LembagaMasterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
