<?php

namespace App\Filament\Resources\MwcnuResource\Pages;

use App\Filament\Resources\MwcnuResource;
use Filament\Resources\Pages\ListRecords;

class ListMwcnus extends ListRecords
{
    protected static string $resource = MwcnuResource::class;

    protected static ?string $title = "Data Kecamatan";
}
