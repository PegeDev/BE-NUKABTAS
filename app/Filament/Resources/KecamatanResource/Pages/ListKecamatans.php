<?php

namespace App\Filament\Resources\KecamatanResource\Pages;

use App\Filament\Resources\KecamatanResource;
use App\Imports\KecamatanImport;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Notifications\Notification;

class ListKecamatan extends ListRecords
{
    protected static string $resource = KecamatanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make("import_kecamatan")
                ->label("Import Kecamatan")
                ->icon("heroicon-o-document-arrow-down")
                ->form([
                    FileUpload::make("attachment")
                        ->acceptedFileTypes(["application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", "application/vnd.ms-excel"])
                ])
                ->action(
                    function (array $data) {
                        $file = public_path('storage/' . $data["attachment"]);

                        Excel::import(new KecamatanImport, $file);

                        Notification::make()
                            ->title("Data Kecamatan berhasil diimport")
                            ->success()
                            ->send();
                    }
                )
        ];
    }
}
