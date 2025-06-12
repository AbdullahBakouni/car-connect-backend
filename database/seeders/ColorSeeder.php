<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ColorModel;

class ColorSeeder extends Seeder
{
    public function run(): void
    {
        $colors = [
            ['name' => 'Black'],
            ['name' => 'White'],
            ['name' => 'Silver'],
            ['name' => 'Gray'],
            ['name' => 'Red'],
            ['name' => 'Blue'],
            ['name' => 'Green'],
            ['name' => 'Yellow'],
            ['name' => 'Brown'],
            ['name' => 'Other']
        ];

        foreach ($colors as $color) {
            ColorModel::create($color);
        }
    }
} 