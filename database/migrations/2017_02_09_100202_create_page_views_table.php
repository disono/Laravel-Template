<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->integer('user_id')->default(0);
            $table->text('http_referrer')->nullable();
            $table->text('current_url')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->text('browser')->nullable();

            $table->string('type', 100)->nullable();
            $table->string('source_id', 100)->nullable();
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
