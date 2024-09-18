<?php

namespace App\Imports;

use App\Models\BanomMaster;
use App\Models\LembagaMaster;
use App\Models\Mwcnu;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LembagaImport implements ToModel, WithHeadingRow
{
    public $currentLembaga = null;

    public function model(array $row)
    {
        try {

            $this->currentLembaga = LembagaMaster::firstOrCreate(
                ['name' => $row['nama_lembaga']],
                ['name' => $row['nama_lembaga']]
            );


            if (!$this->currentLembaga) {
                return null;
            }

            Notification::make()
                ->title("Data Lembaga berhasil diimport")
                ->success()
                ->send();

            return null;
        } catch (Exception $e) {
            Log::error($e->getMessage());
            Notification::make()
                ->title("Data Lembaga gagal diimport!")
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
