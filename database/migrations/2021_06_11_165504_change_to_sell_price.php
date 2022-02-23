<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeToSellPrice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //drop total_buy_mrp
        if (Schema::hasColumn('orders', 'total_buy_mrp')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('total_buy_mrp');
            });
        }
        //add total_sell_price
        if (!Schema::hasColumn('orders', 'total_sell_price')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->float('total_sell_price')->nullable();
            });
        }
        //drop column per_qty_buy_mrp
        if (Schema::hasColumn('orders_details', 'per_qty_buy_mrp')) {
            Schema::table('orders_details', function (Blueprint $table) {
                $table->dropColumn('per_qty_buy_mrp');
            });
        }
        //add per_wty_sell_price
        if (!Schema::hasColumn('orders_details', 'per_qty_sell_price')) {
            Schema::table('orders_details', function (Blueprint $table) {
                $table->float('per_qty_sell_price')->nullable();
            });
        }
        //drop mrp_per_qty
        if (Schema::hasColumn('orders_details', 'mrp_per_qty')) {
            Schema::table('orders_details', function (Blueprint $table) {
                $table->dropColumn('mrp_per_qty');
            });
        }
        //drop applied_coupon_id
        if (Schema::hasColumn('orders', 'applied_coupon_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('applied_coupon_id');
            });
        }
        //add applied_coupon_amount
        if (!Schema::hasColumn('orders', 'applied_coupon_amount')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->float('applied_coupon_amount')->default(0.00);
            });
        }
        //add total_payable_amount
        if (!Schema::hasColumn('orders', 'total_payable_amount')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->float('total_payable_amount')->nullable();
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

    }
}
