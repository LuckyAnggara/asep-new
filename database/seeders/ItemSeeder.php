<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\ItemCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $electronics = ItemCategory::where('name', 'Electronics')->first();
        $furniture = ItemCategory::where('name', 'Furniture')->first();

        Item::create([
            'sku' => 'ELEC-001',
            'name' => 'Laptop',
            'barcode' => '123456789012',
            'item_category_id' => $electronics->id,
            'image' => 'laptop.jpg',
            'variations' => json_encode(['color' => 'Black', 'size' => '15-inch']),
            'cogs' => 500.00,
            'price' => 700.00,
            'min_stock' => 5,
            'weight' => 2.5,
            'unit' => 'pcs',
            'description' => 'A high-performance laptop',
        ]);

        Item::create([
            'sku' => 'FURN-001',
            'name' => 'Office Chair',
            'barcode' => '987654321098',
            'item_category_id' => $furniture->id,
            'image' => 'chair.jpg',
            'variations' => json_encode(['color' => 'Blue', 'material' => 'Leather']),
            'cogs' => 100.00,
            'price' => 150.00,
            'min_stock' => 10,
            'weight' => 10.0,
            'unit' => 'pcs',
            'description' => 'Ergonomic office chair',
        ]);
    }
}
