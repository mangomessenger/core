<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChatMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('chat_id');
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();

            $table->foreign('chat_id', 'chat_members_chat_id_foreign_direct')
                ->references('id')
                ->on('direct_chats');

            $table->foreign('chat_id', 'chat_members_chat_id_foreign_channels')
                ->references('id')
                ->on('channels');

            $table->foreign('chat_id', 'chat_members_chat_id_foreign_groups')
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
        Schema::table('chat_members', function (Blueprint $table) {
            //
        });
    }
}
