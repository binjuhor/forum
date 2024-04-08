<?php

namespace Database\Seeders;

use App\Models\Topic;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TopicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'slug' => 'general',
                'name' => 'General',
                'description' => 'Any and all things about films and movies.',
            ],
            [
                'slug' => 'reviews',
                'name' => 'Reviews',
                'description' => 'What\'s the census on that latest flick? Read all about it here',
            ],
            [
                'slug' => 'questions',
                'name' => 'Questions',
                'description' => 'Did\'t quite understand that one plot point? There are no stupid questions here.',
            ],
            [
                'slug' => 'conspiracies',
                'name' => 'Conspiracies',
                'description' => 'Got a wild idea about how the Incredibles and Monster\'s Inc. are connected? Share it here.',
            ],
            [
                'slug' => 'news',
                'name' => 'News',
                'description' => 'What\'s happening in the world of movies? Find out here.',
            ]
        ];

        Topic::upsert($data, ['slug']);
    }
}
