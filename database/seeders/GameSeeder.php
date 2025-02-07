<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\User;
use App\Models\Developer;
use App\Models\Publisher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class GameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Game::create([
            'name' => 'The Legend of Zelda: Breath of the Wild',
            'released_at' => '2017-03-03',
            'developer_id' => Developer::inRandomOrder()->first()->id,
            'publisher_id' => Publisher::inRandomOrder()->first()->id,
            'user_id' => User::where('username', 'cairhiin')->first()->id,
        ]);

        Game::create([
            'name' => 'Super Mario Odyssey',
            'released_at' => '2017-10-27',
            'developer_id' => Developer::inRandomOrder()->first()->id,
            'publisher_id' => Publisher::inRandomOrder()->first()->id,
            'user_id' => User::where('username', 'cairhiin')->first()->id,
        ]);

        Game::create([
            'name' => 'The Witcher 3: Wild Hunt',
            'released_at' => '2015-05-18',
            'developer_id' => Developer::inRandomOrder()->first()->id,
            'publisher_id' => Publisher::inRandomOrder()->first()->id,
            'user_id' => User::where('username', 'cairhiin')->first()->id,
        ]);

        Game::create([
            'name' => 'Grand Theft Auto V',
            'released_at' => '2013-09-17',
            'developer_id' => Developer::inRandomOrder()->first()->id,
            'publisher_id' => Publisher::inRandomOrder()->first()->id,
            'user_id' => User::where('username', 'cairhiin')->first()->id,
        ]);

        Game::create([
            'name' => 'Red Dead Redemption 2',
            'released_at' => '2018-10-26',
            'developer_id' => Developer::inRandomOrder()->first()->id,
            'publisher_id' => Publisher::inRandomOrder()->first()->id,
            'user_id' => User::where('username', 'cairhiin')->first()->id,
        ]);

        Game::create([
            'name' => 'The Elder Scrolls V: Skyrim',
            'released_at' => '2011-11-11',
            'developer_id' => Developer::inRandomOrder()->first()->id,
            'publisher_id' => Publisher::inRandomOrder()->first()->id,
            'user_id' => User::where('username', 'cairhiin')->first()->id,
        ]);

        Game::create([
            'name' => 'The Legend of Zelda: Ocarina of Time',
            'released_at' => '1998-11-21',
            'developer_id' => Developer::inRandomOrder()->first()->id,
            'publisher_id' => Publisher::inRandomOrder()->first()->id,
            'user_id' => User::where('username', 'cairhiin')->first()->id,
        ]);

        Game::create([
            'name' => 'The Legend of Zelda: A Link to the Past',
            'released_at' => '1991-11-21',
            'developer_id' => Developer::inRandomOrder()->first()->id,
            'publisher_id' => Publisher::inRandomOrder()->first()->id,
            'user_id' => User::where('username', 'cairhiin')->first()->id,
        ]);

        Game::create([
            'name' => 'Dark Souls',
            'released_at' => '2011-11-11',
            'developer_id' => Developer::inRandomOrder()->first()->id,
            'publisher_id' => Publisher::inRandomOrder()->first()->id,
            'user_id' => User::where('username', 'cairhiin')->first()->id,
        ]);

        Game::create([
            'name' => 'Bloodborne',
            'released_at' => '2015-05-18',
            'developer_id' => Developer::inRandomOrder()->first()->id,
            'publisher_id' => Publisher::inRandomOrder()->first()->id,
            'user_id' => User::where('username', 'cairhiin')->first()->id,
        ]);

        Game::create([
            'name' => 'Dark Souls II',
            'released_at' => '2014-11-24',
            'developer_id' => Developer::inRandomOrder()->first()->id,
            'publisher_id' => Publisher::inRandomOrder()->first()->id,
            'user_id' => User::where('username', 'cairhiin')->first()->id,
        ]);

        Game::create([
            'name' => 'Dark Souls III',
            'released_at' => '2016-04-12',
            'developer_id' => Developer::inRandomOrder()->first()->id,
            'publisher_id' => Publisher::inRandomOrder()->first()->id,
            'user_id' => User::where('username', 'cairhiin')->first()->id,
        ]);

        Game::create([
            'name' => 'Sekiro: Shadows Die Twice',
            'released_at' => '2019-02-19',
            'developer_id' => Developer::inRandomOrder()->first()->id,
            'publisher_id' => Publisher::inRandomOrder()->first()->id,
            'user_id' => User::where('username', 'cairhiin')->first()->id,
        ]);

        Game::create([
            'name' => 'The Last of Us',
            'released_at' => '2013-06-14',
            'developer_id' => Developer::inRandomOrder()->first()->id,
            'publisher_id' => Publisher::inRandomOrder()->first()->id,
            'user_id' => User::where('username', 'cairhiin')->first()->id,
        ]);

        Game::create([
            'name' => 'The Last of Us Part II',
            'released_at' => '2020-06-19',
            'developer_id' => Developer::inRandomOrder()->first()->id,
            'publisher_id' => Publisher::inRandomOrder()->first()->id,
            'user_id' => User::where('username', 'cairhiin')->first()->id,
        ]);

        Game::create([
            'name' => 'Uncharted 4: A Thief\'s End',
            'released_at' => '2016-05-10',
            'developer_id' => Developer::inRandomOrder()->first()->id,
            'publisher_id' => Publisher::inRandomOrder()->first()->id,
            'user_id' => User::where('username', 'cairhiin')->first()->id,
        ]);

        Game::create([
            'name' => 'Uncharted: The Lost Legacy',
            'released_at' => '2021-12-08',
            'developer_id' => Developer::inRandomOrder()->first()->id,
            'publisher_id' => Publisher::inRandomOrder()->first()->id,
            'user_id' => User::where('username', 'cairhiin')->first()->id,
        ]);

        Game::create([
            'name' => 'Diablo II',
            'released_at' => '2000-06-29',
            'developer_id' => Developer::inRandomOrder()->first()->id,
            'publisher_id' => Publisher::inRandomOrder()->first()->id,
            'user_id' => User::where('username', 'cairhiin')->first()->id,
        ]);

        Game::create([
            'name' => 'Diablo III',
            'released_at' => '2012-11-15',
            'developer_id' => Developer::inRandomOrder()->first()->id,
            'publisher_id' => Publisher::inRandomOrder()->first()->id,
            'user_id' => User::where('username', 'cairhiin')->first()->id,
        ]);

        Game::create([
            'name' => 'World of Warcraft',
            'released_at' => '2004-11-23',
            'developer_id' => Developer::inRandomOrder()->first()->id,
            'publisher_id' => Publisher::inRandomOrder()->first()->id,
            'user_id' => User::where('username', 'cairhiin')->first()->id,
        ]);

        Game::create([
            'name' => 'World of Warcraft: The Burning Crusade',
            'released_at' => '2007-01-16',
            'developer_id' => Developer::inRandomOrder()->first()->id,
            'publisher_id' => Publisher::inRandomOrder()->first()->id,
            'user_id' => User::where('username', 'cairhiin')->first()->id,
        ]);

        Game::create([
            'name' => 'World of Warcraft: Wrath of the Lich King',
            'released_at' => '2008-11-13',
            'developer_id' => Developer::inRandomOrder()->first()->id,
            'publisher_id' => Publisher::inRandomOrder()->first()->id,
            'user_id' => User::where('username', 'cairhiin')->first()->id,
        ]);

        Game::create([
            'name' => 'World of Warcraft: Cataclysm',
            'released_at' => '2014-11-19',
            'developer_id' => Developer::inRandomOrder()->first()->id,
            'publisher_id' => Publisher::inRandomOrder()->first()->id,
            'user_id' => User::where('username', 'cairhiin')->first()->id,
        ]);

        Game::create([
            'name' => 'World of Warcraft: Mists of Pandaria',
            'released_at' => '2015-10-19',
            'developer_id' => Developer::inRandomOrder()->first()->id,
            'publisher_id' => Publisher::inRandomOrder()->first()->id,
            'user_id' => User::where('username', 'cairhiin')->first()->id,
        ]);
    }
}
