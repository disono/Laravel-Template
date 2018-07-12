<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFirebaseTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('firebase_tokens', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('token_id')->nullable();
            $table->foreign('token_id')->references('id')->on('tokens');

            $table->text('fcm_token');
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
        Schema::dropIfExists('firebase_tokens');
    }
}
