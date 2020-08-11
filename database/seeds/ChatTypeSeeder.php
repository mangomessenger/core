<?php

use Illuminate\Database\Seeder;

class ChatTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\ChatType::create([
            'name' => 'direct-chats'
        ]);

        \App\Models\ChatType::create([
            'name' => 'channel'
        ]);

        \App\Models\ChatType::create([
            'name' => 'group'
        ]);
    }
}
