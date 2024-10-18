<?php

namespace App\Filament\Resources\MwcnuResource\Pages;

use App\Filament\Resources\MwcnuResource;
use App\Imports\MwcnuImport;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Contracts\HasForms;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;

class ListMwcnus extends ListRecords
{
    protected static string $resource = MwcnuResource::class;

    protected static ?string $title = "Data Kecamatan";

    public function getHeaderActions(): array
    {
        return [
            CreateAction::make("buat_kecamatan")
                ->icon("heroicon-o-plus")
                ->url(fn() => MwcnuResource::getUrl("create"))
                ->label("Buat Kecamatan"),
            Action::make("import_kecamatan")
                ->label("Import Kecamatan")
                ->icon("heroicon-o-document-arrow-down")
                ->form([
                    FileUpload::make("attachment")
                        ->acceptedFileTypes(["application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", "application/vnd.ms-excel"])
                ])
                ->visible(auth()->user()->can("import_mwcnu"))
                ->action(
                    function (array $data) {
                        $file = public_path('storage/' . $data["attachment"]);
                        Excel::import(new MwcnuImport, $file);
                    }
                )
        ];
    }
}
