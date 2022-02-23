<?php

namespace Database\Seeders;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Seeder;

class CategorySeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        for ($i = 0 ; $i < 10 ; $i++) {
            $sql = DB::table('product_category')
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
