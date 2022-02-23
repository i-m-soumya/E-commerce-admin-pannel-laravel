<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertInvoiceNumber extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //add invoice_number
        if (!Schema::hasColumn('orders', 'invoice_number')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('invoice_number')->nullable();
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
        //drop invoice number
        if (Schema::hasColumn('orders', 'invoice_number')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('invoice_number');
            });
        }
    }
}
