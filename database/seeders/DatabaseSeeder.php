<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\Formation;
use App\Models\School;
use App\Models\SchoolTestimonial;
use App\Models\Testimonial;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'arsene',
            'email' => 'arsene@gmail.com',
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        User::factory()->create([
            'name' => 'arsene',
            'email' => 'arsene1@gmail.com',
            'role' => 'pending_owner',
            'email_verified_at' => now(),
        ]);

        // User::factory(50)->create();

        $this->call(CategorySeeder::class);

        // User::factory()->count(5)->create()->each(function ($user) {
        //     School::factory()->create([
        //         'user_id' => $user->id,
        //         'category_id' => rand(1, 4),
        //     ]);
        // });

        Testimonial::factory()->count(10)->create();
        Contact::factory()->count(10)->create();

        // Formation::factory()->count(20)->create();
        // SchoolTestimonial::factory()->count(10)->create();
    }
}
