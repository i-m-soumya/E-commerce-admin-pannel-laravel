<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CancellationReasonId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('orders', 'cancellation_reason_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->integer('cancellation_reason_id')->nullable();
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
        if (Schema::hasColumn('orders', 'cancellation_reason_id')) { 
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('cancellation_reason_id');
            });
        }
    }
}
