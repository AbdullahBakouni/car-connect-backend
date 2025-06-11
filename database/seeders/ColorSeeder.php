<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::table('colors')->insert([
            ['id' => 1, 'name' => 'White'],
            ['id' => 2, 'name' => 'Black'],
            ['id' => 3, 'name' => 'Silver'],
            ['id' => 4, 'name' => 'Red'],
            ['id' => 5, 'name' => 'Blue'],
            ['id' => 6, 'name' => 'Gray'],
            ['id' => 7, 'name' => 'Green'],
            ['id' => 8, 'name' => 'Beige'],
            ['id' => 9, 'name' => 'Brown'],
            ['id' => 10, 'name' => 'Gold'],
        ]);
    }
}
