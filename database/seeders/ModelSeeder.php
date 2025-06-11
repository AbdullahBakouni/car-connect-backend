<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       DB::table('models')->insert([
    // Toyota
    ['id' => 1, 'name' => 'Corolla', 'brandId' => 1],
    ['id' => 2, 'name' => 'Camry', 'brandId' => 1],
    ['id' => 3, 'name' => 'Yaris', 'brandId' => 1],
    ['id' => 4, 'name' => 'Land Cruiser', 'brandId' => 1],
    ['id' => 5, 'name' => 'RAV4', 'brandId' => 1],

    // Hyundai
    ['id' => 6, 'name' => 'Elantra', 'brandId' => 2],
    ['id' => 7, 'name' => 'Sonata', 'brandId' => 2],
    ['id' => 8, 'name' => 'Tucson', 'brandId' => 2],
    ['id' => 9, 'name' => 'Santa Fe', 'brandId' => 2],
    ['id' => 10, 'name' => 'Accent', 'brandId' => 2],

    // BMW
    ['id' => 11, 'name' => '3 Series', 'brandId' => 3],
    ['id' => 12, 'name' => '5 Series', 'brandId' => 3],
    ['id' => 13, 'name' => 'X3', 'brandId' => 3],
    ['id' => 14, 'name' => 'X5', 'brandId' => 3],
    ['id' => 15, 'name' => '7 Series', 'brandId' => 3],

    // Mercedes-Benz
    ['id' => 16, 'name' => 'C-Class', 'brandId' => 4],
    ['id' => 17, 'name' => 'E-Class', 'brandId' => 4],
    ['id' => 18, 'name' => 'GLA', 'brandId' => 4],
    ['id' => 19, 'name' => 'GLC', 'brandId' => 4],
    ['id' => 20, 'name' => 'S-Class', 'brandId' => 4],

    // Ford
    ['id' => 21, 'name' => 'F-150', 'brandId' => 5],
    ['id' => 22, 'name' => 'Escape', 'brandId' => 5],
    ['id' => 23, 'name' => 'Mustang', 'brandId' => 5],
    ['id' => 24, 'name' => 'Explorer', 'brandId' => 5],
    ['id' => 25, 'name' => 'Edge', 'brandId' => 5],

    // Kia
    ['id' => 26, 'name' => 'Sportage', 'brandId' => 6],
    ['id' => 27, 'name' => 'Sorento', 'brandId' => 6],
    ['id' => 28, 'name' => 'Rio', 'brandId' => 6],
    ['id' => 29, 'name' => 'Cerato', 'brandId' => 6],
    ['id' => 30, 'name' => 'Telluride', 'brandId' => 6],

    // Chevrolet
    ['id' => 31, 'name' => 'Malibu', 'brandId' => 7],
    ['id' => 32, 'name' => 'Impala', 'brandId' => 7],
    ['id' => 33, 'name' => 'Cruze', 'brandId' => 7],
    ['id' => 34, 'name' => 'Equinox', 'brandId' => 7],
    ['id' => 35, 'name' => 'Tahoe', 'brandId' => 7],

    // Nissan
    ['id' => 36, 'name' => 'Altima', 'brandId' => 8],
    ['id' => 37, 'name' => 'Sentra', 'brandId' => 8],
    ['id' => 38, 'name' => 'Maxima', 'brandId' => 8],
    ['id' => 39, 'name' => 'Rogue', 'brandId' => 8],
    ['id' => 40, 'name' => 'Pathfinder', 'brandId' => 8],

    // Volkswagen
    ['id' => 41, 'name' => 'Golf', 'brandId' => 9],
    ['id' => 42, 'name' => 'Passat', 'brandId' => 9],
    ['id' => 43, 'name' => 'Jetta', 'brandId' => 9],
    ['id' => 44, 'name' => 'Tiguan', 'brandId' => 9],
    ['id' => 45, 'name' => 'Touareg', 'brandId' => 9],

    // Honda
    ['id' => 46, 'name' => 'Civic', 'brandId' => 10],
    ['id' => 47, 'name' => 'Accord', 'brandId' => 10],
    ['id' => 48, 'name' => 'CR-V', 'brandId' => 10],
    ['id' => 49, 'name' => 'HR-V', 'brandId' => 10],
    ['id' => 50, 'name' => 'Pilot', 'brandId' => 10],

     // Mazda
    ['id' => 51, 'name' => 'Mazda 3', 'brandId' => 11],
    ['id' => 52, 'name' => 'Mazda 6', 'brandId' => 11],
    ['id' => 53, 'name' => 'CX-5', 'brandId' => 11],
    ['id' => 54, 'name' => 'CX-30', 'brandId' => 11],
    ['id' => 55, 'name' => 'MX-5', 'brandId' => 11],

    // Audi
    ['id' => 56, 'name' => 'A3', 'brandId' => 12],
    ['id' => 57, 'name' => 'A4', 'brandId' => 12],
    ['id' => 58, 'name' => 'Q5', 'brandId' => 12],
    ['id' => 59, 'name' => 'Q7', 'brandId' => 12],
    ['id' => 60, 'name' => 'A6', 'brandId' => 12],

    // Porsche
    ['id' => 61, 'name' => '911', 'brandId' => 13],
    ['id' => 62, 'name' => 'Cayenne', 'brandId' => 13],
    ['id' => 63, 'name' => 'Panamera', 'brandId' => 13],
    ['id' => 64, 'name' => 'Taycan', 'brandId' => 13],
    ['id' => 65, 'name' => 'Macan', 'brandId' => 13],

    // Jeep
    ['id' => 66, 'name' => 'Wrangler', 'brandId' => 14],
    ['id' => 67, 'name' => 'Grand Cherokee', 'brandId' => 14],
    ['id' => 68, 'name' => 'Renegade', 'brandId' => 14],
    ['id' => 69, 'name' => 'Compass', 'brandId' => 14],
    ['id' => 70, 'name' => 'Gladiator', 'brandId' => 14],

    // Subaru
    ['id' => 71, 'name' => 'Impreza', 'brandId' => 15],
    ['id' => 72, 'name' => 'Forester', 'brandId' => 15],
    ['id' => 73, 'name' => 'Outback', 'brandId' => 15],
    ['id' => 74, 'name' => 'Crosstrek', 'brandId' => 15],
    ['id' => 75, 'name' => 'Legacy', 'brandId' => 15],

    // Lexus
    ['id' => 76, 'name' => 'RX', 'brandId' => 16],
    ['id' => 77, 'name' => 'NX', 'brandId' => 16],
    ['id' => 78, 'name' => 'ES', 'brandId' => 16],
    ['id' => 79, 'name' => 'IS', 'brandId' => 16],
    ['id' => 80, 'name' => 'GX', 'brandId' => 16],

    // Tesla
    ['id' => 81, 'name' => 'Model S', 'brandId' => 17],
    ['id' => 82, 'name' => 'Model 3', 'brandId' => 17],
    ['id' => 83, 'name' => 'Model X', 'brandId' => 17],
    ['id' => 84, 'name' => 'Model Y', 'brandId' => 17],
    ['id' => 85, 'name' => 'Cybertruck', 'brandId' => 17],

    // Peugeot
    ['id' => 86, 'name' => '208', 'brandId' => 18],
    ['id' => 87, 'name' => '308', 'brandId' => 18],
    ['id' => 88, 'name' => '3008', 'brandId' => 18],
    ['id' => 89, 'name' => '5008', 'brandId' => 18],
    ['id' => 90, 'name' => '2008', 'brandId' => 18],

    // Renault
    ['id' => 91, 'name' => 'Clio', 'brandId' => 19],
    ['id' => 92, 'name' => 'Megane', 'brandId' => 19],
    ['id' => 93, 'name' => 'Captur', 'brandId' => 19],
    ['id' => 94, 'name' => 'Kadjar', 'brandId' => 19],
    ['id' => 95, 'name' => 'Duster', 'brandId' => 19], 

    // Skoda
    ['id' => 96, 'name' => 'Octavia', 'brandId' => 20],
    ['id' => 97, 'name' => 'Superb', 'brandId' => 20],
    ['id' => 98, 'name' => 'Kodiaq', 'brandId' => 20],
    ['id' => 99, 'name' => 'Fabia', 'brandId' => 20],
    ['id' => 100, 'name' => 'Scala', 'brandId' => 20],
]);
    }
}
