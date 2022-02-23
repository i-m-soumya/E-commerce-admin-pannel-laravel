<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterColumnTimestamp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //admin_table
        if (Schema::hasColumn('admin', 'created_at')) {
            Schema::table('admin', function (Blueprint $table) {
                $table->dropColumn('created_at');
            });
        }
        if (!Schema::hasColumn('admin', 'created_at')) {
            Schema::table('admin', function (Blueprint $table) {
                $table->integer('created_at')->nullable();
            });
        }
        //admin_type
        if (Schema::hasColumn('admin_type', 'created_at')) {
            Schema::table('admin_type', function (Blueprint $table) {
                $table->dropColumn('created_at');
            });
        }
        if (!Schema::hasColumn('admin_type', 'created_at')) {
            Schema::table('admin_type', function (Blueprint $table) {
                $table->integer('created_at')->nullable();
            });
        }
        //brands
        if (Schema::hasColumn('brands', 'added_on')) {
            Schema::table('brands', function (Blueprint $table) {
                $table->dropColumn('added_on');
            });
        }
        if (!Schema::hasColumn('brands', 'added_on')) {
            Schema::table('brands', function (Blueprint $table) {
                $table->integer('added_on')->nullable();
            });
        }
        //customers
        if (Schema::hasColumn('customers', 'created_at')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->dropColumn('created_at');
            });
        }
        if (!Schema::hasColumn('customers', 'created_at')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->integer('created_at')->nullable();
            });
        }
        if (Schema::hasColumn('customers', 'last_active')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->dropColumn('last_active');
            });
        }
        if (!Schema::hasColumn('customers', 'last_active')) {
            Schema::table('customers', function (Blueprint $table) {
                $table->integer('last_active')->nullable();
            });
        }
        //customer_feedback
        if (Schema::hasColumn('customer_feedback', 'submitted_on')) {
            Schema::table('customer_feedback', function (Blueprint $table) {
                $table->dropColumn('submitted_on');
            });
        }
        if (!Schema::hasColumn('customer_feedback', 'submitted_on')) {
            Schema::table('customer_feedback', function (Blueprint $table) {
                $table->integer('submitted_on')->nullable();
            });
        }
        //customer_search_history
        if (Schema::hasColumn('customer_search_history', 'timestamp')) {
            Schema::table('customer_search_history', function (Blueprint $table) {
                $table->dropColumn('timestamp');
            });
        }
        if (!Schema::hasColumn('customer_search_history', 'timestamp')) {
            Schema::table('customer_search_history', function (Blueprint $table) {
                $table->integer('timestamp')->nullable();
            });
        }
        // delivery_partner
        if (Schema::hasColumn('delivery_partner', 'created_at')) {
            Schema::table('delivery_partner', function (Blueprint $table) {
                $table->dropColumn('created_at');
            });
        }
        if (!Schema::hasColumn('delivery_partner', 'created_at')) {
            Schema::table('delivery_partner', function (Blueprint $table) {
                $table->integer('created_at')->nullable();
            });
        }
        //orders
        if (Schema::hasColumn('orders', 'ordered_on')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropColumn('ordered_on');
            });
        }
        if (!Schema::hasColumn('orders', 'ordered_on')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->integer('ordered_on')->nullable();
            });
        }
        //order_status_details
        if (Schema::hasColumn('order_status_details', 'added_on')) {
            Schema::table('order_status_details', function (Blueprint $table) {
                $table->dropColumn('added_on');
            });
        }
        if (!Schema::hasColumn('order_status_details', 'added_on')) {
            Schema::table('order_status_details', function (Blueprint $table) {
                $table->integer('added_on')->nullable();
            });
        }
        //password_resets
        if (Schema::hasColumn('password_resets', 'created_at')) {
            Schema::table('password_resets', function (Blueprint $table) {
                $table->dropColumn('created_at');
            });
        }
        if (!Schema::hasColumn('password_resets', 'created_at')) {
            Schema::table('password_resets', function (Blueprint $table) {
                $table->integer('created_at')->nullable();
            });
        }
        //product_category
        if (Schema::hasColumn('product_category', 'added_on')) {
            Schema::table('product_category', function (Blueprint $table) {
                $table->dropColumn('added_on');
            });
        }
        if (!Schema::hasColumn('product_category', 'added_on')) {
            Schema::table('product_category', function (Blueprint $table) {
                $table->integer('added_on')->nullable();
            });
        }
        //product_sub_category
        if (Schema::hasColumn('product_sub_category', 'added_on')) {
            Schema::table('product_sub_category', function (Blueprint $table) {
                $table->dropColumn('added_on');
            });
        }
        if (!Schema::hasColumn('product_sub_category', 'added_on')) {
            Schema::table('product_sub_category', function (Blueprint $table) {
                $table->integer('added_on')->nullable();
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

    }
}
