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
        \App\ChatType::create([
            'name' => 'direct'
        ]);

        \App\ChatType::create([
            'name' => 'channel'
        ]);

        \App\ChatType::create([
            'name' => 'group'
        ]);
    }
}
