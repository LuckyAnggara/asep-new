<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JournalEntry>
 */
class JournalEntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'date' => Carbon::now()->subDays(rand(1, 30)), // Random tanggal 1-30 hari lalu
            'description' => $this->faker->sentence(4), // Random deskripsi
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
