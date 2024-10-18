<?php

namespace App\Filament\Resources\MwcnuResource\Pages;

use App\Filament\Resources\MwcnuResource;
use App\Models\Mwcnu;
use Filament\Resources\Concerns\HasTabs;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Actions\EditAction;
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
        if (!in_array($this->state, ['detail', 'surat-keputusan', 'warga', 'kepengurusan'])) {
            redirect()->to(MwcnuResource::getUrl('detail', ['record' => $this->record->id]));
        } elseif ($this->state === 'surat-keputusan' && auth()->user()->id !== $this->record->admin_id && !auth()->user()->hasRole(['super_admin', 'admin_kabupaten'])) {
            redirect()->to(MwcnuResource::getUrl('detail', ['record' => $this->record->id]));
        }
    }

    public function getHeaderActions(): array
    {
        return [
            EditAction::make("edit")
                ->icon("heroicon-o-pencil-square")
                ->label("Edit Detail")
                ->visible(fn() => auth()->user()->id === $this->record->admin_id  || auth()->user()->hasRole(['super_admin', 'admin_kabupaten']) &&  $this->state === 'detail')
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
            "label" => "Warga",
            "icon" => "heroicon-o-user-group",
            "view" => "filament.dashboard.resources.data-kecamatan.warga",

        ],
        [
            "label" => "Surat Keputusan",
            "icon" => "heroicon-o-document-check",
            "view" => "filament.dashboard.resources.data-kecamatan.surat-keputusan",

        ],
        [
            "label" => "Kepengurusan",
            "icon" => "heroicon-o-user",
            "view" => "filament.dashboard.resources.data-kecamatan.pengurus",
        ],
    ];
}
