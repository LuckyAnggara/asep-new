<?php

namespace Database\Factories;

use App\Models\ItemCategory;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'sku' => 'SKU-' . Str::upper(Str::random(8)),
            'image' => 'items/default.png', // Simpan default jika tidak ada gambar
            'minimum_stock' => $this->faker->numberBetween(1, 50),
            'cost_price' => $this->faker->randomFloat(2, 10, 1000),
            'selling_price' => $this->faker->randomFloat(2, 20, 2000),
            'category_id' => ItemCategory::inRandomOrder()->first()->id ?? ItemCategory::factory(),
            'unit_id' => Unit::inRandomOrder()->first()->id ?? Unit::factory(),
        ];
    }
}
