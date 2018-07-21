<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTrackersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_trackers', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');

            $table->double('lat', 22, 12);
            $table->double('lng', 22, 12);

            $table->string('device_id', 100)->nullable()->default(null);
            $table->text('http_referrer')->nullable()->default(null);
            $table->text('current_url')->nullable()->default(null);
            $table->ipAddress('ip_address')->nullable()->default(null);
            $table->text('platform')->nullable()->default(null);
            $table->text('browser')->nullable()->default(null);

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
        Schema::dropIfExists('user_trackers');
    }
}
