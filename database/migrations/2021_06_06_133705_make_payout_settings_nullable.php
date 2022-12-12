<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakePayoutSettingsNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('btcAddress')->nullable(true)->change();
            $table->string('ltcAddress')->nullable(true)->change();
            $table->string('ethAddress')->nullable(true)->change();
            $table->string('paypalEmail')->nullable(true)->change();
            $table->string('iban')->nullable(true)->change();
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
            $table->string('btcAddress')->nullable(false)->change();
            $table->string('ltcAddress')->nullable(false)->change();
            $table->string('ethAddress')->nullable(false)->change();
            $table->string('paypalEmail')->nullable(false)->change();
            $table->string('iban')->nullable(false)->change();
        });
    }
}
