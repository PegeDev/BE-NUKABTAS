<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Laravolt\Indonesia\Seeds\CitiesSeeder;
use Laravolt\Indonesia\Seeds\DistrictsSeeder;
use Laravolt\Indonesia\Seeds\ProvincesSeeder;
use Laravolt\Indonesia\Seeds\VillagesSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        DB::table('permissions')->insert([
            [
                'name' => 'Create MWCNU',
                'guard_name' => 'web',
            ],
            [
                'name' => 'Delete MWCNU',
                'guard_name' => 'web',
            ],
            [
                'name' => 'Update MWCNU',
                'guard_name' => 'web',
            ],
            [
                'name' => 'View MWCNU',
                'guard_name' => 'web',
            ],
            [
                'name' => 'Create Jemaah',
                'guard_name' => 'web',
            ],
            [
                'name' => 'Delete Jemaah',
                'guard_name' => 'web',
            ],
            [
                'name' => 'Update Jemaah',
                'guard_name' => 'web',
            ],
            [
                'name' => 'View Jemaah',
                'guard_name' => 'web',
            ],
        ]);

        // DB::table("roles")->insert([
        //     [
        //         "name" => "dev",
        //         "guard_name" => "web"
        //     ],
        //     [
        //         "name" => "kecamatan",
        //         "guard_name" => "web"
        //     ],
        //     [
        //         "name" => "kabupaten",
        //         "guard_name" => "web"
        //     ]
        // ]);


        // DB::table("jemaahs")
        //     ->where("id", 1)
        //     ->update([
        //         "alamat_lengkap" => [
        //             "provinsi" => "Jawa Barat",
        //             "kota" => "Kab. Tasikmalaya",
        //             "kecamatan" => "Bantarkalong",
        //             "desa" => "Hegarwangi",
        //         ]
        //     ]);

        // $this->call([
        //     ProvincesSeeder::class,
        //     CitiesSeeder::class,
        //     DistrictsSeeder::class,
        //     VillagesSeeder::class,
        // ]);
    }
}
