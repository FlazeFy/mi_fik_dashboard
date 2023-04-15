<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Helpers\Generator;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Feedback>
 */
class FeedbackFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id' => Generator::getUUID(), 
            'feedback_body' => fake()->paragraph(), 
            'feedback_rate' => mt_rand(1, 5), 
            'feedback_suggest' => Generator::getRandomFeedbackSuggest(), 
            'created_at' => Generator::getRandomDate(0), 
            'deleted_at' => Generator::getRandomDate(mt_rand(0,1)), 
        ];
    }
}
