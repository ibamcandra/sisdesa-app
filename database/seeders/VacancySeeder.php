<?php

namespace Database\Seeders;

use App\Models\Vacancy;
use App\Models\Branch;
use App\Models\JobCategory;
use App\Models\User;
use App\Models\Skill;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class VacancySeeder extends Seeder
{
    public function run(): void
    {
        $branches = Branch::pluck('id')->toArray();
        $categories = JobCategory::pluck('id')->toArray();
        $users = User::pluck('id')->toArray();
        $skills = Skill::pluck('id')->toArray();

        $jobTitles = [
            'Fullstack Web Developer',
            'Accounting Staff',
            'Digital Marketing Specialist',
            'HR Generalist',
            'Warehouse Supervisor',
            'Backend Engineer (Laravel)',
            'Sales Executive',
            'UI/UX Designer',
            'Admin Purchasing',
            'Finance Manager'
        ];

        $types = ['Full-time', 'Part-time', 'Kontrak', 'Magang'];

        foreach ($jobTitles as $title) {
            $vacancy = Vacancy::create([
                'user_id' => $users[array_rand($users)],
                'branch_id' => $branches[array_rand($branches)],
                'job_category_id' => $categories[array_rand($categories)],
                'title' => $title,
                'slug' => Str::slug($title) . '-' . Str::random(5),
                'type' => $types[array_rand($types)],
                'close_date' => now()->addDays(rand(15, 60)),
                'description' => '<p>Deskripsi pekerjaan untuk ' . $title . '. Meliputi tanggung jawab harian dan target yang harus dicapai.</p>',
                'requirement' => '<p>Kualifikasi yang dibutuhkan: Minimal lulusan S1, pengalaman minimal 2 tahun, dan mampu bekerja dalam tim.</p>',
            ]);

            // Attach 3-5 random skills
            $randomSkills = array_rand(array_flip($skills), rand(3, 5));
            $vacancy->skills()->attach($randomSkills);
        }
    }
}
