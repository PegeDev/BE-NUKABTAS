<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravolt;
use Laravolt\Indonesia\IndonesiaService;
use Laravolt\Indonesia\Models\Province;
use Laravolt\Indonesia\Models\City;
use Laravolt\Indonesia\Models\District;
use Laravolt\Indonesia\Models\Village;

class IndonesiaController extends Controller
{

    public static function allProvinces()
    {
        return  Province::all();
    }

    public static function allCities()
    {
        return  City::whe();
    }

    public static function getCitiesByProvince(string $provinceName)
    {
        $province = Province::where("name", $provinceName);
        return  City::where("province_code", $province->code);
    }


    public static function allDistricts()
    {
        return  District::all();
    }

    public static function allVillages()
    {
        return  Village::all();
    }
}
