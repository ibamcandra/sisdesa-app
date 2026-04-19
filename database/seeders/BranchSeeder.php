<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Branch::create([
            'name' => 'Kantor Pusat',
            'address' => 'Jl. Jend. Sudirman No. 1, Jakarta',
            'phone' => '021-1234567',
            'email' => 'pusat@perusahaan.com'
        ]);
    }
}
