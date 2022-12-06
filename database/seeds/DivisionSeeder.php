<?php

use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('division')->insert([ 'id' => '3', 'description' => 'RD / ARD Office', 'head' => 271 ]);
        DB::table('division')->insert([ 'id' => '4', 'description' => 'LHSD - Local Health Support Division', 'head' => 69 ]);
        DB::table('division')->insert([ 'id' => '5', 'description' => 'LRED - Licensing Reulations & Enforcement Divsion', 'head' => 54 ]);
        DB::table('division')->insert([ 'id' => '6', 'description' => 'MSD - Management Support Division', 'head' => 170 ]);
        DB::table('division')->insert([ 'id' => '7', 'description' => 'HMS - Visayas Division', 'head' => 171 ]);
    }
}
