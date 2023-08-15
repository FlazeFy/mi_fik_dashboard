<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Helpers\Generator;

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

        $role = Generator::getRandomRole();
        $year = Generator::getRandomYear();

        if($role != null){
            $is_acc = 1;
            $acc_at = Generator::getRandomDate(0);
            $acc_by = Generator::getRandomAdmin(0);
        } else {
            $is_acc = 0;
        }
        
        $ran = mt_rand(0, 1);
        $ran2 = mt_rand(0, 1);
        
        return [
            'id' => Generator::getUUID(), 
            'firebase_fcm_token' => null, 
            'username' => fake()->username(),
            'email' => fake()->unique()->safeEmail(), 
            'password' => fake()->password(), 
            'first_name' => $split[0], 
            'last_name' => $split[1], 
            'role' => $role, 
            'image_url' => null, 
            'batch_year' => $year, 
            'created_at' => Generator::getRandomDate(0), 
            'updated_at' => Generator::getRandomDate($ran), 
            'updated_by' => Generator::getRandomAdmin($ran), 
            'deleted_at' => Generator::getRandomDate($ran2), 
            'deleted_by' => Generator::getRandomAdmin($ran2), 
            'accepted_at' => $acc_at, 
            'accepted_by' => $acc_by, 
            'is_accepted' => $is_acc,
        ];
    }
}
