<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePageReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('page_reports', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');

            $table->unsignedInteger('responded_by')->nullable();
            $table->foreign('responded_by')->references('id')->on('users');

            $table->unsignedInteger('page_report_reason_id')->nullable();
            $table->foreign('page_report_reason_id')->references('id')->on('page_report_reasons');

            $table->text('url')->nullable();
            $table->text('description')->nullable();

            $table->enum('status', ['Pending', 'Processing', 'Reopened', 'Denied', 'Closed'])->default('Pending');
            $table->integer('rating')->default(0);

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
        Schema::dropIfExists('page_reports');
    }
}
