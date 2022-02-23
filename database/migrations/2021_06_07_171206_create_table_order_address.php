<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableOrderAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('order_address')) {
            Schema::create('order_address', function (Blueprint $table){
                    $table->id();
                    $table->integer('customer_id');
                    $table->string('city');
                    $table->integer('village_id');
                    $table->string('house_no');
                    $table->string('area');
                    $table->string('landmark');
                    $table->string('state');
                    $table->string('country');
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
        Schema::dropIfExists('order_address');
    }
}
