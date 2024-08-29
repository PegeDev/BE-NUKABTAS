<?php

namespace App\Filament\Resources\MwcnuResource\Pages;

use App\Filament\Resources\MwcnuResource;
use Filament\Resources\Concerns\HasTabs;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;

class ViewMwcnu extends Page
{
    use InteractsWithRecord;
    use HasTabs;

    protected static string $resource = MwcnuResource::class;

    protected static ?string $title = 'Detail Kecamatan';

    protected static string $view = 'filament.resources.mwcnu-resource.pages.view-mwcnu';

    public $state;


    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
        $this->state = request()->query('state', 'detail');
    }

    public array $tabs = [
        [
            "label" => "Detail",
            "icon" => "heroicon-o-clipboard-document-list",
            "view" => "filament.dashboard.resources.data-kecamatan.detail",
        ],
        [
            "label" => "Jamaah",
            "icon" => "heroicon-o-user-group",
            "view" => "filament.dashboard.resources.data-kecamatan.jamaah",
        ]
    ];
}
