<?php

namespace Database\Factories;

use App\Models\Word;
use Illuminate\Database\Eloquent\Factories\Factory;

class WordFactory extends Factory
{

    protected $model = Word::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'translation' => $this->faker->word(),
            'transcription' => $this->faker->word()
        ];
    }
}
