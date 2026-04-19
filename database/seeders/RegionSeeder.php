<?php

namespace Database\Seeders;

use App\Models\Province;
use App\Models\City;
use App\Models\District;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Provinsi Jawa Barat
        $jabar = Province::create(['name' => 'JAWA BARAT']);

        // 2. Kota/Kabupaten di Jawa Barat
        $cities = [
            'KOTA BANDUNG' => ['SUMUR BANDUNG', 'COBLONG', 'LENGKONG', 'CIBEUNYING KALER'],
            'KOTA BOGOR' => ['BOGOR TIMUR', 'BOGOR SELATAN', 'BOGOR TENGAH'],
            'KOTA BEKASI' => ['BEKASI TIMUR', 'BEKASI SELATAN', 'RAWALUMBU'],
            'KABUPATEN BANDUNG' => ['SOREANG', 'BANJARAN', 'PANGALENGAN'],
            'KABUPATEN GARUT' => ['GARUT KOTA', 'TAROGONG KIDUL', 'CIBATU'],
        ];

        foreach ($cities as $cityName => $districts) {
            $city = City::create([
                'province_id' => $jabar->id,
                'name' => $cityName
            ]);

            foreach ($districts as $districtName) {
                District::create([
                    'city_id' => $city->id,
                    'name' => $districtName
                ]);
            }
        }
    }
}
