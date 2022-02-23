<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ExchangeOrderColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('orders_details', 'order_id')) {
            Schema::table('orders_details', function (Blueprint $table) {
                $table->integer('order_id');
            });
        }
        if (Schema::hasColumn('orders', 'order_details_id')) { 
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('order_details_id');
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
        if (Schema::hasColumn('orders_details', 'order_id')) {
            Schema::table('orders_details', function (Blueprint $table) {
                $table->dropColumn('order_id');
            });
        }
        if (!Schema::hasColumn('orders', 'order_details_id')) { 
            Schema::table('orders', function (Blueprint $table) {
                $table->integer('order_details_id');
            });
        }
    }
}
