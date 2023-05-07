<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Helpers\Generator;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tag>
 */
class TagFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $title = fake()->sentence();
        $slug_name = Generator::getSlugName($title, "tag");
        $ran = mt_rand(0, 1);
        $ran2 = mt_rand(0, 1);

        return [
            'id' => Generator::getUUID(), 
            'slug_name' => $slug_name, 
            'tag_name' => $title, 
            'tag_desc' => fake()->paragraph(), 
            'tag_category' => Generator::getRandomDictionaryType("TAG-001"), 
            'created_at' => Generator::getRandomDate(0), 
            'created_by' => Generator::getRandomAdmin(0), 
            'updated_at' => Generator::getRandomDate($ran), 
            'updated_by' => Generator::getRandomAdmin($ran), 
            'deleted_at' => Generator::getRandomDate($ran2), 
            'deleted_by' => Generator::getRandomAdmin($ran2), 
        ];
    }
}
