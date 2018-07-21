<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePageViewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('page_views', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('page_id')->nullable();
            $table->foreign('page_id')->references('id')->on('pages');

            $table->unsignedInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');

            $table->string('device_id', 100)->nullable()->default(null);
            $table->text('http_referrer')->nullable()->default(null);
            $table->text('current_url')->nullable()->default(null);
            $table->ipAddress('ip_address')->nullable()->default(null);
            $table->text('platform')->nullable()->default(null);
            $table->text('browser')->nullable()->default(null);

            $table->date('expired_at')->nullable()->default(null);
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
        Schema::dropIfExists('page_views');
    }
}
