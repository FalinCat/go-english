<?php

namespace Database\Seeders;

use App\Models\Word;
use Illuminate\Database\Seeder;

class WordTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Word::query()->truncate();

        $fakerRu = \Faker\Factory::create('ru_RU');
        $faker = \Faker\Factory::create();
        for ($i = 0; $i < 100; $i++){
            Word::query()->create([
                'russian_translation' => $fakerRu->word,
                'english_translation' => $faker->word,
                'transcription' => $faker->word
            ]);
        }
    }
}
