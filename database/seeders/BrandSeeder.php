<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BrandModel;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            // Japanese Brands
            ['name' => 'Toyota'],
            ['name' => 'Honda'],
            ['name' => 'Nissan'],
            ['name' => 'Mitsubishi'],
            ['name' => 'Mazda'],
            ['name' => 'Subaru'],
            ['name' => 'Suzuki'],
            ['name' => 'Lexus'],
            ['name' => 'Infiniti'],
            ['name' => 'Acura'],
            
            // German Brands
            ['name' => 'BMW'],
            ['name' => 'Mercedes-Benz'],
            ['name' => 'Audi'],
            ['name' => 'Volkswagen'],
            ['name' => 'Porsche'],
            
            // Korean Brands
            ['name' => 'Hyundai'],
            ['name' => 'Kia'],
            ['name' => 'Genesis'],
            
            // American Brands
            ['name' => 'Ford'],
            ['name' => 'Chevrolet'],
            ['name' => 'Dodge'],
            ['name' => 'Jeep'],
            ['name' => 'GMC'],
            ['name' => 'Cadillac'],
            ['name' => 'Buick'],
            ['name' => 'Lincoln'],
            
            // European Brands
            ['name' => 'Volvo'],
            ['name' => 'Jaguar'],
            ['name' => 'Land Rover'],
            ['name' => 'Mini'],
            ['name' => 'Fiat'],
            ['name' => 'Alfa Romeo'],
            ['name' => 'Peugeot'],
            ['name' => 'Renault'],
            ['name' => 'Citroën'],
            
            // Luxury Brands
            ['name' => 'Bentley'],
            ['name' => 'Rolls-Royce'],
            ['name' => 'Lamborghini'],
            ['name' => 'Ferrari'],
            ['name' => 'Maserati'],
            ['name' => 'Bugatti'],
            ['name' => 'Aston Martin'],
            
            // Other
            ['name' => 'Other']
        ];

        foreach ($brands as $brand) {
            BrandModel::create($brand);
        }
    }
} 