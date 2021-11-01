<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLanguageWordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('language_word', function (Blueprint $table) {
            $table->foreignId('word_id')
                ->index()
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('language_id')
                ->constrained()
                ->onDelete('restrict');
            $table->foreignId('image_id')
                ->nullable()
                ->constrained()
                ->onDelete('set null');
            $table->string('translation');
            $table->string('transcription');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('language_word');
    }
}
