<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->string('key', 100);
            $table->text('value')->nullable();
            $table->string('description', 100)->nullable();
            $table->string('input_type', 100)->nullable();
            $table->text('input_value')->nullable();
            $table->text('attributes')->nullable();
            $table->boolean('is_disabled')->default(0);
            $table->boolean('is_public')->default(1);

            $table->unsignedInteger('category_setting_id')->nullable();
            $table->foreign('category_setting_id')->references('id')->on('setting_categories')->onDelete('cascade');

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
        Schema::dropIfExists('settings');
    }
}
