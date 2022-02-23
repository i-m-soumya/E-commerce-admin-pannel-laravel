<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomerNameInOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('orders', 'delivery_charge')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->float('delivery_charge')->default(0.00);
            });
        }
        if (!Schema::hasColumn('orders', 'customer_name')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('customer_name')->nullable();
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
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'delivery_charge')) {
                Schema::table('orders', function (Blueprint $table) {
                    $table->dropColumn('delivery_charge');
                });
            }
            if (Schema::hasColumn('orders', 'customer_name')) {
                Schema::table('orders', function (Blueprint $table) {
                    $table->dropColumn('customer_name');
                });
            }
        });
    }
}
