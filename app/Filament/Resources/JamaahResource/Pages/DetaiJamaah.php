<?php

namespace App\Filament\Resources\JamaahResource\Pages;

use App\Filament\Resources\JamaahResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class DetailJamaah extends ViewRecord
{
    protected static string $resource = JamaahResource::class;

    protected static ?string $title = "Detail Warga";


    public function getHeaderActions(): array
    {
        return [
            EditAction::make("edit")
                ->label("Edit Detail")
                ->icon("heroicon-o-pencil-square")
        ];
    }
}
