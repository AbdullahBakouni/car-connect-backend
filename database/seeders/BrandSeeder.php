<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('brands')->insert([
            ['id' => 1, 'name' => 'Toyota'],
            ['id' => 2, 'name' => 'Hyundai'],
            ['id' => 3, 'name' => 'BMW'],
            ['id' => 4, 'name' => 'Mercedes-Benz'],
            ['id' => 5, 'name' => 'Ford'],
            ['id' => 6, 'name' => 'Kia'],
            ['id' => 7, 'name' => 'Chevrolet'],
            ['id' => 8, 'name' => 'Nissan'],
            ['id' => 9, 'name' => 'Volkswagen'],
            ['id' => 10, 'name' => 'Honda'],
            ['id' => 11, 'name' => 'Mazda'],
            ['id' => 12, 'name' => 'Audi'],
            ['id' => 13, 'name' => 'Porsche'],
            ['id' => 14, 'name' => 'Jeep'],
            ['id' => 15, 'name' => 'Subaru'],
            ['id' => 16, 'name' => 'Lexus'],
            ['id' => 17, 'name' => 'Tesla'],
            ['id' => 18, 'name' => 'Peugeot'],
            ['id' => 19, 'name' => 'Renault'],
            ['id' => 20, 'name' => 'Skoda'],
        ]);
    }
}
