<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediaFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_files', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->default(0);

            $table->string('source_id', 100)->default(0);
            $table->string('source_type', 100)->default(0);

            $table->string('title', 100)->nullable();
            $table->text('description')->nullable();

            $table->string('file_name', 100);
            $table->string('file_type', 32)->nullable();
            $table->string('file_ext', 32)->nullable();

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
        Schema::dropIfExists('media_files');
    }
}
