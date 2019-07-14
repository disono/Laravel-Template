<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSocketNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('socket_notifications', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');

            $table->string('title', 100);
            $table->string('content', 100);
            // token, topic
            $table->enum('type', ['topic', 'token'])->default('topic');
            $table->string('topic_name', 100)->nullable();
            $table->text('token')->nullable();
            
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
        Schema::dropIfExists('socket_notifications');
    }
}
