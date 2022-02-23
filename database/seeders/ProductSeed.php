<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;


class ProductSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 0 ; $i < 5000 ; $i++){
            $faker = Faker::create();
            $unit_type_id = rand(1,5);
            $quantity = rand(1,25); 
            $mrp = rand(100,2500);
            $discount = rand(1,25);
            $sell_price = $mrp - ($mrp * ($discount/100));
            $brand_id = rand(1,100);
            $category_id = rand(1,10);
            $subcategory_id = rand(1,50);
            $min_qty = rand(1,10);
            $max_qty = rand(50,250);
            $created_on = time();

            $sql = DB::table('product')
                ->insertGetId([
                    'name' => $faker->word,
                    'discount' => $discount,
                    'desc' => $faker->word,
                    'min_qty' => $min_qty,
                    'unit_type_id' => $unit_type_id,
                    'max_qty' => $max_qty,
                    'quantity' => $quantity,
                    'is_in_stock' => 1,
                    'mrp' => $mrp,
                    'created_on' => $created_on,
                    'sell_price' => $sell_price,
                    'brand_id' => $brand_id,
                    'category_id' => $category_id,
                    'subcategory_id' => $subcategory_id,
                    'added_by' => 1,
                ]);
            if($sql) {
                $sql = DB::table('product_images')
                ->insert([
                    'product_id' => $sql,
                    'upload_id' => rand(1,3),
                ]);
            }
        }
        
        
    }
}
