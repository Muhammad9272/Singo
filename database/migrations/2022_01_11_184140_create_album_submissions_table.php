<?php

use App\Models\Album;
use App\Models\AlbumSubmission;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlbumSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('album_submissions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('album_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->tinyInteger('publisher')
                ->default(Album::PUBLISHER_FUGA)
                ->index();

            $table->foreignId('publisher_album_id')
                ->nullable();

            $table->tinyInteger('status')
                ->default(AlbumSubmission::PUBLISH_STATUS_IN_PROGRESS)
                ->index();

            /*
             * Structure
             * {
             *      STEP_NAME: CamelCase(String),
             *      STEP_STATUS: Boolean,
             *      STEP_MESSAGE: String
             *      STEP_EXTRA_CONTENT: String
             * }
             */
            $table->json('logs')->nullable();

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
        Schema::dropIfExists('album_submissions');
    }
}
