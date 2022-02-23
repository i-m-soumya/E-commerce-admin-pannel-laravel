<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReplyFeedback extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('customer_feedback', 'reply_message')) {
            Schema::table('customer_feedback', function (Blueprint $table) {
                $table->string('reply_message')->nullable();
            });
        }
        if (!Schema::hasColumn('customer_feedback', 'is_replied')) {
            Schema::table('customer_feedback', function (Blueprint $table) {
                $table->integer('is_replied')->nullable();
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
        if (Schema::hasColumn('customer_feedback', 'reply_message')) { 
            Schema::table('customer_feedback', function (Blueprint $table) {
                $table->dropColumn('reply_message');
            });
        }
        if (Schema::hasColumn('customer_feedback', 'is_replied')) { 
            Schema::table('customer_feedback', function (Blueprint $table) {
                $table->dropColumn('is_replied');
            });
        }
    }
}
