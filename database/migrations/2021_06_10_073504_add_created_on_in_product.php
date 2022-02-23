<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCreatedOnInProduct extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('product', 'created_on')) {
            Schema::table('product', function (Blueprint $table) {
                $table->integer('created_on')->nullable();
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
        if (Schema::hasColumn('product', 'created_on')) { 
            Schema::table('product', function (Blueprint $table) {
                $table->dropColumn('created_on');
            });
        }
    }
}
