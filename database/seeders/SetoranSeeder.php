<?php

namespace Database\Seeders;

use App\Models\Setoran;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SetoranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setoran::create([
            'member_id' => 1,
            'amount' => 500000,
            'payment_method' => 'Transfer Bank',
            'payment_date' => now(),
            'attachment' => null
        ]);
    }
}
