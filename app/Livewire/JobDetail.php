<?php

namespace App\Livewire;

use App\Models\Vacancy;
use App\Models\JobApplication;
use App\Mail\JobAppliedNotification;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class JobDetail extends Component
{
    public $vacancy;

    public function mount($slug)
    {
        $this->vacancy = Vacancy::with(['skills', 'jobCategory', 'user', 'branch'])->where('slug', $slug)->firstOrFail();
    }

    public function apply()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Pastikan role-nya adalah pelamar
        if ($user->role !== 'pelamar') {
            session()->flash('error', 'Hanya pelamar yang dapat melamar pekerjaan.');
            return;
        }

        $profile = $user->applicantProfile;

        // Validasi keberadaan profil
        if (!$profile) {
            session()->flash('error', 'Silakan lengkapi profil Anda terlebih dahulu.');
            return redirect()->route('profile');
        }

        // Validasi kelengkapan profil (kecuali pengalaman kerja)
        $requiredFields = [
            'name' => 'Nama Lengkap',
            'email' => 'Email',
            'phone' => 'Nomor WhatsApp',
            'birth_date' => 'Tanggal Lahir',
            'gender' => 'Jenis Kelamin',
            'address' => 'Alamat Lengkap',
            'province_id' => 'Provinsi',
            'city_id' => 'Kabupaten/Kota',
            'district_id' => 'Kecamatan',
            'education_level_id' => 'Tingkat Pendidikan',
            'major' => 'Jurusan'
        ];

        foreach ($requiredFields as $field => $label) {
            if (empty($profile->$field)) {
                session()->flash('error', "Profil Anda belum lengkap ($label). Silakan lengkapi data pribadi Anda sebelum melamar.");
                return redirect()->route('profile');
            }
        }

        // Validasi Upload CV
        if (empty($profile->cv_file)) {
            session()->flash('error', "Silakan unggah CV Anda terlebih dahulu di halaman profil sebelum melamar.");
            return redirect()->route('profile');
        }

        // Cek apakah sudah pernah melamar di lowongan ini
        $existing = JobApplication::where('vacancy_id', $this->vacancy->id)
            ->where('applicant_profile_id', $profile->id)
            ->first();

        if ($existing) {
            session()->flash('error', 'Anda sudah melamar pada lowongan ini.');
            return redirect()->route('history');
        }

        // Simpan Lamaran
        $application = JobApplication::create([
            'vacancy_id' => $this->vacancy->id,
            'applicant_profile_id' => $profile->id,
            'status' => 'Terkirim',
        ]);

        // Kirim Notifikasi Email
        try {
            Mail::to($user->email)->send(new JobAppliedNotification($application));
        } catch (\Exception $e) {
            \Log::error('Gagal mengirim email lamaran: ' . $e->getMessage());
        }

        session()->flash('success', 'Lamaran Anda berhasil terkirim! Silakan cek email Anda.');
        return redirect()->route('history');
    }

    public function render()
    {
        return view('livewire.job-detail');
    }
}
