<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            'name' => "Registered User",
            'slug' => "registered-user"
        ]);

        DB::table('roles')->insert([
            'name' => "Moderator",
            'slug' => "moderator"
        ]);

        DB::table('roles')->insert([
            'name' => "Admin",
            'slug' => "admin"
        ]);
    }
}
