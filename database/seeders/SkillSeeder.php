<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        $skills = [
            // IT & Software Development
            ['name' => 'PHP', 'description' => 'Bahasa pemrograman server-side'],
            ['name' => 'Laravel', 'description' => 'Framework PHP populer'],
            ['name' => 'JavaScript', 'description' => 'Bahasa pemrograman web'],
            ['name' => 'React.js', 'description' => 'Library JS untuk UI'],
            ['name' => 'Vue.js', 'description' => 'Progressive framework JS'],
            ['name' => 'Python', 'description' => 'Bahasa pemrograman serbaguna'],
            ['name' => 'Django', 'description' => 'Framework Python'],
            ['name' => 'MySQL', 'description' => 'Sistem manajemen database'],
            ['name' => 'PostgreSQL', 'description' => 'RDBMS open source'],
            ['name' => 'Docker', 'description' => 'Platform containerization'],
            ['name' => 'Git', 'description' => 'Version control system'],
            ['name' => 'AWS', 'description' => 'Cloud computing platform'],
            ['name' => 'Mobile Development (Flutter)', 'description' => 'Framework aplikasi mobile'],
            ['name' => 'UI/UX Design', 'description' => 'Desain antarmuka & pengalaman pengguna'],
            
            // Design & Creative
            ['name' => 'Adobe Photoshop', 'description' => 'Editing foto & desain grafis'],
            ['name' => 'Adobe Illustrator', 'description' => 'Desain vektor'],
            ['name' => 'Figma', 'description' => 'Tools desain UI/UX'],
            ['name' => 'CorelDRAW', 'description' => 'Desain grafis'],
            ['name' => 'Video Editing (Premiere Pro)', 'description' => 'Pengeditan video profesional'],
            ['name' => 'Motion Graphics', 'description' => 'Desain grafis bergerak'],
            
            // Marketing & Sales
            ['name' => 'Digital Marketing', 'description' => 'Pemasaran melalui media digital'],
            ['name' => 'SEO (Search Engine Optimization)', 'description' => 'Optimasi mesin pencari'],
            ['name' => 'Social Media Management', 'description' => 'Pengelolaan akun media sosial'],
            ['name' => 'Copywriting', 'description' => 'Teknik penulisan teks iklan'],
            ['name' => 'Sales Negotiation', 'description' => 'Keterampilan negosiasi penjualan'],
            ['name' => 'Market Research', 'description' => 'Riset pasar'],
            
            // Finance & Accounting
            ['name' => 'Accounting', 'description' => 'Akuntansi dasar & lanjutan'],
            ['name' => 'Financial Auditing', 'description' => 'Audit keuangan'],
            ['name' => 'Taxation (Perpajakan)', 'description' => 'Pemahaman hukum pajak'],
            ['name' => 'Microsoft Excel (Advanced)', 'description' => 'Penggunaan Excel untuk data keuangan'],
            ['name' => 'Zahir/MYOB', 'description' => 'Software akuntansi'],
            
            // HR & Admin
            ['name' => 'Recruitment & Selection', 'description' => 'Proses rekrutmen karyawan'],
            ['name' => 'Human Resources Management', 'description' => 'Manajemen sumber daya manusia'],
            ['name' => 'Payroll Administration', 'description' => 'Pengelolaan gaji karyawan'],
            ['name' => 'Office Administration', 'description' => 'Administrasi perkantoran'],
            ['name' => 'Data Entry', 'description' => 'Penginputan data'],
            
            // Engineering & Technical
            ['name' => 'AutoCAD', 'description' => 'Desain teknik berbantuan komputer'],
            ['name' => 'Electrical Engineering', 'description' => 'Teknik elektro'],
            ['name' => 'PLC Programming', 'description' => 'Pemrograman PLC industri'],
            ['name' => 'Mechanical Maintenance', 'description' => 'Pemeliharaan mesin'],
            
            // Customer Service
            ['name' => 'Customer Relationship Management (CRM)', 'description' => 'Manajemen hubungan pelanggan'],
            ['name' => 'Communication Skills', 'description' => 'Keterampilan komunikasi'],
            ['name' => 'Problem Solving', 'description' => 'Pemecahan masalah'],
            ['name' => 'Telemarketing', 'description' => 'Pemasaran via telepon'],
            
            // Soft Skills
            ['name' => 'Leadership', 'description' => 'Kepemimpinan'],
            ['name' => 'Teamwork', 'description' => 'Kerja sama tim'],
            ['name' => 'Critical Thinking', 'description' => 'Berpikir kritis'],
            ['name' => 'Time Management', 'description' => 'Manajemen waktu'],
            ['name' => 'Public Speaking', 'description' => 'Berbicara di depan umum'],
            ['name' => 'Bilingual (English/Indonesian)', 'description' => 'Kemampuan dua bahasa'],
        ];

        foreach ($skills as $skill) {
            Skill::updateOrCreate(['name' => $skill['name']], $skill);
        }
    }
}
