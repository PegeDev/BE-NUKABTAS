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

        // DB::table('permissions')->insert([
        //     [
        //         'name' => 'Create Master Data',
        //         'guard_name' => 'web',
        //     ],
        //     [
        //         'name' => 'Delete Master Data',
        //         'guard_name' => 'web',
        //     ],
        //     [
        //         'name' => 'Update Master Data',
        //         'guard_name' => 'web',
        //     ],
        //     [
        //         'name' => 'View Master Data',
        //         'guard_name' => 'web',
        //     ],
        //     [
        //         'name' => 'Create Master Desa',
        //         'guard_name' => 'web',
        //     ],
        //     [
        //         'name' => 'Delete Master Desa',
        //         'guard_name' => 'web',
        //     ],
        //     [
        //         'name' => 'Update Master Desa',
        //         'guard_name' => 'web',
        //     ],
        //     [
        //         'name' => 'View Master Desa',
        //         'guard_name' => 'web',
        //     ],
        //     [
        //         'name' => 'Create Role',
        //         'guard_name' => 'web',
        //     ],
        //     [
        //         'name' => 'Delete Role',
        //         'guard_name' => 'web',
        //     ],
        //     [
        //         'name' => 'Update Role',
        //         'guard_name' => 'web',
        //     ],
        //     [
        //         'name' => 'View Role',
        //         'guard_name' => 'web',
        //     ],
        //     [
        //         'name' => 'Create Role',
        //         'guard_name' => 'web',
        //     ],
        //     [
        //         'name' => 'Delete Role',
        //         'guard_name' => 'web',
        //     ],
        //     [
        //         'name' => 'Update Role',
        //         'guard_name' => 'web',
        //     ],
        //     [
        //         'name' => 'View Role',
        //         'guard_name' => 'web',
        //     ],
        //     [
        //         'name' => 'Create Permission',
        //         'guard_name' => 'web',
        //     ],
        //     [
        //         'name' => 'Delete Permission',
        //         'guard_name' => 'web',
        //     ],
        //     [
        //         'name' => 'Update Permission',
        //         'guard_name' => 'web',
        //     ],
        //     [
        //         'name' => 'View Permission',
        //         'guard_name' => 'web',
        //     ],
        // ]);

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

        // $users = DB::table("users")
        //     ->where("id", "1")->first();

        // $role = DB::table("roles")->where("name", "dev")->first();
        // $users->role()->attach($role->id);

        // $this->call([
        //     ProvincesSeeder::class,
        //     CitiesSeeder::class,
        //     DistrictsSeeder::class,
        //     VillagesSeeder::class,
        // ]);
    }
}
