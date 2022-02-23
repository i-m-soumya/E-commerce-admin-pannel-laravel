<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNotificationAndKeywordForDeliveryPartner extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('delivery_partner_notification', 'action')) {
            Schema::table('delivery_partner_notification', function (Blueprint $table) {
                $table->string('action')->nullable();
            });
        }
        if (!Schema::hasColumn('delivery_partner_notification', 'action_keyword')) {
            Schema::table('delivery_partner_notification', function (Blueprint $table) {
                $table->string('action_keyword')->nullable();
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
        if (Schema::hasColumn('delivery_partner_notification', 'action')) {
            Schema::table('delivery_partner_notification', function (Blueprint $table) {
                $table->dropColumn('action');
            });
        }
        if (Schema::hasColumn('delivery_partner_notification', 'action_keyword')) {
            Schema::table('delivery_partner_notification', function (Blueprint $table) {
                $table->dropColumn('action_keyword');
            });
        }
    }
}
