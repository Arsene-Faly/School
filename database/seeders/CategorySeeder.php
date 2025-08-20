<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Établissement Scolaire',
                'description' => 'École primaire, collège ou lycée offrant un enseignement général.',
            ],
            [
                'name' => 'Établissement Supérieur',
                'description' => 'Université, institut ou école offrant un enseignement supérieur.',
            ],
            [
                'name' => 'Centre de formation',
                'description' => 'Centre spécialisé dans la formation professionnelle.',
            ],
            [
                'name' => 'École Spécialisée',
                'description' => 'Établissement dispensant un enseignement dans un domaine précis (art, informatique, santé, etc.).',
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
