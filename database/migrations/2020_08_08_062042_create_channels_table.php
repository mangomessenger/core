<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChannelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channels', function (Blueprint $table) {
            $table->unsignedInteger('id')->index();
            $table->string('title');
            $table->unsignedBigInteger('creator_id');
            $table->string('tag')->unique()->nullable();
            $table->string('photo_url')->nullable();
            $table->boolean('verified')->default(false);
            $table->integer('members_count')->default(0);
            $table->timestamps();

            $table->foreign('creator_id')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('channels');
    }
}
