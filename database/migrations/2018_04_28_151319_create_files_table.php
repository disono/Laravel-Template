<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');

            $table->string('file_name', 100);
            $table->enum('type', ['video', 'photo', 'doc', 'file']);
            $table->string('ext', 45)->nullable();
            $table->string('title', 100)->nullable();
            $table->string('description', 100)->nullable();

            $table->string('table_name', 100)->nullable();
            $table->integer('table_id')->default(0);
            $table->string('tag', 100)->nullable();
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
        Schema::dropIfExists('files');
    }
}
