<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CategorySubcategoryAddUploadId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('product_category', 'upload_id')) {
            Schema::table('product_category', function (Blueprint $table) {
                $table->integer('upload_id')->nullable();
            });
        }
        if (!Schema::hasColumn('product_sub_category', 'upload_id')) {
            Schema::table('product_sub_category', function (Blueprint $table) {
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
        if (Schema::hasColumn('product_category', 'upload_id')) { 
            Schema::table('product_category', function (Blueprint $table) {
                $table->dropColumn('upload_id');
            });
        }
        if (Schema::hasColumn('product_sub_category', 'upload_id')) { 
            Schema::table('product_sub_category', function (Blueprint $table) {
                $table->dropColumn('upload_id');
            });
        }
    }
}
