<?php

namespace App\Imports;

use App\Models\DetailJemaah;
use App\Models\Jemaah;
use Carbon\Carbon;
use Exception;
use Filament\Actions\Concerns\InteractsWithRecord;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\Village;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class JamaahImport implements ToModel, WithHeadingRow
{


    use Importable;

    public $record;

    public function __construct($record)
    {
        $this->record = $record;
    }

    private $currentJemaah = null;

    public function transformDate($value, $format = 'Y-m-d')
    {
        try {
            return \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
        } catch (\ErrorException $e) {
            return \Carbon\Carbon::createFromFormat($format, $value);
        }
    }

    public function model(array $row)
    {

        try {


            $searchTerm = strtolower(str_replace(['.', ' '], '%', $row["kabkota"]));

            $province = Province::where("name", $row["provinsi"])->first()->code ?? "";
            $city = City::select('code', 'name')
                ->whereRaw("LOWER(REPLACE(name, '.', '')) LIKE ?", ["%{$searchTerm}%"])
                ->orWhereRaw("LOWER(REPLACE(name, ' ', '')) LIKE ?", ["%{$searchTerm}%"])
                ->first()->code ?? "";
            $district =  District::where("name", $row["kecamatan"])->first()->code ?? "";
            $village = Village::where("name", $row["desa"])->first()->code ?? "";



            $formatted = [
                "nama_lengkap" => $row["nama_lengkap"],
                "nama_panggilan" => $row["nama_panggilan"],
                "nik" => $row["nik"],
                "email" => $row["email"],
                "tempat_lahir" => $row["tempat_lahir"],
                "tanggal_lahir" =>  Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row["tanggal_lahir"]))->format('d/m/Y'),
                "profile_picture" => "",
                "telp" => preg_replace("/0/", "62", $row["no_hp"]),
                "jenis_kelamin" => $row["jenis_kelamin_lp"] === "P" ? "perempuan" : "laki-laki",
                "alamat_lengkap" => json_encode([
                    "provinsi" => $province,
                    "kota" => $city,
                    "kecamatan" => $district,
                    "desa" => $village
                ]),
                "kepengurusan" => $row["kepengurusan_di_nu"],
                "jabatan_kepengurusan" => $row["jabatan_kepengurusan"],
                "pendidikan_terakhir" => $row["pendidikan_terakhir"],
                "mwcnu_id" => $this->record
            ];

            // dd($formatted);

            $this->currentJemaah = Jemaah::firstOrCreate(
                ["nik" => $formatted["nik"]],
                $formatted
            );

            $formatedDetailJemaah = [
                "penghasilan" =>  "0",
                "pekerjaan" => "",
                "alamat_detail" => $row["alamat_lengkap"],
                "pendidikan_terakhir" => $row["pendidikan_terakhir"],
                "status_pernikahan" => $row["status_pernikahan"] ?? "",
                "jemaah_id" => $this->currentJemaah->id,
            ];

            $this->currentJemaah->detail()->updateOrCreate([
                "jemaah_id" => $this->currentJemaah->id
            ], $formatedDetailJemaah);

            Notification::make()
                ->title("Data Jamaah berhasil diimport")
                ->success()
                ->send();
            return null;
        } catch (Exception $e) {
            Log::error("Error: " . $e->getMessage());
            Notification::make()
                ->title("Data Jamaah gagal diimport!")
                ->danger()
                ->send();
        }
    }
}
