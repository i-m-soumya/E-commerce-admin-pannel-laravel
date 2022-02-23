<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class BrandSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        for ($i = 0 ; $i < 100 ; $i++) {
            $sql = DB::table('brands')
                ->insert([
                    'name' => $faker->word,
                    'is_active' => 1,
                    'added_by' => 1,
                    'added_on' => time(),
                    'upload_id' => rand(1,3),
                ]);
        }
    }
}
