<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          DB::table('gears')->insert([
            ['id' => 1, 'name' => 'Manual'],
            ['id' => 2, 'name' => 'Automatic'],
            ['id' => 3, 'name' => 'CVT'],
            ['id' => 4, 'name' => 'Semi-Automatic'],
        ]);
    }
}
