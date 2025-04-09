<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class FriendSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::where('username', config('app.admin_user_username'))->first()->friendsOfMine()->attach(User::take(5)->get()->pluck('id'), ['accepted' => true]);
    }
}
