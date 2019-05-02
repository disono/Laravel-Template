<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatGroupMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_group_members', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('chat_group_id')->nullable();
            $table->foreign('chat_group_id')->references('id')->on('chat_groups');

            $table->unsignedInteger('member_id')->nullable();
            $table->foreign('member_id')->references('id')->on('users');

            $table->unsignedInteger('added_by_id')->nullable();
            $table->foreign('added_by_id')->references('id')->on('users');

            $table->boolean('is_admin')->default(0);
            $table->boolean('is_mute')->default(0);
            $table->boolean('is_active')->default(0);
            $table->boolean('is_seen')->default(0);
            $table->boolean('is_archive')->default(0);

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
        Schema::dropIfExists('chat_group_members');
    }
}
