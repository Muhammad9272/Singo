<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNameAndPayoutToUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('username');
            $table->string('name');
            $table->string('artistName');
            $table->string('btcAddress');
            $table->string('ltcAddress');
            $table->string('ethAddress');
            $table->string('paypalEmail');
            $table->string('iban');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique();
            $table->dropColumn(['name', 'artistName', 'btcAddress', 'ltcAddress', 'ethAddress', 'paypalEmail', 'iban']);
        });
    }
}
