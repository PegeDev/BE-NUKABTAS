<?php

namespace App\Filament\Resources\MwcnuResource\Pages;

use App\Filament\Resources\MwcnuResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMwcnu extends EditRecord
{
    protected static string $resource = MwcnuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
