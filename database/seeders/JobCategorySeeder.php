<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JobCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Teknologi Informasi (IT)', 'description' => 'Software engineering, jaringan, data, keamanan.'],
            ['name' => 'Keuangan & Akuntansi', 'description' => 'Akuntansi, audit, pajak, perbankan.'],
            ['name' => 'Pemasaran & Penjualan', 'description' => 'Marketing, sales, digital marketing, PR.'],
            ['name' => 'Sumber Daya Manusia (HR)', 'description' => 'Rekrutmen, training, GA, personalia.'],
            ['name' => 'Operasional & Logistik', 'description' => 'Gudang, supply chain, operasional kantor.'],
            ['name' => 'Administrasi', 'description' => 'Data entry, admin, sekretaris.'],
        ];

        foreach ($categories as $category) {
            \App\Models\JobCategory::create($category);
        }
    }
}
