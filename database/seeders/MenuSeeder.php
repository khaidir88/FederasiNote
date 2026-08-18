<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // MENU UTAMA
        $home = Menu::create([
            'name' => 'Home',
            'slug' => 'home',
            'position' => 'header',
            'order' => 1
        ]);

        $nasional = Menu::create([
            'name' => 'Nasional',
            'slug' => 'nasional',
            'position' => 'header',
            'order' => 2
        ]);

        $daerah = Menu::create([
            'name' => 'Daerah',
            'slug' => 'daerah',
            'position' => 'header',
            'order' => 3
        ]);

        // SUB MENU NASIONAL
        Menu::create([
            'name' => 'Politik',
            'slug' => 'politik',
            'parent_id' => $nasional->id,
            'order' => 1
        ]);

        Menu::create([
            'name' => 'Ekonomi',
            'slug' => 'ekonomi',
            'parent_id' => $nasional->id,
            'order' => 2
        ]);

        Menu::create([
            'name' => 'Hukum',
            'slug' => 'hukum',
            'parent_id' => $nasional->id,
            'order' => 3
        ]);

        // SUB MENU DAERAH
        Menu::create([
            'name' => 'Provinsi',
            'slug' => 'provinsi',
            'parent_id' => $daerah->id,
            'order' => 1
        ]);

        Menu::create([
            'name' => 'Kabupaten / Kota',
            'slug' => 'kabupaten-kota',
            'parent_id' => $daerah->id,
            'order' => 2
        ]);
    }
}
