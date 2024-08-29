<?php

namespace App\Imports;

use App\Models\Desa;
use App\Models\Kecamatan;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class KecamatanImport implements ToModel, WithHeadingRow
{

    use Importable;

    private $currentKecamatan = null;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {   // Jika kode atau nama_kecamatan kosong, gunakan nilai dari baris sebelumnya
        if (!empty($row['nama_kecamatan'])) {

            $this->currentKecamatan = Kecamatan::firstOrCreate(
                ['nama' => $row['nama_kecamatan']],
                ['nama' => $row['nama_kecamatan'], "kode" => $row["kode"]]
            );

            Log::info("hasil firstOrCrete: {$this->currentKecamatan}");

            // Debugging info
            Log::info("Kecamatan diproses: Kode - {$row['kode']}, Nama - {$this->currentKecamatan->nama}");
        }

        // Jika tidak ada kecamatan yang di-set, maka lewati baris ini
        if (!$this->currentKecamatan) {
            Log::warning('Baris dilewati karena kecamatan tidak ada: ', $row);
            return null;
        }

        // Cek apakah desa sudah ada di dalam kecamatan yang sama
        $desaExists = Desa::where('nama', $row['nama_desa'])
            ->where('kecamatan_id', $this->currentKecamatan->id)
            ->exists();

        if (!$desaExists) {
            // Buat desa baru yang berhubungan dengan kecamatan saat ini
            Log::info('Membuat desa baru: ' . $row['nama_desa']);
            return new Desa([
                'nama' => $row['nama_desa'],
                'kecamatan_id' => $this->currentKecamatan->id,
            ]);
        } else {
            Log::info('Desa sudah ada dan dilewati: ' . $row['nama_desa']);
        }

        return null;
    }
}
