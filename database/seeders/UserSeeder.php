<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'id' => Str::uuid(),
            'name' => env('ADMIN_NAME'),
            'username' => env('ADMIN_USER_NAME'),
            'email' => env('ADMIN_USER_EMAIL'),
            'password' => bcrypt(env('ADMIN_USER_PASSWORD')),
            'role_id' => 3
        ]);

        User::factory()
            ->count(50)
            ->create();
    }
}
