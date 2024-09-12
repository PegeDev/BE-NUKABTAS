<?php

namespace App\Exports;

use App\Models\Jemaah;
use App\Models\Mwcnu;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\Village;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class JamaahExport implements FromCollection, WithHeadings
{
    use Exportable;

    protected Collection $record;


    public function __construct(Collection $record)
    {
        $this->record = $record;
    }

    public function collection()
    {
        return collect($this->record->map(function ($jemaah) {


            $province = $jemaah->alamat_jemaah ? Province::where("code", $jemaah->alamat_jemaah->provinsi)->first()->name : "";
            $city = $jemaah->alamat_jemaah ? City::where("code",  $jemaah->alamat_jemaah->kota)->first()->name : "";
            $district = $jemaah->alamat_jemaah ? District::where("code",  $jemaah->alamat_jemaah->kecamatan)->first()->name : "";
            $village = $jemaah->alamat_jemaah ? Village::where("code",  $jemaah->alamat_jemaah->desa)->first()->name : "";

            return [
                "Nama Lengkap" => (string) $jemaah->nama_lengkap,
                "Nama Panggilan" => (string) $jemaah->nama_panggilan,
                "NIK" => (string) $jemaah->nik,
                "Email" => (string) $jemaah->email,
                "Tempat Lahir" => (string) $jemaah->tempat_lahir,
                "Tanggal Lahir" => (string) Carbon::parse($jemaah->tanggal_lahir)->format('d/m/Y'),
                "No. HP" => (string) $jemaah->telp,
                "Jenis Kelamin" => (string) $jemaah->jenis_kelamin,
                "Kecamatan" => (string) $district,
                "Desa" => (string) $village,
                "Kab/Kota" => (string) $city,
                "Provinsi" => (string) $province,
                "Alamat Lengkap" => (string) $jemaah->detail?->alamat_detail,
                "Kepengurusan di NU" => (string) $jemaah->kepengurusan,
                "Jabatan Kepengurusan" => (string) $jemaah->jabatan_kepengurusan,
            ];
        }));
    }

    public function headings(): array
    {
        return [
            "Nama Panggilan",
            "NIK",
            "Email",
            "Tempat Lahir",
            "Tanggal Lahir",
            "No. HP",
            "Jenis Kelamin",
            "Kecamatan",
            "Desa",
            "Kab/Kota",
            "Provinsi",
            "Alamat Lengkap",
            "Kepengurusan di NU",
            "Jabatan Kepengurusan",
        ];
    }
}
