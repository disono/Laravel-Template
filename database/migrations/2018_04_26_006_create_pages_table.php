<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->string('name', 100);
            $table->text('content')->nullable();
            $table->string('summary', 220)->nullable();
            $table->string('slug', 100)->unique();
            $table->text('tags')->nullable();
            $table->string('template', 100)->nullable();
            $table->boolean('is_draft')->default(0);
            $table->boolean('is_email_to_subscriber')->default(0);
            $table->dateTime('post_at')->nullable();
            $table->dateTime('expired_at')->nullable();

            $table->text('seo_description')->nullable();
            $table->text('seo_keywords')->nullable();
            $table->string('seo_robots', 100)->nullable();
            $table->string('og_url', 100)->nullable();
            $table->string('og_type', 100)->nullable();
            $table->string('og_title', 180)->nullable();
            $table->string('og_description', 180)->nullable();

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
        Schema::dropIfExists('pages');
    }
}
