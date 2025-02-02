<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Company::create([
            'name' => 'PT Digital Nusantara',
            'slogan' => 'Inovasi Tanpa Batas',
            'logo' => 'company/logo.png',
            'address' => 'Jl. Merdeka No. 10, Jakarta',
            'phone' => '021-12345678',
            'email' => 'info@digitalnusantara.com',
            'website' => 'https://www.digitalnusantara.com',
            'registration_number' => '0123456789',
            'currency' => 'IDR',
            'timezone' => 'Asia/Jakarta',
            'theme' => 'dark',
        ]);
    }
}
