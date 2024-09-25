<?php

namespace App\Filament\Resources\MwcnuResource\Pages;

use App\Filament\Resources\MwcnuResource;
use App\Models\Mwcnu;
use Filament\Resources\Concerns\HasTabs;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Actions\EditAction;
use Filament\Pages\Page as PagesPage;
use Illuminate\Contracts\Support\Htmlable;

class DetailMwcnu extends Page
{
    use InteractsWithRecord;
    use HasTabs;

    protected static string $resource = MwcnuResource::class;

    protected static string $view = 'filament.resources.mwcnu-resource.pages.detail-mwcnu';

    public $state;

    public function getTitle(): string|Htmlable
    {
        return  "Detail Kecamatan " . $this->record->nama_kecamatan;
    }

    public function mount($record): void
    {
        $this->record = $this->resolveRecord($record);
        $this->state = request()->query('state', 'detail');
        if (!in_array($this->state, ['detail', 'surat-keputusan', 'warga', 'pengurus'])) {
            redirect()->to(MwcnuResource::getUrl('detail', ['record' => $this->record->id]));
        }
    }

    public function getHeaderActions(): array
    {
        return [
            EditAction::make("edit")
                ->icon("heroicon-o-pencil-square")
                ->label("Edit Detail")
                ->visible(auth()->user()->id === $this->record->admin_id && $this->state === 'detail' || auth()->user()->hasRole(['super_admin', 'admin_kabupaten']))
                ->url(MwcnuResource::getUrl('edit', ['record' => $this->record->id])),
        ];
    }

    public array $tabs = [
        [
            "label" => "Detail",
            "icon" => "heroicon-o-clipboard-document-list",
            "view" => "filament.dashboard.resources.data-kecamatan.detail",
        ],
        [
            "label" => "Surat Keputusan",
            "icon" => "heroicon-o-document-check",
            "view" => "filament.dashboard.resources.data-kecamatan.surat-keputusan",
        ],
        [
            "label" => "Warga",
            "icon" => "heroicon-o-user-group",
            "view" => "filament.dashboard.resources.data-kecamatan.warga",
        ],
        [
            "label" => "Kepengurusan",
            "icon" => "heroicon-o-user",
            "view" => "filament.dashboard.resources.data-kecamatan.pengurus",
        ]
    ];
}
