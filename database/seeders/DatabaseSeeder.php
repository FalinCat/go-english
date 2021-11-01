<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\Language;
use App\Models\Word;
use Database\Factories\WordFactory;
use Illuminate\Database\Seeder;
use Nette\Utils\Random;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     * @throws \Exception
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
//        $this->call(EnglishWordSeeder::class);

        \Eloquent::unguard();
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        \DB::table('words')->truncate();
        \DB::table('language_word')->truncate();
        \DB::table('languages')->truncate();
        \DB::table('images')->truncate();

       \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $faker = \Faker\Factory::create();

        \DB::table('languages')->insert([
            'name' => 'русский',
            'created_at' => $faker->dateTimeBetween('-2 years', '-1 years'),
            'updated_at' => $faker->dateTimeBetween('-1 years', 'now'),
        ]);

        \DB::table('languages')->insert([
            'name' => 'клингонский',
            'created_at' => $faker->dateTimeBetween('-2 years', '-1 years'),
            'updated_at' => $faker->dateTimeBetween('-1 years', 'now'),
        ]);

        $images = Image::factory(10)->create();
        $images_id = $images->pluck('id');

        $languages = Language::factory(5)->create();
        $languages_id = $languages->pluck('id');

        $words = Word::factory(10)->create();
        $words->each(function ($word) use ($languages_id, $images_id) {
            $word->languages()->attach($languages_id->random(3), [
                'translation' => Word::factory()->make()->pluck('translation')->random(),
                'transcription' => Word::factory()->make()->pluck('translation')->random(),
                'image_id' => $images_id->random(1)->first(),
            ]);

        });

//
//        $words = Word::factory(100)->create();
//        $languages = Language::factory(5)->create();
//        $images = Image::factory(100)->create();
//
//
//
//        $images_id = $images->pluck('id');
//        $languages_id = $languages->pluck('id');
//
//        $words->each(function ($word) use ($languages_id, $images_id) {
//            $word->languages()->attach(intval($languages_id->random(3)), [
//                'translation' => Word::factory()->make()->pluck('translation')->first(),
//                'transcription' => Word::factory()->make()->pluck('translation')->first(),
//                'image_id' => $images_id->random(1),
//            ]);
//
//        });


    }
}
