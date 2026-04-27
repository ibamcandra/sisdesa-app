<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        $skills = [
            // Teknologi Informasi (IT) & Software
            ['name' => 'Pemrograman PHP', 'description' => 'Hard Skill - IT & Software'],
            ['name' => 'Pemrograman Python', 'description' => 'Hard Skill - IT & Software'],
            ['name' => 'JavaScript', 'description' => 'Hard Skill - IT & Software'],
            ['name' => 'Cloud Computing (AWS/Azure)', 'description' => 'Hard Skill - IT & Software'],
            ['name' => 'Database (SQL/PostgreSQL)', 'description' => 'Hard Skill - IT & Software'],
            ['name' => 'DevOps (Docker, Kubernetes)', 'description' => 'Hard Skill - IT & Software'],
            ['name' => 'Cybersecurity', 'description' => 'Hard Skill - IT & Software'],
            ['name' => 'Network Configuration', 'description' => 'Hard Skill - IT & Software'],

            // Pemasaran & Digital Marketing
            ['name' => 'SEO/SEM', 'description' => 'Hard Skill - Pemasaran & Digital Marketing'],
            ['name' => 'Content Writing', 'description' => 'Hard Skill - Pemasaran & Digital Marketing'],
            ['name' => 'Social Media Analytics', 'description' => 'Hard Skill - Pemasaran & Digital Marketing'],
            ['name' => 'Email Marketing Strategy', 'description' => 'Hard Skill - Pemasaran & Digital Marketing'],
            ['name' => 'Google Ads', 'description' => 'Hard Skill - Pemasaran & Digital Marketing'],
            ['name' => 'Affiliate Marketing', 'description' => 'Hard Skill - Pemasaran & Digital Marketing'],

            // Desain Kreatif & Multimedia
            ['name' => 'Adobe Photoshop', 'description' => 'Hard Skill - Desain Kreatif'],
            ['name' => 'Adobe Illustrator', 'description' => 'Hard Skill - Desain Kreatif'],
            ['name' => 'Video Editing (Premiere/DaVinci)', 'description' => 'Hard Skill - Desain Kreatif'],
            ['name' => 'UI/UX Design (Figma)', 'description' => 'Hard Skill - Desain Kreatif'],
            ['name' => 'Motion Graphics', 'description' => 'Hard Skill - Desain Kreatif'],
            ['name' => 'Fotografi', 'description' => 'Hard Skill - Desain Kreatif'],

            // Keuangan & Akuntansi
            ['name' => 'Analisis Laporan Keuangan', 'description' => 'Hard Skill - Keuangan'],
            ['name' => 'Perpajakan (Brevet A/B)', 'description' => 'Hard Skill - Keuangan'],
            ['name' => 'Audit', 'description' => 'Hard Skill - Keuangan'],
            ['name' => 'Manajemen Risiko', 'description' => 'Hard Skill - Keuangan'],
            ['name' => 'Software Akuntansi (SAP/Xero)', 'description' => 'Hard Skill - Keuangan'],

            // Manajemen Proyek & Bisnis
            ['name' => 'Business Analysis', 'description' => 'Hard Skill - Manajemen Bisnis'],
            ['name' => 'Agile/Scrum Methodology', 'description' => 'Hard Skill - Manajemen Bisnis'],
            ['name' => 'Supply Chain Management', 'description' => 'Hard Skill - Manajemen Bisnis'],
            ['name' => 'Market Research', 'description' => 'Hard Skill - Manajemen Bisnis'],
            ['name' => 'Strategic Planning', 'description' => 'Hard Skill - Manajemen Bisnis'],

            // Teknik & Konstruksi
            ['name' => 'AutoCAD/Revit', 'description' => 'Hard Skill - Teknik'],
            ['name' => 'Structural Analysis', 'description' => 'Hard Skill - Teknik'],
            ['name' => 'Electrical Wiring', 'description' => 'Hard Skill - Teknik'],
            ['name' => 'Project Estimating', 'description' => 'Hard Skill - Teknik'],
            ['name' => 'Pemeliharaan Mesin (Maintenance)', 'description' => 'Hard Skill - Teknik'],

            // Kesehatan & Medis
            ['name' => 'Diagnosa Medis', 'description' => 'Hard Skill - Medis'],
            ['name' => 'Pengoperasian Alat Medis', 'description' => 'Hard Skill - Medis'],
            ['name' => 'Farmakologi', 'description' => 'Hard Skill - Medis'],
            ['name' => 'Keperawatan Kritis', 'description' => 'Hard Skill - Medis'],
            ['name' => 'Medical Coding', 'description' => 'Hard Skill - Medis'],

            // Pendidikan & Pelatihan
            ['name' => 'Penyusunan Kurikulum', 'description' => 'Hard Skill - Pendidikan'],
            ['name' => 'Metodologi Pembelajaran', 'description' => 'Hard Skill - Pendidikan'],
            ['name' => 'Evaluasi Pendidikan', 'description' => 'Hard Skill - Pendidikan'],
            ['name' => 'LMS Management', 'description' => 'Hard Skill - Pendidikan'],

            // Administrasi & Perkantoran
            ['name' => 'Data Entry', 'description' => 'Hard Skill - Administrasi'],
            ['name' => 'Manajemen Kearsipan', 'description' => 'Hard Skill - Administrasi'],
            ['name' => 'Advanced Microsoft Excel', 'description' => 'Hard Skill - Administrasi'],
            ['name' => 'Kesekretariatan', 'description' => 'Hard Skill - Administrasi'],

            // Soft Skill - Komunikasi
            ['name' => 'Komunikasi Efektif', 'description' => 'Soft Skill - Interpersonal'],
            ['name' => 'Negosiasi', 'description' => 'Soft Skill - Interpersonal'],
            ['name' => 'Public Speaking', 'description' => 'Soft Skill - Interpersonal'],
            ['name' => 'Empati', 'description' => 'Soft Skill - Interpersonal'],

            // Soft Skill - Kepemimpinan
            ['name' => 'Leadership', 'description' => 'Soft Skill - Manajemen Diri'],
            ['name' => 'Manajemen Waktu', 'description' => 'Soft Skill - Manajemen Diri'],
            ['name' => 'Problem Solving', 'description' => 'Soft Skill - Manajemen Diri'],
            ['name' => 'Pengambilan Keputusan', 'description' => 'Soft Skill - Manajemen Diri'],

            // Soft Skill - Adaptabilitas
            ['name' => 'Fleksibilitas', 'description' => 'Soft Skill - Sikap Kerja'],
            ['name' => 'Resiliensi (Ketahanan)', 'description' => 'Soft Skill - Sikap Kerja'],
            ['name' => 'Kerja Sama Tim (Teamwork)', 'description' => 'Soft Skill - Sikap Kerja'],
            ['name' => 'Berpikir Kritis (Critical Thinking)', 'description' => 'Soft Skill - Sikap Kerja'],
        ];

        foreach ($skills as $skill) {
            Skill::updateOrCreate(['name' => $skill['name']], $skill);
        }
    }
}
