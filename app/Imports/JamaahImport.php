<?php

namespace App\Imports;

use App\Models\DetailJemaah;
use App\Models\Jemaah;
use Carbon\Carbon;
use Exception;
use Filament\Actions\Concerns\InteractsWithRecord;
use Filament\Notifications\Actions\Action;
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

        $receipent = auth()->user();

        try {
            if ($row["nik"] === null) {
                return null;
            }

            $searchTerm = strtolower(str_replace(['.', ' '], '%', $row["kabkota"]));

            $province = Province::where("name", $row["provinsi"])->first();
            $provinceCode = $province ? $province->code : '';

            $city = City::select('code', 'name')
                ->where("province_code", $provinceCode)
                ->where(function ($query) use ($searchTerm) {
                    $query->whereRaw("LOWER(REPLACE(name, '.', '')) LIKE ?", ["%{$searchTerm}%"])
                        ->orWhereRaw("LOWER(REPLACE(name, ' ', '')) LIKE ?", ["%{$searchTerm}%"]);
                })
                ->first();
            $cityCode = $city ? $city->code : '';

            $district = District::where("city_code", $cityCode)
                ->where("name", $row["kecamatan"])
                ->first();
            $districtCode = $district ? $district->code : '';

            $village = Village::where("district_code", $districtCode)
                ->where("name", $row["desa"])
                ->first();
            $villageCode = $village ? $village->code : '';



            $formatted = [
                "nama_lengkap" => $row["nama_lengkap"],
                "nama_panggilan" => $row["nama_panggilan"],
                "nik" => $row["nik"],
                "email" => $row["email"],
                "tempat_lahir" => $row["tempat_lahir"],
                "tanggal_lahir" =>  Carbon::instance(Date::excelToDateTimeObject($row["tanggal_lahir"]))->format('d/m/Y'),
                "telp" => formatPhoneNumber($row["no_hp"]),
                "profile_picture" => "",
                "jenis_kelamin" => $row["jenis_kelamin_lp"] === "P" ? "perempuan" : "laki-laki",
                "kepengurusan" => $row["kepengurusan_di_nu"],
                "jabatan_kepengurusan" => $row["jabatan_kepengurusan"],
                "pendidikan_terakhir" => $row["pendidikan_terakhir"],
                "penghasilan" =>  "0",
                "pekerjaan" => "",
                "mwcnu_id" => $this->record
            ];


            $this->currentJemaah = Jemaah::firstOrCreate(
                ["nik" => $formatted["nik"]],
                $formatted
            );

            $this->currentJemaah->alamat_jemaah()->updateOrCreate([
                "provinsi" => $provinceCode,
                "kota" => $cityCode,
                "kecamatan" => $districtCode,
                "desa" => $villageCode,
                "jemaah_id" => $this->currentJemaah->id
            ]);


            $formatedDetailJemaah = [
                "alamat_detail" => $row["alamat_lengkap"],
                "jemaah_id" => $this->currentJemaah->id,
            ];

            $this->currentJemaah->detail_jemaah()->updateOrCreate([
                "jemaah_id" => $this->currentJemaah->id
            ], $formatedDetailJemaah);

            return null;
        } catch (Exception $e) {
            Log::error("Error: " . $e->getMessage());
            Notification::make()
                ->title("Data Jamaah gagal diimport!")
                ->danger()
                ->body("Warga dengan NIK {$row["nik"]} gagal diimport. Silahkan hubungi admin.")
                ->send()
                ->sendToDatabase($receipent);
        }
    }
}
