<?php

namespace App\Filament\Resources\KaderResource\Pages;

use App\Filament\Resources\KaderResource;
use App\Imports\KaderImport;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Actions\Action as ActionsAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\ActionSize;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use Maatwebsite\Excel\Validators\ValidationException;

class ListKaders extends ListRecords
{
    protected static string $resource = KaderResource::class;

    protected static ?string $title = 'Data Kader';


    protected function getHeaderActions(): array
    {
        return [
            Action::make("import_kader")
                ->visible(fn() => auth()->user()->hasRole(['super_admin', "admin_kabupaten"]))
                ->label("Import Kader")
                ->icon("fluentui-document-add-20-o")
                ->modalDescription("Import file excel dengan format yang valid.")
                ->modalSubmitActionLabel('Import Kader')
                ->modalWidth('md')
                ->form([
                    FileUpload::make("attachment")
                        ->hiddenLabel()
                        ->acceptedFileTypes(["application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", "application/vnd.ms-excel"])
                ])
                ->action(
                    function (array $data) {
                        $file = public_path('storage/' . $data["attachment"]);

                        $headings = (new HeadingRowImport)->toArray($file);
                        $fileHeadings = array_values($headings[0][0]);

                        $import = new KaderImport();
                        if ($import->validHeadings !== $fileHeadings) {
                            return Notification::make()
                                ->title("File tidak valid")
                                ->body('upload ulang file dengan format yang valid, Sesuai dengan template.')
                                ->danger()
                                ->send();
                        }

                        Excel::import($import, $file);

                        if (!empty($import->failures())) {

                            $formattedErrors = '';

                            $errors = $import->failures()->toArray();
                            $groupedErrors = [];

                            foreach ($errors as $failure) {
                                $row = $failure['row'];

                                // Jika baris belum ada di array $groupedErrors, tambahkan
                                if (!isset($groupedErrors[$row])) {
                                    $groupedErrors[$row] = [];
                                }

                                // Tambahkan error untuk atribut ke dalam array berdasarkan baris
                                $groupedErrors[$row][] = "{$failure['attribute']}: " . implode(', ', $failure['error']);
                            }
                            $formattedErrors = '';
                            foreach ($groupedErrors  as $row => $errorMessages) {
                                $formattedErrors .= "========== Baris {$row} ==========\n";
                                $formattedErrors .= implode("\n", $errorMessages) . "\n\n"; // Pisahkan setiap error dengan baris baru
                            }

                            $receipent = User::role(['super_admin', 'admin_kabupaten'])->get();

                            Notification::make()
                                ->title("Data Kader gagal diimport!")
                                ->body('gagal import kader')
                                ->danger()
                                ->actions([
                                    ActionsAction::make('viewError')
                                        ->label('Lihat Error')
                                        ->icon('fluentui-text-bullet-list-square-warning-16-o')
                                        ->size(ActionSize::Small)
                                        ->close()
                                        ->dispatch('error-modal', ['errorsData' => $formattedErrors]),
                                ])
                                ->send()
                                ->sendToDatabase($receipent);
                        }
                    }
                )
        ];
    }
}
