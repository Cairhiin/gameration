<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Enums\RoleName;
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
        User::create([
            'id' => Str::uuid(),
            'name' => config('app.admin_user_name'),
            'username' => config('app.admin_user_username'),
            'email' => config('app.admin_user_email'),
            'password' => bcrypt(config('app.admin_user_password')),
            'email_verified_at' => now(),
        ])->roles()->sync(Role::where('name', RoleName::ADMIN->value)->first());

        User::factory()
            ->count(50)
            ->create()
            ->each(function ($user) {
                $user->roles()->sync(Role::where('name', RoleName::USER->value)->first());
            });
    }
}
