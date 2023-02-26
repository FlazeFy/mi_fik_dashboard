<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $name = fake()->name();
        $split = explode(" ", $name);

        $slug_name = strtolower($split[0])."_".strtolower($split[1]);
        
        return [
            'slug_name' => $slug_name, 
            'username' => fake()->username(),
            'email' => fake()->unique()->safeEmail(), 
            'password' => fake()->password(), 
            'first_name' => $split[0], 
            'last_name' => $split[1], 
            'role' => null, 
            'image_url' => null, 
            'created_at' => now(), 
            'updated_at' => null, 
            'updated_by' => null, 
            'deleted_at' => null, 
            'deleted_by' => null, 
            'accepted_at' => null, 
            'accepted_by' => null, 
            'is_accepted' => 0,
        ];
    }
}
