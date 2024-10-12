<?php

namespace App\Filament\Resources\MwcnuResource\Pages;

use App\Filament\Resources\MwcnuResource;
use App\Models\Mwcnu;
use Filament\Resources\Pages\Page;


class CreateJamaahMwcnu extends Page
{
    protected static string $resource = MwcnuResource::class;

    protected static string $view = 'filament.resources.mwcnu-resource.pages.buat-warga';

    public Mwcnu $record;

    public function getTitle(): string
    {
        return __('Buat Warga ' . $this->record->nama_kecamatan);
    }
}
