<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnVilageIsActive extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('pin_wise_village', 'is_active')) {
            Schema::table('pin_wise_village', function (Blueprint $table) {
                $table->integer('is_active')->nullable();
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
        if (Schema::hasColumn('pin_wise_village', 'is_active')) { 
            Schema::table('pin_wise_village', function (Blueprint $table) {
                $table->dropColumn('is_active');
            });
        }
    }
}
