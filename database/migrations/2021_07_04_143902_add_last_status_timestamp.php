<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLastStatusTimestamp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('orders', 'last_status_timestamp')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->integer('last_status_timestamp')->nullable();
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
        if (Schema::hasColumn('orders', 'last_status_timestamp')) { 
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('last_status_timestamp');
            });
        }
    }
}
