<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BrandTableAlterUploadId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (! Schema::hasColumn('brands', 'upload_id')) {
            Schema::table('brands', function (Blueprint $table) {
                $table->integer('upload_id')->nullable();   
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
        if (Schema::hasColumn('brands', 'upload_id')) {
            Schema::table('brands', function (Blueprint $table) {
                $table->dropColumn('upload_id');
            });
        }
    }
}