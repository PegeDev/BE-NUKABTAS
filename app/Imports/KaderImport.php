<?php

namespace App\Imports;

use App\Models\Jemaah;
use App\Models\Mwcnu;
use Carbon\Carbon;
use Exception;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\Village;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;

class KaderImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, SkipsOnError, SkipsEmptyRows
{
    use Importable, SkipsFailures, SkipsErrors;


    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            $this->failures[] = [
                'row' => $failure->row(),
                'attribute' => $failure->attribute(),
                'error' => $failure->errors(),
            ];
        }
    }

    private $currentJemaah = null;

    public $validHeadings = [
        'nik',
        'email',
        'nama_lengkap',
        'nama_panggilan',
        'ttl',
        'usia',
        'no_hp',
        'jenis_kelamin',
        'provinsi',
        'kabupatenkota',
        'kecamatan',
        'kelurahandesa',
        'alamat',
        'pekerjaan',
        'keanggotaan_di_nu',
        'jabatan',
        'pendidikan_terakhir',
        'pernah_mondok',
        'ponpes',
        'kaderisasi'
    ];


    public function rules(): array
    {
        return [
            'nik' => 'required|string|max:16',
            'email' => 'nullable|email|max:255',
            'nama_lengkap' => 'required|string|max:255',
            'nama_panggilan' => 'nullable|string|max:255',
            'ttl' => 'required|string|max:255',
            'no_hp' => 'required|string|max:255',
            'jenis_kelamin' => 'required|string|max:255',
            'provinsi' => 'required|string|max:255',
            'kabupatenkota' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'kelurahandesa' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'pekerjaan' => 'nullable|string|max:255',
            'keanggotaan_di_nu' => 'nullable|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'pendidikan_terakhir' => 'required|string|max:255',
            'pernah_mondok' => 'required|string|max:255',
            'ponpes' => 'required|string|max:255',
            'kaderisasi' => 'required|string|max:255',
        ];
    }



    public function customValidationMessages()
    {
        return [
            'nik.required' => 'NIK harus diisi dan maksimal 16 karakter.',
            'nama_lengkap.required' => 'Nama lengkap harus diisi.',
            'ttl.required' => 'Tempat dan tanggal lahir harus diisi.',
            'no_hp.required' => 'Nomor telepon harus diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin harus diisi.',
            'provinsi.required' => 'Provinsi harus diisi.',
            'kabupatenkota.required' => 'Kabupaten/Kota harus diisi.',
            'kecamatan.required' => 'Kecamatan harus diisi.',
            'kelurahandesa.required' => 'Kelurahan/Desa harus diisi.',
            'alamat.required' => 'Alamat harus diisi.',
            'pekerjaan.required' => 'Pekerjaan harus diisi.',
            'keanggotaan_di_nu.required' => 'Keanggotaan di NU harus diisi.',
            'pendidikan_terakhir.required' => 'Pendidikan terakhir harus diisi.',
            'pernah_mondok.required' => 'Pernah mondok harus diisi.',
            'ponpes.required' => 'Nama Pondok Pesantren harus diisi.',
            'kaderisasi.required' => 'Kaderisasi harus diisi.',
        ];
    }

    public function headingRow(): int
    {
        return 1;
    }


    public function model(array $row)
    {

        if (!array_filter($row)) {
            return null;
        }

        $ttl = explode(", ", $row["ttl"]);
        $tempatLahir = '';
        $tglLahir = '';
        if (count($ttl) > 2) {
            $tempatLahir = $ttl[0] . $ttl[1];
        } else {
            $tempatLahir = $ttl[0];
        }


        $mwcnu = Mwcnu::where("nama_kecamatan", $row["kecamatan"])->first();

        $provinceCode = $this->getProvinceCode($row["provinsi"]);
        $cityCode = $this->getCityCode($row["kabupatenkota"], $provinceCode);
        $districtCode = $this->getDistrictCode($row["kecamatan"], $cityCode);
        $villageCode = $this->getVillageCode($row["kelurahandesa"], $districtCode);

        $pendidikan_terakhir = $row["pendidikan_terakhir"];

        $formatted = [
            "nama_lengkap" => $row["nama_lengkap"],
            "nama_panggilan" => $row["nama_panggilan"],
            "nik" => $row["nik"],
            "email" => $row["email"],
            "tempat_lahir" => $tempatLahir,
            "tanggal_lahir" => Carbon::parse(convertIndonesianDate($row["ttl"])),
            "telp" => formatPhoneNumber($row["no_hp"]),
            "jenis_kelamin" => Str::lower($row["jenis_kelamin"]),
            "kepengurusan" => $row["keanggotaan_di_nu"],
            "jabatan_kepengurusan" => $row["jabatan"],
            "pendidikan_terakhir" => $pendidikan_terakhir,
            "pekerjaan" => $row["pekerjaan"],
            "mwcnu_id" => $mwcnu->id,
        ];

        $this->currentJemaah = Jemaah::firstOrCreate(
            ["nik" => $formatted["nik"]],
            $formatted
        );

        $this->currentJemaah->kader()->updateOrCreate(
            ["jemaah_id" => $this->currentJemaah->id],
            [
                "kaderisasi" => $row["kaderisasi"],
                "jemaah_id" => $this->currentJemaah->id,
            ]
        );


        $this->currentJemaah->alamat_jemaah()->updateOrCreate(
            [
                "provinsi" => $provinceCode,
                "kota" => $cityCode,
                "kecamatan" => $districtCode,
                "desa" => $villageCode,
                "jemaah_id" => $this->currentJemaah->id,
            ]
        );

        $formattedDetailJemaah = [
            "alamat_detail" => $row["alamat"],
            "riwayat_pesantren" => array([
                "nama_pesantren" => $row["ponpes"],
            ]),
            "jemaah_id" => $this->currentJemaah->id,
        ];

        $this->currentJemaah->detail_jemaah()->updateOrCreate(
            ["jemaah_id" => $this->currentJemaah->id],
            $formattedDetailJemaah
        );

        $this->currentJemaah->save();

        return $this->currentJemaah;
    }






    private function getProvinceCode($provinceName)
    {
        $province = Province::where("name", $provinceName)->first();
        return $province ? $province->code : '';
    }

    private function getCityCode($cityName, $provinceCode)
    {
        $searchTerm = strtolower(str_replace(['.', ' '], '%', $cityName));
        $city = City::select('code', 'name')
            ->where("province_code", $provinceCode)
            ->where(function ($query) use ($searchTerm) {
                $query->whereRaw("LOWER(REPLACE(name, '.', '')) LIKE ?", ["%{$searchTerm}%"])
                    ->orWhereRaw("LOWER(REPLACE(name, ' ', '')) LIKE ?", ["%{$searchTerm}%"]);
            })
            ->first();
        return $city ? $city->code : '';
    }

    private function getDistrictCode($districtName, $cityCode)
    {
        $district = District::where("city_code", $cityCode)
            ->where("name", $districtName)
            ->first();
        return $district ? $district->code : '';
    }

    private function getVillageCode($villageName, $districtCode)
    {
        $village = Village::where("district_code", $districtCode)
            ->where("name", $villageName)
            ->first();
        return $village ? $village->code : '';
    }

    private function transformDate($value, $format = 'Y-m-d')
    {
        try {
            return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
        } catch (\ErrorException $e) {
            return Carbon::createFromFormat($format, $value);
        }
    }
}
