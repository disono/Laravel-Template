<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePageClassificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('page_classifications', function (Blueprint $table) {
            $table->unsignedInteger('page_id');
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');

            $table->unsignedInteger('page_category_id');
            $table->foreign('page_category_id')->references('id')->on('page_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('page_classifications');
    }
}
