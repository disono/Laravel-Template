<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAuthHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auth_histories', function (Blueprint $table) {
            $table->integer('user_id');
            $table->string('ip', 100);
            $table->string('platform', 160);
            $table->enum('type', ['login', 'logout', 'update']);
            $table->string('content', 100)->nullable();
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
        Schema::drop('auth_histories');
    }
}
