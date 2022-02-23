<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BrandTableAlterAddedBy extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasColumn('brands', 'added_by')) {
            Schema::table('brands', function (Blueprint $table) {
                $table->integer('added_by')->default(1);   
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
        if (Schema::hasColumn('brands', 'added_by')) {
            Schema::table('brands', function (Blueprint $table) {
                $table->dropColumn('added_by');
            });
        }
    }
}