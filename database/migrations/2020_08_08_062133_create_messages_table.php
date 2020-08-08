<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->unsignedInteger('id')->index();
            $table->unsignedInteger('chat_id');
            $table->foreignId('from_id')->constrained('users');

            $table->unsignedInteger('reply_to_msg_id')->nullable();
            $table->string('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();

            $table->foreign('chat_id', 'messages_chat_id_foreign_direct')
                ->references('id')
                ->on('direct_chats');

            $table->foreign('chat_id', 'messages_chat_id_foreign_channels')
                ->references('id')
                ->on('channels');

            $table->foreign('chat_id', 'messages_chat_id_foreign_groups')
                ->references('id')
                ->on('groups');
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->foreign('reply_to_msg_id')
                ->references('id')
                ->on('messages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
