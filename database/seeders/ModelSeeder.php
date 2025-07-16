<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ModelModel;

class ModelSeeder extends Seeder
{
    public function run(): void
    {
        $models = [
            // Toyota Models
            ['name' => 'Camry'],
            ['name' => 'Corolla'],
            ['name' => 'RAV4'],
            ['name' => 'Land Cruiser'],
            ['name' => 'Hilux'],
            
            // Honda Models
            ['name' => 'Civic'],
            ['name' => 'Accord'],
            ['name' => 'CR-V'],
            ['name' => 'Pilot'],
            
            // BMW Models
            ['name' => '3 Series'],
            ['name' => '5 Series'],
            ['name' => 'X5'],
            ['name' => 'X3'],
            
            // Mercedes Models
            ['name' => 'C-Class'],
            ['name' => 'E-Class'],
            ['name' => 'S-Class'],
            ['name' => 'GLC'],
            
            // Hyundai Models
            ['name' => 'Elantra'],
            ['name' => 'Sonata'],
            ['name' => 'Tucson'],
            ['name' => 'Santa Fe'],
            
            // Kia Models
            ['name' => 'Sportage'],
            ['name' => 'Sorento'],
            ['name' => 'K5'],
            ['name' => 'Telluride'],
            
            // Other
            ['name' => 'Other']
        ];

        foreach ($models as $model) {
            ModelModel::create($model);
        }
    }
} 