<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlateMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('plate_methods')->insert([ 'name' => 'Barrel Plated','created_at' => Carbon::now(), ]);
        DB::table('plate_methods')->insert([ 'name' => 'Rack Plated', 'created_at' => Carbon::now(),]);
        DB::table('plate_methods')->insert([ 'name' => 'Non-Plated','created_at' => Carbon::now(), ]);
        DB::table('plate_methods')->insert([ 'name' => 'Not Specified', 'created_at' => Carbon::now(),]);
    }
}
