<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AggregatorNotificationTableTimestampColumnAdd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('delivery_partner_notification', 'timestamp')) {
            Schema::table('delivery_partner_notification', function (Blueprint $table) {
                $table->bigInteger('timestamp')->nullable();
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
        if (Schema::hasColumn('delivery_partner_notification', 'timestamp')) {
            Schema::table('delivery_partner_notification', function (Blueprint $table) {
                $table->dropColumn('timestamp');
            });
        }
    }
}
