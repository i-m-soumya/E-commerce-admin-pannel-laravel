<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProductDescProductNameOnOrderDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('orders_details', 'name')) {
            Schema::table('orders_details', function (Blueprint $table) {
                $table->string('name')->default("");
            });
        }
        if (!Schema::hasColumn('orders_details', 'description')) {
            Schema::table('orders_details', function (Blueprint $table) {
                $table->string('description')->default("");
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
        if (Schema::hasColumn('orders_details', 'name')) {
            Schema::table('orders_details', function (Blueprint $table) {
                $table->dropColumn('name');
            });
        }
        if (Schema::hasColumn('orders_details', 'description')) {
            Schema::table('orders_details', function (Blueprint $table) {
                $table->dropColumn('description');
            });
        }
    }
}
