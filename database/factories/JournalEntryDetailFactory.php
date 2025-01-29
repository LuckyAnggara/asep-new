<?php

namespace Database\Factories;

use App\Models\ChartOfAccount;
use App\Models\JournalEntry;
use App\Models\JournalEntryDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JournalEntryDetail>
 */
class JournalEntryDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = JournalEntryDetail::class;

    public function definition()
    {
        // Ambil akun secara acak dari database
        $account = ChartOfAccount::inRandomOrder()->first();
        $amount = $this->faker->numberBetween(100, 5000); // Random jumlah antara 100 - 5000

        return [
            'journal_entry_id' => JournalEntry::factory(), // Buat otomatis jika belum ada
            'account_id' => $account ? $account->id : 1, // Pakai account_id yang valid
            'debit' => $this->faker->boolean ? $amount : 0, // Random debit atau credit
            'credit' => $this->faker->boolean ? 0 : $amount,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
