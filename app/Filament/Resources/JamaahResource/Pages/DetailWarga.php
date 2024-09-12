<?php

namespace App\Filament\Resources\JamaahResource\Pages;

use App\Filament\Resources\JamaahResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class DetailWarga extends ViewRecord
{
    protected static string $resource = JamaahResource::class;

    protected static ?string $title = "Detail Warga";


    public function getHeaderActions(): array
    {
        return [
            EditAction::make("edit")
                ->label("Edit Detail")
                ->visible(auth()->user()->id === $this->record->mwcnu->admin_id && auth()->user()->can("update_jamaah") || auth()->user()->hasRole('super_admin'))
                ->icon("heroicon-o-pencil-square")
        ];
    }
}
