<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRewardRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reward_requests', function (Blueprint $table) {
            $table->id();

            $table->integer('user_id')->nullable();
            $table->integer('reward_id')->nullable();

            $table->string('fname')->nullable();
            $table->string('lname')->nullable();
            $table->string('street_no')->nullable();
            $table->string('contact_no')->nullable();

            $table->string('zip_code')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('info')->nullable();
                       
            $table->string('status')->default("pending");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reward_requests');
    }
}
