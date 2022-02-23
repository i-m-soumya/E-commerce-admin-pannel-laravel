<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderAddressIdOnOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('orders', 'order_address_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->integer('order_address_id');
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
        if (Schema::hasColumn('orders', 'order_address_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('order_address_id');
            });
        }
    }
}
