<?php

namespace Database\Seeders;

use App\Models\ItemCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $categories = [
            ['name' => 'Electronics', 'description' => 'Electronic devices and accessories'],
            ['name' => 'Furniture', 'description' => 'Tables, chairs, and other furniture'],
            ['name' => 'Office Supplies', 'description' => 'Stationery and office tools'],
            ['name' => 'Groceries', 'description' => 'Food items and daily needs'],
        ];

        foreach ($categories as $category) {
            ItemCategory::create($category);
        }
    }
}
