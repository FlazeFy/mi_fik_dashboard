<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Helpers\Generator;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $ran = mt_rand(0, 1);
        $ran2 = mt_rand(0, 1);

        if($ran == 0){
            $answer = fake()->paragraph();
        } else {
            $answer = null;
        }

        return [
            'id' => Generator::getUUID(), 
            'question_type' => Generator::getRandomDictionaryType("QST-001"), 
            'question_body' => fake()->paragraph(), 
            'question_answer' => $answer, 
            'created_at' => Generator::getRandomDate(0), 
            'created_by' => Generator::getRandomAdmin(0), 
            'updated_at' => Generator::getRandomDate($ran), 
            'updated_by' => Generator::getRandomAdmin($ran), 
            'deleted_at' => Generator::getRandomDate($ran2), 
            'deleted_by' => Generator::getRandomAdmin($ran2), 
        ];
    }
}
