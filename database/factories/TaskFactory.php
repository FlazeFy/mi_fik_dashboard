<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Helpers\Generator;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
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
        $slug_name = Generator::getSlugName($title, "task");
        $ran = mt_rand(0, 1);
        $ran2 = mt_rand(0, 1);

        return [
            'id' => Generator::getUUID(), 
            'slug_name' => $slug_name, 
            'task_title' => $title, 
            'task_desc' => fake()->paragraph(), 
            'task_date_start' => fake()->dateTimeBetween('now', '+2 days'), 
            'task_date_end' => fake()->dateTimeBetween('+2 days', '+1 weeks'), 
            'task_reminder' => $reminder, 
            'created_at' => Generator::getRandomDate(0), 
            'created_by' => Generator::getRandomUser(0), 
            'updated_at' => Generator::getRandomDate($ran), 
            'updated_by' => Generator::getRandomUser($ran), 
            'deleted_at' => Generator::getRandomDate($ran2), 
            'deleted_by' => Generator::getRandomAdmin($ran2), 
        ];
    }
}
