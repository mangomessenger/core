<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::create([
            'name' => 'Donald',
            'username' => NULL,
            'phone_number' => '+380123123123',
            'country_code' => 'UA',
        ]);
    }
}
