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
            'description' => 'The Legend of Zelda: Breath of the Wild is an action-adventure game developed and published by Nintendo. An entry in the longrunning The Legend of Zelda series, it was released for the Nintendo Switch and Wii U consoles on March 3, 2017. The story follows Link, who awakens from a hundred-year slumber to defeat Calamity Ganon before it can destroy the kingdom of Hyrule.',
        ]);

        Game::create([
            'name' => 'Super Mario Odyssey',
            'released_at' => '2017-10-27',
            'developer_id' => Developer::inRandomOrder()->first()->id,
            'publisher_id' => Publisher::inRandomOrder()->first()->id,
            'user_id' => User::where('username', 'cairhiin')->first()->id,
            'description' => 'Super Mario Odyssey is a platform game developed and published by Nintendo for the Nintendo Switch. An entry in the Super Mario series, it follows Mario and Cappy, a spirit that turns into Mario\'s hat and allows him to possess other characters and objects, as they journey across various worlds to save Princess Peach from his nemesis Bowser, who plans to  marry her.',
        ]);

        Game::create([
            'name' => 'The Witcher 3: Wild Hunt',
            'released_at' => '2015-05-18',
            'developer_id' => Developer::inRandomOrder()->first()->id,
            'publisher_id' => Publisher::inRandomOrder()->first()->id,
            'user_id' => User::where('username', 'cairhiin')->first()->id,
            'description' => 'The Witcher 3: Wild Hunt is an action role-playing game developed and published by CD Projekt. An entry in the Witcher series, it was released for Microsoft Windows, PlayStation 4, and Xbox One in May 2015, and for the Nintendo Switch in October 2019. The story follows Geralt of Rivia, a monster hunter searching for his missing adopted daughter, Ciri, while the Wild Hunt, a group of otherworldly warriors, seeks to capture her'
        ]);

        Game::create([
            'name' => 'Grand Theft Auto V',
            'released_at' => '2013-09-17',
            'developer_id' => Developer::inRandomOrder()->first()->id,
            'publisher_id' => Publisher::inRandomOrder()->first()->id,
            'user_id' => User::where('username', 'cairhiin')->first()->id,
            'description' => 'Grand Theft Auto V is an action-adventure game developed by Rockstar North and published by Rockstar Games. It was released in September 2013 for PlayStation 3 and Xbox 360, in November 2014 for PlayStation 4 and Xbox One, and in April 2015 for Microsoft Windows. The story follows three criminals who plan a series of heists to secure their own fortune while under pressure from a government agency and powerful crime figures.',
        ]);

        Game::create([
            'name' => 'Red Dead Redemption 2',
            'released_at' => '2018-10-26',
            'developer_id' => Developer::inRandomOrder()->first()->id,
            'publisher_id' => Publisher::inRandomOrder()->first()->id,
            'user_id' => User::where('username', 'cairhiin')->first()->id,
            'description' => 'Red Dead Redemption 2 is an action-adventure game developed and published by Rockstar Games. It was released for PlayStation 4 and Xbox One in October 2018, and for Microsoft Windows and Stadia in November 2019. The story follows Arthur Morgan, a member of the Van der Linde gang in 1899, in the waning years of the American Old West, and the extinction of the age of outlaws and gunslingers.',
        ]);

        Game::create([
            'name' => 'The Elder Scrolls V: Skyrim',
            'released_at' => '2011-11-11',
            'developer_id' => Developer::inRandomOrder()->first()->id,
            'publisher_id' => Publisher::inRandomOrder()->first()->id,
            'user_id' => User::where('username', 'cairhiin')->first()->id,
            'description' => 'The Elder Scrolls V: Skyrim is an action role-playing game developed by Bethesda Game Studios and published by Bethesda Softworks. It was released for Microsoft Windows, PlayStation 3, and Xbox 360 in November 2011, and for PlayStation 4, Xbox One, and Nintendo Switch in November 2017. The story follows the Dragonborn, a prophesied hero who must defeat Alduin, a dragon who is prophesied to destroy'
        ]);

        Game::create([
            'name' => 'The Legend of Zelda: Ocarina of Time',
            'released_at' => '1998-11-21',
            'developer_id' => Developer::inRandomOrder()->first()->id,
            'publisher_id' => Publisher::inRandomOrder()->first()->id,
            'user_id' => User::where('username', 'cairhiin')->first()->id,
            'description' => 'The Legend of Zelda: Ocarina of Time is an action-adventure game developed and published by Nintendo. It was released for the Nintendo 64 in November 1998. The story follows Link, who travels through time to stop Ganondorf from obtaining the Triforce and ruling the world. In doing so, he travels through time and navigates various dungeons to awaken the Seven Sages and defeat Ganon.',
        ]);

        Game::create([
            'name' => 'The Legend of Zelda: A Link to the Past',
            'released_at' => '1991-11-21',
            'developer_id' => Developer::inRandomOrder()->first()->id,
            'publisher_id' => Publisher::inRandomOrder()->first()->id,
            'user_id' => User::where('username', 'cairhiin')->first()->id,
            'description' => 'The Legend of Zelda: A Link to the Past is an action-adventure game developed and published by Nintendo. It was released for the Super Nintendo Entertainment System in November 1991. The story follows Link, who must rescue Princess Zelda from the evil wizard Agahnim by traveling between two worlds, the Light World and the Dark World, and defeating various enemies and bosses.',
        ]);

        Game::create([
            'name' => 'Dark Souls',
            'released_at' => '2011-11-11',
            'developer_id' => Developer::inRandomOrder()->first()->id,
            'publisher_id' => Publisher::inRandomOrder()->first()->id,
            'user_id' => User::where('username', 'cairhiin')->first()->id,
            'description' => 'Dark Souls is an action role-playing game developed by FromSoftware and published by Namco Bandai Games. It was released for PlayStation 3 and Xbox 360 in September 2011, and for Microsoft Windows in August 2012. The story follows an undead warrior who must defeat various enemies and bosses to escape the undead asylum and ring the Bells of Awakening to open the way to the Lordran kingdom.',
        ]);

        Game::create([
            'name' => 'Bloodborne',
            'released_at' => '2015-05-18',
            'developer_id' => Developer::inRandomOrder()->first()->id,
            'publisher_id' => Publisher::inRandomOrder()->first()->id,
            'user_id' => User::where('username', 'cairhiin')->first()->id,
            'description' => 'Bloodborne is an action role-playing game developed by FromSoftware and published by Sony Computer Entertainment. It was released for PlayStation 4 in March 2015. The story follows a hunter who must navigate the city of Yharnam and defeat various enemies and bosses to uncover the source of a plague that has turned the city\'s inhabitants into monsters.',
        ]);

        Game::create([
            'name' => 'Dark Souls II',
            'released_at' => '2014-11-24',
            'developer_id' => Developer::inRandomOrder()->first()->id,
            'publisher_id' => Publisher::inRandomOrder()->first()->id,
            'user_id' => User::where('username', 'cairhiin')->first()->id,
            'description' => 'Dark Souls II is an action role-playing game developed by FromSoftware and published by Namco Bandai Games. It was released for PlayStation 3 and Xbox 360 in March 2014, and for Microsoft Windows in April 2014. The story follows an undead warrior who must defeat various enemies and bosses to escape the undead asylum and ring the Bells of Awakening to open the way to the Drangleic kingdom.',
        ]);

        Game::create([
            'name' => 'Dark Souls III',
            'released_at' => '2016-04-12',
            'developer_id' => Developer::inRandomOrder()->first()->id,
            'publisher_id' => Publisher::inRandomOrder()->first()->id,
            'user_id' => User::where('username', 'cairhiin')->first()->id,
            'description' => 'Dark Souls III is an action role-playing game developed by FromSoftware and published by Namco Bandai Games. It was released for PlayStation 4, Xbox One, and Microsoft Windows in April 2016. The story follows an undead warrior who must defeat various enemies and bosses to escape the undead asylum and ring the Bells of Awakening to open the way to the Lothric kingdom.',
        ]);

        Game::create([
            'name' => 'Sekiro: Shadows Die Twice',
            'released_at' => '2019-02-19',
            'developer_id' => Developer::inRandomOrder()->first()->id,
            'publisher_id' => Publisher::inRandomOrder()->first()->id,
            'user_id' => User::where('username', 'cairhiin')->first()->id,
            'description' => 'Sekiro: Shadows Die Twice is an action-adventure game developed by FromSoftware and published by Activision. It was released for PlayStation 4, Xbox One, and Microsoft Windows in March 2019. The story follows a shinobi named Wolf as he embarks on a mission to rescue his kidnapped lord and seek revenge on his enemies in a reimagined late 1500s Sengoku period Japan.',
        ]);

        Game::create([
            'name' => 'The Last of Us',
            'released_at' => '2013-06-14',
            'developer_id' => Developer::inRandomOrder()->first()->id,
            'publisher_id' => Publisher::inRandomOrder()->first()->id,
            'user_id' => User::where('username', 'cairhiin')->first()->id,
            'description' => 'The Last of Us is an action-adventure game developed by Naughty Dog and published by Sony Computer Entertainment. It was released for PlayStation 3 in June 2013, and for PlayStation 4 in July 2014. The story follows Joel, a smuggler, and Ellie   , a survivor, as they travel across the United States in search of a missing child.',
        ]);

        Game::create([
            'name' => 'The Last of Us Part II',
            'released_at' => '2020-06-19',
            'developer_id' => Developer::inRandomOrder()->first()->id,
            'publisher_id' => Publisher::inRandomOrder()->first()->id,
            'user_id' => User::where('username', 'cairhiin')->first()->id,
            'description' => 'The Last of Us Part II is an action-adventure game developed by Naughty Dog and published by Sony Interactive Entertainment. It was released for PlayStation 4 in June 2020. The story follows Ellie, a survivor, as she seeks revenge on a group of hostile survivors in a post-apocalyptic United States.',
        ]);

        Game::create([
            'name' => 'Uncharted 4: A Thief\'s End',
            'released_at' => '2016-05-10',
            'developer_id' => Developer::inRandomOrder()->first()->id,
            'publisher_id' => Publisher::inRandomOrder()->first()->id,
            'user_id' => User::where('username', 'cairhiin')->first()->id,
            'description' => 'Uncharted 4: A Thief\'s End is an action-adventure game developed by Naughty Dog and published by Sony Computer Entertainment. It was released for PlayStation 4 in May 2016. The story follows Nathan Drake, a retired treasure hunter, as he embarks on a quest to find the lost pirate colony of Libertalia and recover a fortune in treasure.',
        ]);

        Game::create([
            'name' => 'Uncharted: The Lost Legacy',
            'released_at' => '2021-12-08',
            'developer_id' => Developer::inRandomOrder()->first()->id,
            'publisher_id' => Publisher::inRandomOrder()->first()->id,
            'user_id' => User::where('username', 'cairhiin')->first()->id,
            'description' => 'Uncharted: The Lost Legacy is an action-adventure game developed by Naughty Dog and published by Sony Interactive Entertainment. It was released for PlayStation 4 in August 2017. The story follows Chloe Frazer, a treasure hunter, and Nadine Ross, a mercenary, as they search for the Tusk of Ganesh in the Western Ghats of India.',
        ]);

        Game::create([
            'name' => 'Diablo II',
            'released_at' => '2000-06-29',
            'developer_id' => Developer::inRandomOrder()->first()->id,
            'publisher_id' => Publisher::inRandomOrder()->first()->id,
            'user_id' => User::where('username', 'cairhiin')->first()->id,
            'description' => 'Diablo II is an action role-playing game developed by Blizzard North and published by Blizzard Entertainment. It was released for Microsoft Windows and Mac OS in June 2000. The story follows a group of adventurers who must defeat the demon Diablo and his minions to save the world of Sanctuary.',
        ]);

        Game::create([
            'name' => 'Diablo III',
            'released_at' => '2012-11-15',
            'developer_id' => Developer::inRandomOrder()->first()->id,
            'publisher_id' => Publisher::inRandomOrder()->first()->id,
            'user_id' => User::where('username', 'cairhiin')->first()->id,
            'description' => 'Diablo III is an action role-playing game developed and published by Blizzard Entertainment. It was released for Microsoft Windows and Mac OS in May 2012, and for PlayStation 3 and Xbox 360 in September 2013. The story follows a group of adventurers who must defeat the demon Diablo and his minions to save the world of Sanctuary.',
        ]);

        Game::create([
            'name' => 'World of Warcraft',
            'released_at' => '2004-11-23',
            'developer_id' => Developer::inRandomOrder()->first()->id,
            'publisher_id' => Publisher::inRandomOrder()->first()->id,
            'user_id' => User::where('username', 'cairhiin')->first()->id,
            'description' => 'World of Warcraft is a massively multiplayer online role-playing game developed and published by Blizzard Entertainment. It was released for Microsoft Windows and Mac OS in November 2004. The story follows the players, who assume the roles of various characters in the world of Azeroth, as they complete quests and defeat enemies to level up and acquire new gear.',
        ]);

        Game::create([
            'name' => 'World of Warcraft: The Burning Crusade',
            'released_at' => '2007-01-16',
            'developer_id' => Developer::inRandomOrder()->first()->id,
            'publisher_id' => Publisher::inRandomOrder()->first()->id,
            'user_id' => User::where('username', 'cairhiin')->first()->id,
            'description' => 'World of Warcraft: The Burning Crusade is an expansion pack for World of Warcraft. It was released for Microsoft Windows and Mac OS in January 2007. The story follows the players, who assume the roles of various characters in the world of Outland, as they complete quests and defeat enemies to level up and acquire new gear.',
        ]);

        Game::create([
            'name' => 'World of Warcraft: Wrath of the Lich King',
            'released_at' => '2008-11-13',
            'developer_id' => Developer::inRandomOrder()->first()->id,
            'publisher_id' => Publisher::inRandomOrder()->first()->id,
            'user_id' => User::where('username', 'cairhiin')->first()->id,
            'description' => 'World of Warcraft: Wrath of the Lich King is an expansion pack for World of Warcraft. It was released for Microsoft Windows and Mac OS in November 2008. The story follows the players, who assume the roles of various characters in the world of Northrend, as they complete quests and defeat enemies to level up and acquire new gear.',
        ]);

        Game::create([
            'name' => 'World of Warcraft: Cataclysm',
            'released_at' => '2014-11-19',
            'developer_id' => Developer::inRandomOrder()->first()->id,
            'publisher_id' => Publisher::inRandomOrder()->first()->id,
            'user_id' => User::where('username', 'cairhiin')->first()->id,
            'description' => 'World of Warcraft: Cataclysm is an expansion pack for World of Warcraft. It was released for Microsoft Windows and Mac OS in December 2010. The story follows the players, who assume the roles of various characters in the world of Azeroth, as they complete quests and defeat enemies to level up and acquire new gear.',
        ]);

        Game::create([
            'name' => 'World of Warcraft: Mists of Pandaria',
            'released_at' => '2015-10-19',
            'developer_id' => Developer::inRandomOrder()->first()->id,
            'publisher_id' => Publisher::inRandomOrder()->first()->id,
            'user_id' => User::where('username', 'cairhiin')->first()->id,
            'description' => 'World of Warcraft: Mists of Pandaria is an expansion pack for World of Warcraft. It was released for Microsoft Windows and Mac OS in September 2012. The story follows the players, who assume the roles of various characters in the world of Pandaria, as they complete quests and defeat enemies to level up and acquire new gear.',
        ]);
    }
}
