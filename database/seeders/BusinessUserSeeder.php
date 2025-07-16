<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BusinessUserSeeder extends Seeder
{
    public function run()
    {
        DB::table('business_user')->insert([
            [
                'name' => 'شركة المستقبل',
                'phone' => '0999999999',
                'desc' => 'شركة تأجير سيارات حديثة',
                'type' => 1,
                'lat' => '33.5138',
                'long' => '36.2765',
                'idImageUrl' => null,
                'commercialRegisterImageUrl' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'شركة النور',
                'phone' => '0988888888',
                'desc' => 'شركة سيارات فاخرة',
                'type' => 1,
                'lat' => '33.5102',
                'long' => '36.2913',
                'idImageUrl' => null,
                'commercialRegisterImageUrl' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
} 