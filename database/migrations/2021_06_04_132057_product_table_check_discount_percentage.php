<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProductTableCheckDiscountPercentage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('product', 'discount')) {
            Schema::table('product', function (Blueprint $table) {
                $table->float('discount')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('product', 'discount_percentage')) { 
            Schema::table('product', function (Blueprint $table) {
                $table->dropColumn('discount_percentage');
            });
        }
    }
}
