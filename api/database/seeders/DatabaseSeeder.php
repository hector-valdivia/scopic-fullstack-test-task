<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(1)
            ->create([
                'name'      => 'user1',
                'email'     => 'user1@gmail.com',
                'password'  => Hash::make('password')
            ]);

        \App\Models\User::factory(1)
            ->create([
                'name'      => 'user2',
                'email'     => 'user2@gmail.com',
                'password'  => Hash::make('password')
            ]);

        Item::factory()
            ->count(50)
            ->create();
    }
}
