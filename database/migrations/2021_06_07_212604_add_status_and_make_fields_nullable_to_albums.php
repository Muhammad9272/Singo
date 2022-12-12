<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusAndMakeFieldsNullableToAlbums extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('albums', function (Blueprint $table) {
            $table->string('upc')->nullable(true)->change();
            $table->date('release')->nullable(true)->change();
            $table->tinyInteger('status')->after('release')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('albums', function (Blueprint $table) {
            $table->string('upc')->nullable(false)->change();
            $table->date('release')->nullable(false)->change();
            $table->dropColumn('status');
        });
    }
}
