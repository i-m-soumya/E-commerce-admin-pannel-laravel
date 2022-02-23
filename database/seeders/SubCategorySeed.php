<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class SubCategorySeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        for ($i = 0 ; $i < 50 ; $i++) {
            $sql = DB::table('product_sub_category')
                ->insert([
                    'name' => $faker->word,
                    'is_active' => 1,
                    'added_by' => 1,
                    'category_id' => rand(1,14),
                    'added_on' => time(),
                    'upload_id' => rand(1,3),
                ]);
        }
    }
}
