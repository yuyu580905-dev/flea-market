<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'id' => 1,
            'name' => 'テストユーザー1',
            'email' => 'user1@example.com',
            'password' => Hash::make('password123'),
        ]);

        User::create([
            'id' => 2,
            'name' => 'テストユーザー2',
            'email' => 'user2@example.com',
            'password' => Hash::make('password123'),
        ]);

        User::create([
            'id' => 3,
            'name' => 'テストユーザー3',
            'email' => 'user3@example.com',
            'password' => Hash::make('password123'),
        ]);
    }
}
