<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Member;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(1, 50) as $index) {
            Member::create([
                'member_id' => 'MEM-' . now()->format('Ymd') . '-' . str_pad($index, 4, '0', STR_PAD_LEFT),
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->phoneNumber,
                'address' => $faker->address,
                'date_of_birth' => $faker->date('Y-m-d', '2000-01-01'),
                'gender' => $faker->randomElement(['male', 'female', 'other']),
                'job_title' => $faker->jobTitle,
                'department' => $faker->word,
                'join_date' => $faker->date('Y-m-d'),
            ]);
        }
    }
}
