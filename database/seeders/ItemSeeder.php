<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\ItemCategory;
use App\Models\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $items = [
            [
                'name' => 'Laptop Dell Inspiron',
                'sku' => 'LAP-DEL-001',
                'item_category_id' => ItemCategory::where('name', 'Electronics')->first()->id,
                'unit_id' => Unit::where('name', 'Piece')->first()->id,
                'image' => 'images/laptop_dell.jpg',
                'cost_price' => 1200.00,
                'selling_price' => 1500.00,
                'minimum_stock' => 5
            ],
            [
                'name' => 'Office Chair',
                'sku' => 'FUR-OCH-002',
                'item_category_id' => ItemCategory::where('name', 'Furniture')->first()->id,
                'unit_id' => Unit::where('name', 'Piece')->first()->id,
                'image' => 'images/office_chair.jpg',
                'cost_price' => 150.00,
                'selling_price' => 200.00,
                'minimum_stock' => 10
            ],
            [
                'name' => 'Printer Ink Cartridge',
                'sku' => 'OFF-INK-003',
                'item_category_id' => ItemCategory::where('name', 'Office Supplies')->first()->id,
                'unit_id' => Unit::where('name', 'Piece')->first()->id,
                'image' => 'images/ink_cartridge.jpg',
                'cost_price' => 25.50,
                'selling_price' => 35.00,
                'minimum_stock' => 20
            ],
            [
                'name' => 'Rice 5kg',
                'sku' => 'GRO-RICE-004',
                'item_category_id' => ItemCategory::where('name', 'Groceries')->first()->id,
                'unit_id' => Unit::where('name', 'Kilogram')->first()->id,
                'image' => 'images/rice_5kg.jpg',
                'cost_price' => 7.50,
                'selling_price' => 10.00,
                'minimum_stock' => 50
            ]
        ];

        foreach ($items as $item) {
            Item::create($item);
        }
    }
}
