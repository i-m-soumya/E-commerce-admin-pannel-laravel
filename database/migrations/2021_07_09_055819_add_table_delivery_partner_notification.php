<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTableDeliveryPartnerNotification extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasTable('delivery_partner_notification')) {
            Schema::create('delivery_partner_notification', function (Blueprint $table){
                    $table->id();
                    $table->integer('aggregator_id');
                    $table->string('details');
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
        Schema::dropIfExists('delivery_partner_notification');
    }
}
