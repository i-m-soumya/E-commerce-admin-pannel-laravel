<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnIsActiveInProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('product', 'is_active')) {
            Schema::table('product', function (Blueprint $table) {
                $table->integer('is_active')->default(1);
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
        if (Schema::hasColumn('product', 'is_active')) {
            Schema::table('product', function (Blueprint $table) {
                $table->dropColumn('is_active');
            });
        }
    }
}
