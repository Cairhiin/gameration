<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PublisherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('publishers')->insert([
            'name' => "Activision",
            'country' => "USA",
            'year' => "1979",
            'city' =>  "Santa Monica",
            'user_id' => User::where('username', env('ADMIN_USER_NAME'))->first()->id
        ]);

        DB::table('publishers')->insert([
            'name' => "Activision Blizzard",
            'country' => "USA",
            'year' => "2008",
            'city' =>  "Santa Monica",
            'user_id' => User::where('username', env('ADMIN_USER_NAME'))->first()->id
        ]);

        DB::table('publishers')->insert([
            'name' => "Blizzard Entertainment",
            'country' => "USA",
            'year' => "1991",
            'city' =>  "Irvine",
            'user_id' => User::where('username', env('ADMIN_USER_NAME'))->first()->id
        ]);

        DB::table('publishers')->insert([
            'name' => "Bandai Namco Entertainment",
            'country' => "Japan",
            'year' => "1955",
            'city' =>  "Tokyo",
            'user_id' => User::where('username', env('ADMIN_USER_NAME'))->first()->id
        ]);

        DB::table('publishers')->insert([
            'name' => "Capcom",
            'country' => "Japan",
            'year' => "1979",
            'city' =>  "Osaka",
            'user_id' => User::where('username', env('ADMIN_USER_NAME'))->first()->id
        ]);

        DB::table('publishers')->insert([
            'name' => "CD Projekt Red",
            'country' => "Poland",
            'year' => "1994",
            'city' =>  "Warsaw",
            'user_id' => User::where('username', env('ADMIN_USER_NAME'))->first()->id
        ]);

        DB::table('publishers')->insert([
            'name' => "Deep Silver",
            'country' => "Germany",
            'year' => "2002",
            'city' =>  "Munich",
            'user_id' => User::where('username', env('ADMIN_USER_NAME'))->first()->id
        ]);

        DB::table('publishers')->insert([
            'name' => "Electronic Arts",
            'country' => "USA",
            'year' => "1982",
            'city' =>  "Redwood City",
            'user_id' => User::where('username', env('ADMIN_USER_NAME'))->first()->id
        ]);

        DB::table('publishers')->insert([
            'name' => "FromSoftware",
            'country' => "Japan",
            'year' => "1986",
            'city' =>  "Tokyo",
            'user_id' => User::where('username', env('ADMIN_USER_NAME'))->first()->id
        ]);

        DB::table('publishers')->insert([
            'name' => "id Software",
            'country' => "USA",
            'year' => "1991",
            'city' =>  "Richardson",
            'user_id' => User::where('username', env('ADMIN_USER_NAME'))->first()->id
        ]);

        DB::table('publishers')->insert([
            'name' => "Interplay Entertainment",
            'country' => "USA",
            'year' => "1983",
            'city' =>  "Beverly Hills",
            'user_id' => User::where('username', env('ADMIN_USER_NAME'))->first()->id
        ]);

        DB::table('publishers')->insert([
            'name' => "Konami",
            'country' => "Japan",
            'year' => "1969",
            'city' =>  "Tokyo",
            'user_id' => User::where('username', env('ADMIN_USER_NAME'))->first()->id
        ]);

        DB::table('publishers')->insert([
            'name' => "LucasArts",
            'country' => "USA",
            'year' => "1982",
            'city' =>  "San Francisco",
            'user_id' => User::where('username', env('ADMIN_USER_NAME'))->first()->id
        ]);

        DB::table('publishers')->insert([
            'name' => "Microsoft",
            'country' => "USA",
            'year' => "1975",
            'city' =>  "Redmond",
            'user_id' => User::where('username', env('ADMIN_USER_NAME'))->first()->id
        ]);

        DB::table('publishers')->insert([
            'name' => "NCSoft",
            'country' => "Korea",
            'year' => "1987",
            'city' =>  "Seoul",
            'user_id' => User::where('username', env('ADMIN_USER_NAME'))->first()->id
        ]);

        DB::table('publishers')->insert([
            'name' => "Nintendo",
            'country' => "Japan",
            'year' => "1889",
            'city' =>  "Kyoto",
            'user_id' => User::where('username', env('ADMIN_USER_NAME'))->first()->id
        ]);

        DB::table('publishers')->insert([
            'name' => "THQ",
            'country' => "USA",
            'year' => "1989",
            'city' =>  "Agoura Hills",
            'user_id' => User::where('username', env('ADMIN_USER_NAME'))->first()->id
        ]);

        DB::table('publishers')->insert([
            'name' => "THQ nordic",
            'country' => "Austria",
            'year' => "2011",
            'city' =>  "Santa Monica",
            'user_id' => User::where('username', env('ADMIN_USER_NAME'))->first()->id
        ]);

        DB::table('publishers')->insert([
            'name' => "Ubisoft",
            'country' => "France",
            'year' => "1986",
            'city' =>  "Montreuil-sous-Bois",
            'user_id' => User::where('username', env('ADMIN_USER_NAME'))->first()->id
        ]);

        DB::table('publishers')->insert([
            'name' => "Valve",
            'country' => "USA",
            'year' => "1996",
            'city' =>  "Bellevue",
            'user_id' => User::where('username', env('ADMIN_USER_NAME'))->first()->id
        ]);
    }
}
