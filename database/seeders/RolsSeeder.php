<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        DB::table('rols')->insert([ 'name' => 'Master Administrator','created_at' => Carbon::now(), ]);
        DB::table('rols')->insert([ 'name' => 'Administrator','created_at' => Carbon::now(), ]);
        DB::table('rols')->insert([ 'name' => 'Distributor','created_at' => Carbon::now(), ]);
        DB::table('rols')->insert([ 'name' => 'Customer','created_at' => Carbon::now(), ]);
    }
}
