<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GearModel;

class GearSeeder extends Seeder
{
    public function run(): void
    {
        $gears = [
            ['name' => 'Manual'],
            ['name' => 'Automatic']
        ];

        foreach ($gears as $gear) {
            GearModel::create($gear);
        }
    }
} 