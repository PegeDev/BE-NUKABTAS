<?php

namespace App\Imports;

use App\Models\BanomMaster;
use App\Models\Mwcnu;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BanomImport implements ToModel, WithHeadingRow
{
    public $currentBanom = null;

    public function model(array $row)
    {
        try {

            $this->currentBanom = BanomMaster::firstOrCreate(
                ['name' => $row['nama_banom']],
                ['name' => $row['nama_banom']]
            );


            if (!$this->currentBanom) {
                return null;
            }

            Notification::make()
                ->title("Data Banom berhasil diimport")
                ->success()
                ->send();

            return null;
        } catch (Exception $e) {
            Log::error($e->getMessage());
            Notification::make()
                ->title("Data Banom gagal diimport!")
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
