<?php

namespace Database\Factories;
use App\Helpers\Generator;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ArchiveRelation>
 */
class ArchiveRelationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // Check this again, must be created based on users role, content tag, and archive
        $ran = mt_rand(1, 2);
        $res = Generator::getRandomUserArchive($ran);

        return [
            'id' => Generator::getUUID(), 
            'archive_id' => Generator::getRandomArchive($res[0]['id']), 
            'content_id' => Generator::getRandomContent($ran), 
            'created_at' => Generator::getRandomDate(0), 
            'created_by' => $res[0]['user_id']
        ];
    }
}
