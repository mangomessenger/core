<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserChatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_chat', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('chat_id');
            $table->foreignId('user_id')->constrained('users');

            $table->foreign('chat_id', 'user_chat_chat_id_foreign_direct')
                ->references('id')
                ->on('direct_chats');

            $table->foreign('chat_id', 'user_chat_chat_id_foreign_channels')
                ->references('id')
                ->on('channels');

            $table->foreign('chat_id', 'user_chat_chat_id_foreign_groups')
                ->references('id')
                ->on('groups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_chat');
    }
}
