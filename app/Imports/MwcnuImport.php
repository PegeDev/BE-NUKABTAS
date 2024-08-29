<?php

namespace App\Imports;

use App\Models\Mwcnu;
use Exception;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MwcnuImport implements ToModel, WithHeadingRow
{
    private $currentKecamatan = null;

    public function model(array $row)
    {
        try {

            $this->currentKecamatan = Mwcnu::firstOrCreate(
                ['nama_kecamatan' => $row['nama_kecamatan']],
                ['nama_kecamatan' => $row['nama_kecamatan']]
            );

            Log::info("hasil firstOrCrete: {$this->currentKecamatan}");

            // Debugging info

            if (!$this->currentKecamatan) {
                return null;
            }
            Notification::make()
                ->title("Data Kecamatan berhasil diimport")
                ->success()
                ->send();
            return null;
        } catch (Exception $e) {
            Log::error($e->getMessage());
            Notification::make()
                ->title("Data Kecamatan gagal diimport!")
                ->danger()
                ->send();
        }
    }
}
