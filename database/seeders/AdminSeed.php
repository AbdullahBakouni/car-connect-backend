<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeed extends Seeder
{
   
    public function run(): void
    {
        DB::table('admin')->insert([

            'email' => "superadmin@gmail.com",
            'password' => Hash::make(12345678),
        ]);
    }
}
