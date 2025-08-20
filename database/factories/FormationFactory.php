<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\formation>
 */
class FormationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->company();

        return [
            'school_id' => 6,
            'title' => $this->faker->paragraph(),
            'slug' => Str::slug($title),
            'description' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(['draft', 'published', 'archived']),
        ];
    }
}
