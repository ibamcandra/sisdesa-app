<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EducationLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $levels = [
            'SD',
            'SMP/Sederajat',
            'SMA/SMK/Sederajat',
            'D1',
            'D2',
            'D3',
            'D4',
            'S1',
            'S2',
            'S3'
        ];

        foreach ($levels as $level) {
            \App\Models\EducationLevel::create(['name' => $level]);
        }
    }
}
