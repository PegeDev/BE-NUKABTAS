<?php

namespace App\Filament\Resources\MwcnuResource\Pages;

use App\Filament\Resources\MwcnuResource;
use App\Models\Mwcnu;
use Filament\Resources\Pages\Page;

class SuratKeputusanMwcnu extends Page
{

    protected static string $resource = MwcnuResource::class;

    protected static string $view = 'filament.resources.mwcnu-resource.pages.surat-keputusan-mwcnu';

    public Mwcnu $record;
}
