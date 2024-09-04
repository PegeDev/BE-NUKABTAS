<?php

namespace App\Filament\Resources\MwcnuResource\Pages;

use App\Filament\Resources\MwcnuResource;
use App\Models\Mwcnu;
use Filament\Resources\Concerns\HasTabs;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Actions\Action;
use Filament\Actions\EditAction;

class ViewMwcnu extends Page
{
    use InteractsWithRecord;
    use HasTabs;

    protected static string $resource = MwcnuResource::class;

    protected static ?string $title = 'Detail Kecamatan';

    protected static string $view = 'filament.resources.mwcnu-resource.pages.view-mwcnu';

    public $state;



    public function mount($record): void
    {
        $this->record = $this->resolveRecord($record);
        $this->state = request()->query('state', 'detail');
        if ($this->state !== 'warga' && $this->state !== 'detail') {
            redirect()->to(route('filament.dashboard.resources.data-kecamatan.detail', ['record' => $this->record->id]));
        }
    }

    public function getHeaderActions(): array
    {
        return [
            EditAction::make("edit")
                ->icon("heroicon-o-pencil-square")
                ->label("Edit Detail")
                ->url(route("filament.dashboard.resources.data-kecamatan.edit", $this->record->id)),
        ];
    }

    public array $tabs = [
        [
            "label" => "Detail",
            "icon" => "heroicon-o-clipboard-document-list",
            "view" => "filament.dashboard.resources.data-kecamatan.detail",
        ],
        [
            "label" => "Warga",
            "icon" => "heroicon-o-user-group",
            "view" => "filament.dashboard.resources.data-kecamatan.jamaah",
        ]
    ];
}
