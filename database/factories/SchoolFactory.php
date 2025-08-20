<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\School>
 */
class SchoolFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->company();

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            // 'description' => $this->faker->paragraph(),
            // 'logo' => null,
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'status' => $this->faker->randomElement(['pending', 'validated', 'rejected']),
        ];
    }
}
