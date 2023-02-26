<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

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
        $slug_name = strtolower(str_replace(" ","_", $title));

        //Get random reminder
        $collection = [
            'reminder_1_day_before',
            'reminder_3_day_before',
            'reminder_none',
            'reminder_1_hour_before',
            'reminder_3_hour_before'
        ];
        $i = rand(0, 4);
        $reminder = $collection[$i];
        
        return [
            'slug_name' => $slug_name, 
            'content_title' => $title, 
            'content_desc' => fake()->paragraph(), 
            'content_date_start' => fake()->dateTimeBetween('now', '+2 days'), 
            'content_date_end' => fake()->dateTimeBetween('+2 days', '+1 weeks'), 
            'content_reminder' => $reminder, 
            'content_image' => null, 
            'is_draft' => 0, 
            'created_at' => now(), 
            'created_by' => 'dc4d52ec-afb1-11ed-afa1-0242ac120002', 
            'updated_at' => null, 
            'updated_by' => null,
            'deleted_at' => null, 
            'deleted_by' => null,
        ];
    }
}
