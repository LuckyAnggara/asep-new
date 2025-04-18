<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\JournalEntry;
use App\Models\JournalEntryDetail;
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
        // JournalEntry::factory()
        //     ->count(20)
        //     ->has(JournalEntryDetail::factory()->count(rand(2, 4)), 'details')
        //     ->create();
        // $this->call(AccountingSeeder::class);
        $this->call(UnitSeeder::class);
        $this->call(ItemCategorySeeder::class);
        $this->call(ItemSeeder::class);
        // $this->call(MemberSeeder::class);
        // $this->call(JournalEntrySeeder::class);
    }
}
