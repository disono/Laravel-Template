<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->integer('image_id');

            $table->string('gender', 6)->nullable()->default('Male');
            $table->text('address')->nullable();
            $table->integer('country_id')->default(173);
            $table->string('phone', 22)->nullable();
            $table->date('birthday');
            $table->text('about');

            $table->string('username', 32)->unique();
            $table->string('email', 100)->unique();
            $table->string('password', 100);

            $table->boolean('enabled', 1)->default(0);
            $table->boolean('email_confirmed', 1)->default(0);

            $table->string('role', 22);
            $table->rememberToken();
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
        Schema::drop('users');
    }
}
