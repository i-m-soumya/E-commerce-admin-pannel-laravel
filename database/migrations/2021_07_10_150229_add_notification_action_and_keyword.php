<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNotificationActionAndKeyword extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('user_notification', 'action')) {
            Schema::table('user_notification', function (Blueprint $table) {
                $table->string('action')->nullable();
            });
        }
        if (!Schema::hasColumn('user_notification', 'action_keyword')) {
            Schema::table('user_notification', function (Blueprint $table) {
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
        if (Schema::hasColumn('user_notification', 'action')) {
            Schema::table('user_notification', function (Blueprint $table) {
                $table->dropColumn('action');
            });
        }
        if (Schema::hasColumn('user_notification', 'action_keyword')) {
            Schema::table('user_notification', function (Blueprint $table) {
                $table->dropColumn('action_keyword');
            });
        }
    }
}
