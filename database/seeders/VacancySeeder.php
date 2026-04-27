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
        $users = User::whereIn('role', ['super_admin', 'recruitment'])->pluck('id')->toArray();
        $skills = Skill::pluck('id')->toArray();

        // Jika tidak ada user admin/recruiter, gunakan user pertama yang ada
        if (empty($users)) {
            $users = User::pluck('id')->toArray();
        }

        $vacancies = [
            [
                'title' => 'Operator Produksi',
                'description' => '<p>Melakukan pengoperasian mesin produksi sesuai dengan SOP yang berlaku untuk mencapai target produksi harian.</p>',
                'requirement' => '<ul><li>Pria/Wanita</li><li>Pendidikan minimal SMK sederajat</li><li>Usia 18-25 tahun</li><li>Sehat jasmani dan rohani</li></ul>',
            ],
            [
                'title' => 'Quality Control Inspector',
                'description' => '<p>Memastikan setiap produk yang dihasilkan memenuhi standar kualitas perusahaan sebelum didistribusikan.</p>',
                'requirement' => '<ul><li>Detail oriented</li><li>Pendidikan SMK Teknik</li><li>Paham alat ukur</li><li>Pengalaman min 1 tahun</li></ul>',
            ],
            [
                'title' => 'Staf Administrasi Gudang',
                'description' => '<p>Mencatat arus keluar masuk barang, melakukan stok opname berkala, dan membuat laporan logistik.</p>',
                'requirement' => '<ul><li>Mahir MS Excel</li><li>Pendidikan minimal SMA/K</li><li>Teliti dan jujur</li></ul>',
            ],
            [
                'title' => 'Staff IT Support',
                'description' => '<p>Melakukan maintenance perangkat keras dan lunak di lingkungan kantor serta membantu troubleshooting jaringan.</p>',
                'requirement' => '<ul><li>Paham jaringan dasar (TCP/IP)</li><li>Lulusan D3/S1 IT</li><li>Mampu merakit PC</li></ul>',
            ],
            [
                'title' => 'Digital Marketing Content Creator',
                'description' => '<p>Membuat konten kreatif untuk media sosial dan mengelola kampanye iklan digital perusahaan.</p>',
                'requirement' => '<ul><li>Kreatif</li><li>Mampu edit video/foto (Canva/Adobe)</li><li>Update tren medsos</li></ul>',
            ],
            [
                'title' => 'Receptionist & Front Office',
                'description' => '<p>Menerima tamu, melayani telepon masuk, dan membantu keperluan administratif kantor depan.</p>',
                'requirement' => '<ul><li>Berpenampilan menarik</li><li>Komunikasi lancar</li><li>Tinggi badan min 160cm</li></ul>',
            ],
            [
                'title' => 'Accounting & Tax Specialist',
                'description' => '<p>Mengelola laporan keuangan bulanan dan memastikan kewajiban pajak perusahaan terpenuhi tepat waktu.</p>',
                'requirement' => '<ul><li>Paham Brevet A & B</li><li>Lulusan S1 Akuntansi</li><li>Pengalaman 2 tahun</li></ul>',
            ],
            [
                'title' => 'Sales Lapangan (Canvasser)',
                'description' => '<p>Melakukan kunjungan ke outlet-outlet mitra untuk menawarkan produk dan menjalin hubungan baik.</p>',
                'requirement' => '<ul><li>Punya kendaraan pribadi</li><li>Memiliki SIM C</li><li>Komunikatif</li></ul>',
            ],
            [
                'title' => 'Driver Logistik',
                'description' => '<p>Melakukan pengiriman barang ke distributor di wilayah Jawa Barat dengan aman dan tepat waktu.</p>',
                'requirement' => '<ul><li>Memiliki SIM B1/B2 Umum</li><li>Hafal rute Jawa Barat</li><li>Jujur</li></ul>',
            ],
            [
                'title' => 'Maintenance Mesin CNC',
                'description' => '<p>Melakukan perawatan rutin dan perbaikan pada mesin-mesin CNC di workshop produksi.</p>',
                'requirement' => '<ul><li>Paham kelistrikan industri</li><li>Pendidikan SMK Teknik Mesin</li><li>Siap kerja shift</li></ul>',
            ],
        ];

        $types = ['Full-time', 'Part-time', 'Kontrak', 'Magang'];

        foreach ($vacancies as $v) {
            $vacancy = Vacancy::create([
                'user_id' => $users[array_rand($users)],
                'branch_id' => $branches[array_rand($branches)],
                'job_category_id' => $categories[array_rand($categories)],
                'title' => $v['title'],
                'slug' => Str::slug($v['title']) . '-' . Str::random(5),
                'type' => $types[array_rand($types)],
                'close_date' => now()->addDays(rand(7, 45)),
                'description' => $v['description'],
                'requirement' => $v['requirement'],
            ]);

            // Attach 2-4 random skills
            if (!empty($skills)) {
                $randomSkillsCount = min(rand(2, 4), count($skills));
                $randomSkills = array_rand(array_flip($skills), $randomSkillsCount);
                if (is_array($randomSkills)) {
                    $vacancy->skills()->attach($randomSkills);
                } else {
                    $vacancy->skills()->attach([$randomSkills]);
                }
            }
        }
    }
}
