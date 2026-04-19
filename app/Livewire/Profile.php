<?php

namespace App\Livewire;

use App\Models\ApplicantProfile;
use App\Models\ApplicantExperience;
use App\Models\ApplicantEducation;
use App\Models\ApplicantCertification;
use App\Models\Skill;
use App\Models\Province;
use App\Models\City;
use App\Models\District;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;

#[Title('Profil Saya')]
class Profile extends Component
{
    use WithFileUploads;

    // Profil Pelamar
    public $applicant;

    // Data Pribadi (form)
    public $name, $email, $phone, $birth_date, $gender, $address;
    public $province_id, $city_id, $district_id;
    public $education_level_id, $major;
    public $editingPersonal = false;

    // Region Data
    public $provinces = [];
    public $cities = [];
    public $districts = [];

    // Avatar & CV
    public $avatarUpload;
    public $cvUpload;

    // CRUD Pengalaman Kerja
    public $showExperienceForm = false;
    public $editingExperienceId = null;
    public $exp_company_name, $exp_position, $exp_start_date, $exp_end_date, $exp_is_current = false, $exp_description;

    // CRUD Pendidikan
    public $showEducationForm = false;
    public $editingEducationId = null;
    public $edu_institution_name, $edu_degree, $edu_major, $edu_gpa, $edu_start_year, $edu_end_year, $edu_is_graduated = true;

    // CRUD Sertifikasi
    public $showCertificationForm = false;
    public $editingCertificationId = null;
    public $cert_name, $cert_organization, $cert_issue_date, $cert_expiry_date, $cert_credential_url;

    // Skills
    public $showSkillForm = false;
    public $selectedSkills = [];
    public $availableSkills = [];

    // Konfirmasi Hapus
    public $confirmDeletionId = null;
    public $confirmDeletionType = null; // 'experience', 'education', 'certification', 'cv'

    public function mount()
    {
        $user = Auth::user();

        // Auto-create applicant_profile jika belum ada
        $this->applicant = ApplicantProfile::with(['province', 'city', 'district'])->firstOrCreate(
            ['user_id' => $user->id],
            ['name' => $user->name, 'email' => $user->email]
        );

        // Load form data pribadi
        $this->name = $this->applicant->name;
        $this->email = $this->applicant->email;
        $this->phone = $this->applicant->phone;
        $this->birth_date = $this->applicant->birth_date?->format('Y-m-d');
        $this->gender = $this->applicant->gender;
        $this->address = $this->applicant->address;
        $this->province_id = $this->applicant->province_id;
        $this->city_id = $this->applicant->city_id;
        $this->district_id = $this->applicant->district_id;
        $this->education_level_id = $this->applicant->education_level_id;
        $this->major = $this->applicant->major;

        // Load Initial Regions using Relationships
        $this->provinces = Province::orderBy('name')->get();
        if ($this->province_id) {
            $province = Province::find($this->province_id);
            if ($province) {
                $this->cities = $province->cities()->orderBy('name')->get();
            }
        }
        if ($this->city_id) {
            $city = City::find($this->city_id);
            if ($city) {
                $this->districts = $city->districts()->orderBy('name')->get();
            }
        }

        // Load skills
        $this->selectedSkills = $this->applicant->skills->pluck('id')->toArray();
        $this->availableSkills = Skill::orderBy('name')->get();
    }

    // ==========================================
    // REGION LOGIC (Dependent Dropdown)
    // ==========================================
    public function updatedProvinceId($value)
    {
        $province = Province::find($value);
        if ($province) {
            $this->cities = $province->cities()->orderBy('name')->get();
        } else {
            $this->cities = [];
        }
        $this->city_id = null;
        $this->districts = [];
        $this->district_id = null;
    }

    public function updatedCityId($value)
    {
        $city = City::find($value);
        if ($city) {
            $this->districts = $city->districts()->orderBy('name')->get();
        } else {
            $this->districts = [];
        }
        $this->district_id = null;
    }

    // ==========================================
    // DATA PRIBADI
    // ==========================================
    public function toggleEditPersonal()
    {
        $this->editingPersonal = !$this->editingPersonal;
    }

    public function savePersonalData()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:Laki-laki,Perempuan',
            'address' => 'nullable|string|max:500',
            'province_id' => 'nullable|exists:indonesia_provinces,id',
            'city_id' => 'nullable|exists:indonesia_cities,id',
            'district_id' => 'nullable|exists:indonesia_districts,id',
            'education_level_id' => 'nullable|exists:education_levels,id',
            'major' => 'nullable|string|max:255',
        ]);

        $this->applicant->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'birth_date' => $this->birth_date ?: null,
            'gender' => $this->gender ?: null,
            'address' => $this->address ?: null,
            'province_id' => $this->province_id ?: null,
            'city_id' => $this->city_id ?: null,
            'district_id' => $this->district_id ?: null,
            'education_level_id' => $this->education_level_id ?: null,
            'major' => $this->major ?: null,
        ]);

        $this->editingPersonal = false;
        $this->applicant->load(['province', 'city', 'district']);
        session()->flash('success', 'Data pribadi berhasil diperbarui.');
    }

    // ==========================================
    // AVATAR
    // ==========================================
    public function updatedAvatarUpload()
    {
        $this->validate(['avatarUpload' => 'image|max:2048']);

        if ($this->applicant->avatar) {
            Storage::disk('public')->delete($this->applicant->avatar);
        }

        $path = $this->avatarUpload->store('avatars', 'public');
        $this->applicant->update(['avatar' => $path]);
        $this->applicant->refresh();
    }

    // ==========================================
    // CV UPLOAD
    // ==========================================
    public function updatedCvUpload()
    {
        $this->validate(['cvUpload' => 'file|mimes:pdf,doc,docx|max:5120']);

        if ($this->applicant->cv_file) {
            Storage::disk('public')->delete($this->applicant->cv_file);
        }

        $path = $this->cvUpload->store('cv-files', 'public');
        $this->applicant->update(['cv_file' => $path]);
        $this->applicant->refresh();
    }

    public function deleteCv()
    {
        if ($this->applicant->cv_file) {
            Storage::disk('public')->delete($this->applicant->cv_file);
            $this->applicant->update(['cv_file' => null]);
            $this->applicant->refresh();
        }
    }

    // ==========================================
    // PENGALAMAN KERJA (CRUD)
    // ==========================================
    public function openExperienceForm()
    {
        $this->resetExperienceForm();
        $this->showExperienceForm = true;
    }

    public function editExperience($id)
    {
        $exp = ApplicantExperience::findOrFail($id);
        $this->editingExperienceId = $exp->id;
        $this->exp_company_name = $exp->company_name;
        $this->exp_position = $exp->position;
        $this->exp_start_date = $exp->start_date->format('Y-m-d');
        $this->exp_end_date = $exp->end_date?->format('Y-m-d');
        $this->exp_is_current = $exp->is_current;
        $this->exp_description = $exp->description;
        $this->showExperienceForm = true;
    }

    public function saveExperience()
    {
        $this->validate([
            'exp_company_name' => 'required|string|max:255',
            'exp_position' => 'required|string|max:255',
            'exp_start_date' => 'required|date',
            'exp_end_date' => 'nullable|date|after_or_equal:exp_start_date',
            'exp_description' => 'nullable|string|max:1000',
        ]);

        $data = [
            'applicant_profile_id' => $this->applicant->id,
            'company_name' => $this->exp_company_name,
            'position' => $this->exp_position,
            'start_date' => $this->exp_start_date,
            'end_date' => ($this->exp_is_current || !$this->exp_end_date) ? null : $this->exp_end_date,
            'is_current' => $this->exp_is_current,
            'description' => $this->exp_description ?: null,
        ];

        if ($this->editingExperienceId) {
            ApplicantExperience::findOrFail($this->editingExperienceId)->update($data);
        } else {
            ApplicantExperience::create($data);
        }

        $this->resetExperienceForm();
        $this->showExperienceForm = false;
        session()->flash('success', 'Pengalaman kerja berhasil disimpan.');
    }

    public function deleteExperience($id)
    {
        ApplicantExperience::findOrFail($id)->delete();
    }

    private function resetExperienceForm()
    {
        $this->editingExperienceId = null;
        $this->exp_company_name = '';
        $this->exp_position = '';
        $this->exp_start_date = '';
        $this->exp_end_date = '';
        $this->exp_is_current = false;
        $this->exp_description = '';
    }

    // ==========================================
    // PENDIDIKAN (CRUD)
    // ==========================================
    public function openEducationForm()
    {
        $this->resetEducationForm();
        $this->showEducationForm = true;
    }

    public function editEducation($id)
    {
        $edu = ApplicantEducation::findOrFail($id);
        $this->editingEducationId = $edu->id;
        $this->edu_institution_name = $edu->institution_name;
        $this->edu_degree = $edu->degree;
        $this->edu_major = $edu->major;
        $this->edu_gpa = $edu->gpa;
        $this->edu_start_year = $edu->start_year;
        $this->edu_end_year = $edu->end_year;
        $this->edu_is_graduated = $edu->is_graduated;
        $this->showEducationForm = true;
    }

    public function saveEducation()
    {
        $this->validate([
            'edu_institution_name' => 'required|string|max:255',
            'edu_degree' => 'required|string|max:100',
            'edu_major' => 'nullable|string|max:255',
            'edu_gpa' => 'nullable|numeric',
            'edu_start_year' => 'required|integer|min:1970|max:' . date('Y'),
            'edu_end_year' => 'nullable|integer|min:1970|max:' . (date('Y') + 5),
        ]);

        $data = [
            'applicant_profile_id' => $this->applicant->id,
            'institution_name' => $this->edu_institution_name,
            'degree' => $this->edu_degree,
            'major' => $this->edu_major ?: null,
            'gpa' => $this->edu_gpa ?: null,
            'start_year' => $this->edu_start_year,
            'end_year' => ($this->edu_is_graduated && $this->edu_end_year) ? $this->edu_end_year : null,
            'is_graduated' => $this->edu_is_graduated,
        ];

        if ($this->editingEducationId) {
            ApplicantEducation::findOrFail($this->editingEducationId)->update($data);
        } else {
            ApplicantEducation::create($data);
        }

        $this->resetEducationForm();
        $this->showEducationForm = false;
        session()->flash('success', 'Riwayat pendidikan berhasil disimpan.');
    }

    public function deleteEducation($id)
    {
        ApplicantEducation::findOrFail($id)->delete();
    }

    private function resetEducationForm()
    {
        $this->editingEducationId = null;
        $this->edu_institution_name = '';
        $this->edu_degree = '';
        $this->edu_major = '';
        $this->edu_gpa = '';
        $this->edu_start_year = '';
        $this->edu_end_year = '';
        $this->edu_is_graduated = true;
    }

    // ==========================================
    // SERTIFIKASI (CRUD)
    // ==========================================
    public function openCertificationForm()
    {
        $this->resetCertificationForm();
        $this->showCertificationForm = true;
    }

    public function editCertification($id)
    {
        $cert = ApplicantCertification::findOrFail($id);
        $this->editingCertificationId = $cert->id;
        $this->cert_name = $cert->certification_name;
        $this->cert_organization = $cert->issuing_organization;
        $this->cert_issue_date = $cert->issue_date?->format('Y-m-d');
        $this->cert_expiry_date = $cert->expiry_date?->format('Y-m-d');
        $this->cert_credential_url = $cert->credential_url;
        $this->showCertificationForm = true;
    }

    public function saveCertification()
    {
        $this->validate([
            'cert_name' => 'required|string|max:255',
            'cert_organization' => 'required|string|max:255',
            'cert_issue_date' => 'nullable|date',
            'cert_expiry_date' => 'nullable|date',
            'cert_credential_url' => 'nullable|url|max:500',
        ]);

        $data = [
            'applicant_profile_id' => $this->applicant->id,
            'certification_name' => $this->cert_name,
            'issuing_organization' => $this->cert_organization,
            'issue_date' => $this->cert_issue_date ?: null,
            'expiry_date' => $this->cert_expiry_date ?: null,
            'credential_url' => $this->cert_credential_url ?: null,
        ];

        if ($this->editingCertificationId) {
            ApplicantCertification::findOrFail($this->editingCertificationId)->update($data);
        } else {
            ApplicantCertification::create($data);
        }

        $this->resetCertificationForm();
        $this->showCertificationForm = false;
        session()->flash('success', 'Sertifikasi berhasil disimpan.');
    }

    public function deleteCertification($id)
    {
        ApplicantCertification::findOrFail($id)->delete();
    }

    private function resetCertificationForm()
    {
        $this->editingCertificationId = null;
        $this->cert_name = '';
        $this->cert_organization = '';
        $this->cert_issue_date = '';
        $this->cert_expiry_date = '';
        $this->cert_credential_url = '';
    }

    // ==========================================
    // KEAHLIAN (SKILLS)
    // ==========================================
    public function toggleSkillForm()
    {
        $this->showSkillForm = !$this->showSkillForm;
    }

    public function saveSkills()
    {
        $this->applicant->skills()->sync($this->selectedSkills);
        $this->showSkillForm = false;
        $this->applicant->refresh();
        session()->flash('success', 'Keahlian berhasil diperbarui.');
    }

    public function removeSkill($skillId)
    {
        $this->applicant->skills()->detach($skillId);
        $this->selectedSkills = array_values(array_diff($this->selectedSkills, [$skillId]));
        $this->applicant->refresh();
    }

    // ==========================================
    // KONFIRMASI HAPUS (MODAL)
    // ==========================================
    public function confirmDelete($id, $type)
    {
        $this->confirmDeletionId = $id;
        $this->confirmDeletionType = $type;
        $this->dispatch('open-delete-modal');
    }

    public function deleteConfirmed()
    {
        if ($this->confirmDeletionType === 'experience') {
            ApplicantExperience::findOrFail($this->confirmDeletionId)->delete();
        } elseif ($this->confirmDeletionType === 'education') {
            ApplicantEducation::findOrFail($this->confirmDeletionId)->delete();
        } elseif ($this->confirmDeletionType === 'certification') {
            ApplicantCertification::findOrFail($this->confirmDeletionId)->delete();
        } elseif ($this->confirmDeletionType === 'cv') {
            $this->deleteCv();
        }

        $this->confirmDeletionId = null;
        $this->confirmDeletionType = null;
        $this->dispatch('close-delete-modal');
        session()->flash('success', 'Data berhasil dihapus.');
    }

    // ==========================================
    // RENDER
    // ==========================================
    public function render()
    {
        return view('livewire.profile', [
            'experiences' => $this->applicant->experiences()
                ->orderBy('is_current', 'desc')
                ->orderBy('start_date', 'desc')
                ->get(),
            'educations' => $this->applicant->educations()
                ->orderBy('start_year', 'desc')
                ->get(),
            'certifications' => $this->applicant->certifications()
                ->orderBy('issue_date', 'desc')
                ->get(),
            'profileSkills' => $this->applicant->skills,
            'educationLevels' => \App\Models\EducationLevel::orderBy('id', 'asc')->get(),
        ])->layout('components.layouts.app');
    }
}
