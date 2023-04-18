<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Helpers\Generator;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ContentHeader>
 */
class ContentHeaderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $title = fake()->sentence();
        $reminder = Generator::getRandomReminder();
        $slug_name = Generator::getSlugName($title, "content");
        $ran = mt_rand(0, 1);
        $ran2 = mt_rand(0, 1);

        return [
            'id' => Generator::getUUID(), 
            'slug_name' => $slug_name, 
            'content_title' => $title, 
            'content_desc' => fake()->paragraph(), 
            'content_date_start' => fake()->dateTimeBetween('now', '+2 days'), 
            'content_date_end' => fake()->dateTimeBetween('+2 days', '+1 weeks'), 
            'content_reminder' => $reminder, 
            'content_image' => null, 
            'is_draft' => 0, 
            'created_at' => Generator::getRandomDate(0), 
            'created_by' => Generator::getRandomAdmin(0), 
            'updated_at' => Generator::getRandomDate($ran), 
            'updated_by' => Generator::getRandomAdmin($ran), 
            'deleted_at' => Generator::getRandomDate($ran2), 
            'deleted_by' => Generator::getRandomAdmin($ran2), 
        ];
    }
}
