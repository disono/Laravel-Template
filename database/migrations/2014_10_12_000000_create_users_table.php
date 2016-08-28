<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

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
            $table->string('middle_name', 100)->nullable();
            $table->string('last_name', 100);
            $table->integer('image_id')->default(0);

            $table->string('gender', 6)->nullable()->default('Male');
            $table->text('address')->nullable();
            $table->integer('country_id')->default(173);
            $table->string('phone', 22)->nullable();
            $table->date('birthday')->nullable();
            $table->text('about')->nullable();

            $table->string('email', 100)->unique()->nullable();
            $table->string('password', 100);

            $table->boolean('enabled', 1)->default(0);
            $table->boolean('email_confirmed', 1)->default(0);

            $table->string('role', 22)->nullable();
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
