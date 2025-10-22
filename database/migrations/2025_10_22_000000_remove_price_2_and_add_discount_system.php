<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemovePrice2AndAddDiscountSystem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_groups', function (Blueprint $table) {
            $table->decimal('discount_amount', 10, 2)->default(0.00)->after('user_id');
        });

        Schema::table('user_groups', function (Blueprint $table) {
            $table->dropColumn('price_type');
        });

        Schema::table('groups', function (Blueprint $table) {
            $table->dropColumn('price_2');
        });

        Schema::table('groups', function (Blueprint $table) {
            $table->renameColumn('price_1', 'price');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->renameColumn('price', 'price_1');
        });

        Schema::table('groups', function (Blueprint $table) {
            $table->decimal('price_2', 10, 2)->nullable()->after('price_1');
        });

        Schema::table('user_groups', function (Blueprint $table) {
            $table->string('price_type')->default('price_1')->after('user_id');
        });

        Schema::table('user_groups', function (Blueprint $table) {
            $table->dropColumn('discount_amount');
        });
    }
}
