<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPayoutStatusDefault extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payouts', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        Schema::table('payouts', function (Blueprint $table) {
            $table->tinyInteger('status')->after('user_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payouts', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        Schema::table('payouts', function (Blueprint $table) {
            $table->tinyInteger('status')->after('user_id');
        });
    }
}
