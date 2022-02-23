<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FcmToken extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('fcm_token')) {
            Schema::create('fcm_token', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id');
                $table->integer('user_type');
                $table->string('token', 512);
                $table->bigInteger('last_active');

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
        Schema::dropIfExists('fcm_token');
    }
}
