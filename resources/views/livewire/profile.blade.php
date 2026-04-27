<div class="max-w-4xl mx-auto px-4 py-8 md:py-12 pb-32">
    {{-- Notifikasi Sukses --}}
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" class="mb-6 p-4 bg-green-50 text-green-700 rounded-2xl border border-green-100 flex items-center gap-3">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-8 flex justify-between items-end">
        <div>
            <h2 class="text-2xl font-black text-gray-900">Profil Saya</h2>
            <p class="text-sm text-gray-500">Lengkapi data diri Anda untuk meningkatkan peluang diterima kerja</p>
        </div>
    </div>

    <div class="flex flex-col gap-8">
        {{-- Section: Foto Profil --}}
        <div x-data="{ uploading: false, progress: 0 }" 
             x-on:livewire-upload-start="uploading = true" 
             x-on:livewire-upload-finish="uploading = false" 
             x-on:livewire-upload-error="uploading = false" 
             x-on:livewire-upload-progress="progress = $event.detail.progress"
             class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-gray-50 shadow-sm flex flex-col md:flex-row items-center gap-6">
            <div class="relative group">
                <div class="w-32 h-32 bg-gray-100 rounded-full border-4 border-white shadow-lg overflow-hidden flex items-center justify-center">
                    <img src="{{ $applicant->avatar_url }}" alt="Avatar" class="w-full h-full object-cover">
                    <div wire:loading wire:target="avatarUpload" class="absolute inset-0 bg-black/50 flex items-center justify-center">
                        <svg class="animate-spin h-6 w-6 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </div>
                </div>
                <label class="absolute bottom-0 right-0 w-10 h-10 bg-kt-red text-white rounded-full border-4 border-white flex items-center justify-center shadow-lg hover:scale-110 transition-transform cursor-pointer">
                    <input type="file" wire:model="avatarUpload" class="hidden" accept="image/*">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </label>
            </div>
            <div class="text-center md:text-left flex-1">
                <h4 class="font-bold text-gray-900">Foto Profil</h4>
                <p class="text-xs text-gray-400 mt-1">Gunakan foto formal agar terlihat profesional. Maksimal 2MB.</p>
                
                {{-- Progress Bar --}}
                <div x-show="uploading" class="mt-4">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-[10px] font-black text-kt-red uppercase tracking-widest">Mengunggah...</span>
                        <span class="text-[10px] font-black text-kt-red" x-text="progress + '%'"></span>
                    </div>
                    <div class="w-full bg-red-50 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-kt-red h-full rounded-full transition-all duration-300" :style="'width: ' + progress + '%'"></div>
                    </div>
                </div>

                @error('avatarUpload') <span class="text-red-500 text-[10px] font-bold block mt-2">{{ $message }}</span> @enderror
            </div>
        </div>

        {{-- Section: Data Pribadi --}}
        <div class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-gray-50 shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-3">
                    <span class="w-1.5 h-6 bg-kt-red rounded-full"></span>
                    Data Pribadi
                </h3>
                <button wire:click="toggleEditPersonal" class="flex items-center gap-2 px-4 py-2 bg-red-50 hover:bg-kt-red text-kt-red hover:text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition-all duration-300 shadow-sm shadow-red-50/50">
                    @if($editingPersonal)
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        Batal
                    @else
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Ubah
                    @endif
                </button>
            </div>

            @if($editingPersonal)
                <form wire:submit.prevent="savePersonalData" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex flex-col gap-1">
                        <label class="text-[10px] text-gray-400 font-bold uppercase tracking-widest ml-1">Nama Lengkap</label>
                        <input type="text" wire:model="name" class="w-full h-11 bg-gray-50 border-none rounded-xl px-4 text-sm focus:ring-2 focus:ring-kt-red">
                        @error('name') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-[10px] text-gray-400 font-bold uppercase tracking-widest ml-1">Email</label>
                        <input type="email" wire:model="email" class="w-full h-11 bg-gray-200 border-none rounded-xl px-4 text-sm text-gray-500 cursor-not-allowed" readonly>
                        @error('email') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-[10px] text-gray-400 font-bold uppercase tracking-widest ml-1">Telepon</label>
                        <input type="tel" wire:model="phone" class="w-full h-11 bg-gray-50 border-none rounded-xl px-4 text-sm focus:ring-2 focus:ring-kt-red" placeholder="Contoh: 08123456789">
                        @error('phone') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-[10px] text-gray-400 font-bold uppercase tracking-widest ml-1">Jenis Kelamin</label>
                        <select wire:model="gender" class="w-full h-11 bg-gray-50 border-none rounded-xl px-4 text-sm focus:ring-2 focus:ring-kt-red">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                        @error('gender') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-[10px] text-gray-400 font-bold uppercase tracking-widest ml-1">Tanggal Lahir</label>
                        <input type="date" wire:model="birth_date" class="w-full h-11 bg-gray-50 border-none rounded-xl px-4 text-sm focus:ring-2 focus:ring-kt-red">
                        @error('birth_date') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-[10px] text-gray-400 font-bold uppercase tracking-widest ml-1">Tingkat Pendidikan</label>
                        <select wire:model="education_level_id" class="w-full h-11 bg-gray-50 border-none rounded-xl px-4 text-sm focus:ring-2 focus:ring-kt-red">
                            <option value="">Pilih Pendidikan</option>
                            @foreach($educationLevels as $level)
                                <option value="{{ $level->id }}">{{ $level->name }}</option>
                            @endforeach
                        </select>
                        @error('education_level_id') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-[10px] text-gray-400 font-bold uppercase tracking-widest ml-1">Jurusan</label>
                        <input type="text" wire:model="major" class="w-full h-11 bg-gray-50 border-none rounded-xl px-4 text-sm focus:ring-2 focus:ring-kt-red" placeholder="Contoh: Teknik Informatika">
                        @error('major') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-[10px] text-gray-400 font-bold uppercase tracking-widest ml-1">Provinsi</label>
                        <select wire:model.live="province_id" class="w-full h-11 bg-gray-50 border-none rounded-xl px-4 text-sm focus:ring-2 focus:ring-kt-red">
                            <option value="">Pilih Provinsi</option>
                            @foreach($provinces as $province)
                                <option value="{{ $province->id }}">{{ $province->name }}</option>
                            @endforeach
                        </select>
                        @error('province_id') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-[10px] text-gray-400 font-bold uppercase tracking-widest ml-1">Kabupaten/Kota</label>
                        <select wire:model.live="city_id" class="w-full h-11 bg-gray-50 border-none rounded-xl px-4 text-sm focus:ring-2 focus:ring-kt-red" {{ empty($cities) ? 'disabled' : '' }}>
                            <option value="">Pilih Kabupaten/Kota</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->id }}">{{ $city->name }}</option>
                            @endforeach
                        </select>
                        @error('city_id') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-[10px] text-gray-400 font-bold uppercase tracking-widest ml-1">Kecamatan</label>
                        <select wire:model="district_id" class="w-full h-11 bg-gray-50 border-none rounded-xl px-4 text-sm focus:ring-2 focus:ring-kt-red" {{ empty($districts) ? 'disabled' : '' }}>
                            <option value="">Pilih Kecamatan</option>
                            @foreach($districts as $district)
                                <option value="{{ $district->id }}">{{ $district->name }}</option>
                            @endforeach
                        </select>
                        @error('district_id') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex flex-col gap-1 md:col-span-2">
                        <label class="text-[10px] text-gray-400 font-bold uppercase tracking-widest ml-1">Alamat Lengkap</label>
                        <textarea wire:model="address" class="w-full bg-gray-50 border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-kt-red" rows="3"></textarea>
                        @error('address') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                    </div>
                    <div class="md:col-span-2 flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-kt-red text-white text-xs font-bold rounded-xl shadow-lg shadow-red-50">Simpan Perubahan</button>
                    </div>
                </form>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex flex-col">
                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Nama Lengkap</span>
                        <span class="text-sm font-bold text-gray-800">{{ $applicant->name }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Email</span>
                        <span class="text-sm font-bold text-gray-800">{{ $applicant->email }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Telepon</span>
                        <span class="text-sm font-bold text-gray-800">{{ $applicant->phone ?? '-' }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Jenis Kelamin</span>
                        <span class="text-sm font-bold text-gray-800">{{ $applicant->gender ?? '-' }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Tanggal Lahir</span>
                        <span class="text-sm font-bold text-gray-800">{{ $applicant->birth_date ? $applicant->birth_date->format('d M Y') : '-' }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Pendidikan Terakhir</span>
                        <span class="text-sm font-bold text-gray-800">{{ $applicant->educationLevel->name ?? '-' }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Jurusan</span>
                        <span class="text-sm font-bold text-gray-800">{{ $applicant->major ?? '-' }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Provinsi</span>
                        <span class="text-sm font-bold text-gray-800">{{ $applicant->province?->name ?? '-' }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Kabupaten/Kota</span>
                        <span class="text-sm font-bold text-gray-800">{{ $applicant->city?->name ?? '-' }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Kecamatan</span>
                        <span class="text-sm font-bold text-gray-800">{{ $applicant->district?->name ?? '-' }}</span>
                    </div>
                </div>
            @endif
        </div>

        {{-- Section: Pengalaman Kerja --}}
        <div class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-gray-50 shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-3">
                    <span class="w-1.5 h-6 bg-kt-red rounded-full"></span>
                    Pengalaman Kerja
                </h3>
                <button wire:click="openExperienceForm" class="px-4 py-2 bg-kt-red text-white text-[10px] font-bold rounded-xl shadow-lg shadow-red-50">+ Tambah</button>
            </div>
            
            @if($showExperienceForm)
                <div class="mb-8 p-6 bg-gray-50 rounded-3xl border border-gray-100">
                    <form wire:submit.prevent="saveExperience" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex flex-col gap-1 md:col-span-2">
                            <label class="text-[10px] text-gray-400 font-bold uppercase tracking-widest ml-1">Nama Perusahaan</label>
                            <input type="text" wire:model="exp_company_name" class="w-full h-11 bg-white border-none rounded-xl px-4 text-sm focus:ring-2 focus:ring-kt-red shadow-sm">
                        </div>
                        <div class="flex flex-col gap-1 md:col-span-2">
                            <label class="text-[10px] text-gray-400 font-bold uppercase tracking-widest ml-1">Posisi / Jabatan</label>
                            <input type="text" wire:model="exp_position" class="w-full h-11 bg-white border-none rounded-xl px-4 text-sm focus:ring-2 focus:ring-kt-red shadow-sm">
                        </div>
                        <div class="flex flex-col gap-1">
                            <label class="text-[10px] text-gray-400 font-bold uppercase tracking-widest ml-1">Tanggal Mulai</label>
                            <input type="date" wire:model="exp_start_date" class="w-full h-11 bg-white border-none rounded-xl px-4 text-sm focus:ring-2 focus:ring-kt-red shadow-sm">
                        </div>
                        <div class="flex flex-col gap-1">
                            <label class="text-[10px] text-gray-400 font-bold uppercase tracking-widest ml-1">Tanggal Selesai</label>
                            <input type="date" wire:model="exp_end_date" class="w-full h-11 bg-white border-none rounded-xl px-4 text-sm focus:ring-2 focus:ring-kt-red shadow-sm" {{ $exp_is_current ? 'disabled' : '' }}>
                        </div>
                        <div class="md:col-span-2 flex items-center gap-2 mb-2">
                            <input type="checkbox" wire:model.live="exp_is_current" class="rounded text-kt-red focus:ring-kt-red">
                            <span class="text-xs font-bold text-gray-500">Masih Bekerja di Sini</span>
                        </div>
                        <div class="md:col-span-2 flex flex-col gap-1">
                            <label class="text-[10px] text-gray-400 font-bold uppercase tracking-widest ml-1">Deskripsi Pekerjaan</label>
                            <textarea wire:model="exp_description" class="w-full bg-white border-none rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-kt-red shadow-sm" rows="3" placeholder="Jelaskan tugas dan tanggung jawab Anda..."></textarea>
                            @error('exp_description') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                        </div>
                        <div class="md:col-span-2 flex justify-end gap-3 mt-2">
                            <button type="button" wire:click="$set('showExperienceForm', false)" class="px-6 py-2 text-gray-500 text-xs font-bold rounded-xl">Batal</button>
                            <button type="submit" class="px-6 py-2 bg-kt-red text-white text-xs font-bold rounded-xl shadow-lg shadow-red-50">Simpan</button>
                        </div>
                    </form>
                </div>
            @endif

            <div class="flex flex-col gap-4">
                @forelse($experiences as $exp)
                    <div class="flex gap-4 p-4 border border-gray-50 rounded-2xl bg-gray-50/50">
                        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shrink-0 shadow-sm">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between">
                                <h4 class="font-bold text-sm text-gray-900">{{ $exp->position }}</h4>
                                <div class="flex gap-2">
                                    <button wire:click="editExperience({{ $exp->id }})" class="text-gray-400 hover:text-kt-red"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg></button>
                                    <button wire:click="confirmDelete({{ $exp->id }}, 'experience')" class="text-gray-400 hover:text-red-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 font-medium">{{ $exp->company_name }}</p>
                            <p class="text-[10px] text-gray-400 mt-1 uppercase font-bold tracking-widest">
                                {{ $exp->start_date->format('M Y') }} - {{ $exp->is_current ? 'Sekarang' : $exp->end_date?->format('M Y') }}
                            </p>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-xs text-gray-400 py-4 italic">Belum ada data pengalaman kerja.</p>
                @endforelse
            </div>
        </div>

        {{-- Section: Pendidikan --}}
        <div class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-gray-50 shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-3">
                    <span class="w-1.5 h-6 bg-kt-red rounded-full"></span>
                    Pendidikan
                </h3>
                <button wire:click="openEducationForm" class="px-4 py-2 bg-kt-red text-white text-[10px] font-bold rounded-xl shadow-lg shadow-red-50">+ Tambah</button>
            </div>

            @if($showEducationForm)
                <div class="mb-8 p-6 bg-gray-50 rounded-3xl border border-gray-100">
                    <form wire:submit.prevent="saveEducation" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex flex-col gap-1 md:col-span-2">
                            <label class="text-[10px] text-gray-400 font-bold uppercase tracking-widest ml-1">Nama Instansi / Sekolah</label>
                            <input type="text" wire:model="edu_institution_name" class="w-full h-11 bg-white border-none rounded-xl px-4 text-sm focus:ring-2 focus:ring-kt-red shadow-sm">
                        </div>
                        <div class="flex flex-col gap-1">
                            <label class="text-[10px] text-gray-400 font-bold uppercase tracking-widest ml-1">Gelar / Tingkat</label>
                            <select wire:model="edu_degree" class="w-full h-11 bg-white border-none rounded-xl px-4 text-sm focus:ring-2 focus:ring-kt-red shadow-sm">
                                <option value="">Pilih Gelar / Tingkat</option>
                                @foreach($educationLevels as $level)
                                    <option value="{{ $level->name }}">{{ $level->name }}</option>
                                @endforeach
                            </select>
                            @error('edu_degree') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex flex-col gap-1">
                                <label class="text-[10px] text-gray-400 font-bold uppercase tracking-widest ml-1">Jurusan</label>
                                <input type="text" wire:model="edu_major" class="w-full h-11 bg-white border-none rounded-xl px-4 text-sm focus:ring-2 focus:ring-kt-red shadow-sm">
                            </div>
                            <div class="flex flex-col gap-1">
                                <label class="text-[10px] text-gray-400 font-bold uppercase tracking-widest ml-1">Nilai Akhir / IPK</label>
                                <input type="number" step="0.01" wire:model="edu_gpa" placeholder="Contoh: 3.85" class="w-full h-11 bg-white border-none rounded-xl px-4 text-sm focus:ring-2 focus:ring-kt-red shadow-sm">
                            </div>
                        </div>
                        <div class="flex flex-col gap-1">
                            <label class="text-[10px] text-gray-400 font-bold uppercase tracking-widest ml-1">Tahun Mulai</label>
                            <input type="number" wire:model="edu_start_year" class="w-full h-11 bg-white border-none rounded-xl px-4 text-sm focus:ring-2 focus:ring-kt-red shadow-sm">
                        </div>
                        <div class="flex flex-col gap-1">
                            <label class="text-[10px] text-gray-400 font-bold uppercase tracking-widest ml-1">Tahun Lulus</label>
                            <input type="number" wire:model="edu_end_year" class="w-full h-11 bg-white border-none rounded-xl px-4 text-sm focus:ring-2 focus:ring-kt-red shadow-sm">
                        </div>
                        <div class="md:col-span-2 flex justify-end gap-3 mt-2">
                            <button type="button" wire:click="$set('showEducationForm', false)" class="px-6 py-2 text-gray-500 text-xs font-bold rounded-xl">Batal</button>
                            <button type="submit" class="px-6 py-2 bg-kt-red text-white text-xs font-bold rounded-xl shadow-lg shadow-red-50">Simpan</button>
                        </div>
                    </form>
                </div>
            @endif
            
            <div class="flex flex-col gap-4">
                @forelse($educations as $edu)
                    <div class="flex gap-4 p-4 border border-gray-50 rounded-2xl bg-gray-50/50">
                        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shrink-0 shadow-sm">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/></svg>
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between">
                                <h4 class="font-bold text-sm text-gray-900">{{ $edu->degree }} {{ $edu->major }}</h4>
                                @if($edu->gpa)
                                    <p class="text-[10px] font-bold text-kt-red uppercase tracking-wider">IPK: {{ $edu->gpa }}</p>
                                @endif
                                <div class="flex gap-2">
                                    <button wire:click="editEducation({{ $edu->id }})" class="text-gray-400 hover:text-kt-red"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg></button>
                                    <button wire:click="confirmDelete({{ $edu->id }}, 'education')" class="text-gray-400 hover:text-red-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 font-medium">{{ $edu->institution_name }}</p>
                            <p class="text-[10px] text-gray-400 mt-1 uppercase font-bold tracking-widest">
                                {{ $edu->start_year }} - {{ $edu->end_year ?? 'Sekarang' }}
                            </p>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-xs text-gray-400 py-4 italic">Belum ada data riwayat pendidikan.</p>
                @endforelse
            </div>
        </div>

        {{-- Section: Sertifikasi --}}
        <div class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-gray-50 shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-3">
                    <span class="w-1.5 h-6 bg-kt-red rounded-full"></span>
                    Sertifikasi
                </h3>
                <button wire:click="openCertificationForm" class="px-4 py-2 bg-kt-red text-white text-[10px] font-bold rounded-xl shadow-lg shadow-red-50">+ Tambah</button>
            </div>
            
            @if($showCertificationForm)
                <div class="mb-8 p-6 bg-gray-50 rounded-3xl border border-gray-100">
                    <form wire:submit.prevent="saveCertification" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex flex-col gap-1 md:col-span-2">
                            <label class="text-[10px] text-gray-400 font-bold uppercase tracking-widest ml-1">Nama Sertifikasi</label>
                            <input type="text" wire:model="cert_name" class="w-full h-11 bg-white border-none rounded-xl px-4 text-sm focus:ring-2 focus:ring-kt-red shadow-sm">
                        </div>
                        <div class="flex flex-col gap-1 md:col-span-2">
                            <label class="text-[10px] text-gray-400 font-bold uppercase tracking-widest ml-1">Organisasi Penerbit</label>
                            <input type="text" wire:model="cert_organization" class="w-full h-11 bg-white border-none rounded-xl px-4 text-sm focus:ring-2 focus:ring-kt-red shadow-sm">
                        </div>
                        <div class="flex flex-col gap-1">
                            <label class="text-[10px] text-gray-400 font-bold uppercase tracking-widest ml-1">Tanggal Terbit</label>
                            <input type="date" wire:model="cert_issue_date" class="w-full h-11 bg-white border-none rounded-xl px-4 text-sm focus:ring-2 focus:ring-kt-red shadow-sm">
                        </div>
                        <div class="flex flex-col gap-1">
                            <label class="text-[10px] text-gray-400 font-bold uppercase tracking-widest ml-1">URL Kredensial (Opsional)</label>
                            <input type="url" wire:model="cert_credential_url" placeholder="https://..." class="w-full h-11 bg-white border-none rounded-xl px-4 text-sm focus:ring-2 focus:ring-kt-red shadow-sm">
                        </div>
                        <div class="md:col-span-2 flex justify-end gap-3 mt-2">
                            <button type="button" wire:click="$set('showCertificationForm', false)" class="px-6 py-2 text-gray-500 text-xs font-bold rounded-xl">Batal</button>
                            <button type="submit" class="px-6 py-2 bg-kt-red text-white text-xs font-bold rounded-xl shadow-lg shadow-red-50">Simpan</button>
                        </div>
                    </form>
                </div>
            @endif

            <div class="flex flex-col gap-4">
                @forelse($certifications as $cert)
                    <div class="flex gap-4 p-4 border border-gray-50 rounded-2xl bg-gray-50/50">
                        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shrink-0 shadow-sm">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between">
                                <h4 class="font-bold text-sm text-gray-900">{{ $cert->certification_name }}</h4>
                                <div class="flex gap-2">
                                    <button wire:click="editCertification({{ $cert->id }})" class="text-gray-400 hover:text-kt-red"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg></button>
                                    <button wire:click="confirmDelete({{ $cert->id }}, 'certification')" class="text-gray-400 hover:text-red-600"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 font-medium">{{ $cert->issuing_organization }}</p>
                            @if($cert->issue_date)
                                <p class="text-[10px] text-gray-400 mt-1 uppercase font-bold tracking-widest">Diterbitkan: {{ $cert->issue_date->format('M Y') }}</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-center text-xs text-gray-400 py-4 italic">Belum ada data sertifikasi.</p>
                @endforelse
            </div>
        </div>

        {{-- Section: Keahlian --}}
        <div class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-gray-50 shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-3">
                    <span class="w-1.5 h-6 bg-kt-red rounded-full"></span>
                    Keahlian
                </h3>
                <button wire:click="toggleSkillForm" class="flex items-center gap-2 px-4 py-2 bg-red-50 hover:bg-kt-red text-kt-red hover:text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition-all duration-300 shadow-sm shadow-red-50/50">
                    @if($showSkillForm)
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        Batal
                    @else
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Ubah
                    @endif
                </button>
            </div>

            @if($showSkillForm)
                <div class="mb-6 p-6 bg-gray-50 rounded-3xl border border-gray-100">
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 max-h-60 overflow-y-auto p-2 scrollbar-hide">
                        @foreach($availableSkills as $skill)
                            <label class="flex items-center gap-2 p-3 bg-white rounded-xl border {{ in_array($skill->id, $selectedSkills) ? 'border-kt-red' : 'border-gray-100' }} cursor-pointer hover:border-kt-red transition-all">
                                <input type="checkbox" wire:model="selectedSkills" value="{{ $skill->id }}" class="rounded text-kt-red focus:ring-kt-red">
                                <span class="text-xs font-bold text-gray-700">{{ $skill->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    <div class="flex justify-end mt-4">
                        <button wire:click="saveSkills" class="px-6 py-2 bg-kt-red text-white text-xs font-bold rounded-xl shadow-lg shadow-red-50">Simpan Keahlian</button>
                    </div>
                </div>
            @endif

            <div class="flex flex-wrap gap-2">
                @forelse($profileSkills as $skill)
                    <span class="px-4 py-2 bg-gray-50 text-gray-700 text-xs font-bold rounded-xl flex items-center gap-2">
                        {{ $skill->name }} <button wire:click="removeSkill({{ $skill->id }})" class="text-gray-400 hover:text-kt-red">×</button>
                    </span>
                @empty
                    <p class="text-xs text-gray-400 italic">Belum menambahkan keahlian.</p>
                @endforelse
                @if(!$showSkillForm)
                    <button wire:click="toggleSkillForm" class="px-4 py-2 border border-dashed border-gray-200 text-gray-400 text-xs font-bold rounded-xl hover:border-kt-red hover:text-kt-red transition-all">+ Tambah Skill</button>
                @endif
            </div>
        </div>

        {{-- Section: Dokumen CV --}}
        <div class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-gray-50 shadow-sm">
            <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center gap-3">
                <span class="w-1.5 h-6 bg-kt-red rounded-full"></span>
                Dokumen CV
            </h3>
            
            @if($applicant->cv_file)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl border border-gray-100 mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center border border-gray-100">
                            <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 24 24"><path d="M11.363 2c4.155 0 2.637 6 2.637 6s6-1.518 6 2.638v11.362c0 .552-.448 1-1 1H5c-.552 0-1-.448-1-1V3c0-.552.448-1 1-1h6.363zM12 2l8 8h-8V2z"/></svg>
                        </div>
                        <div class="flex-1 overflow-hidden">
                            <p class="text-xs font-bold text-gray-900 truncate">CV_{{ str_replace(' ', '_', $applicant->name) }}.pdf</p>
                            <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">Dokumen Terlampir</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ Storage::url($applicant->cv_file) }}" target="_blank" class="p-2 text-gray-400 hover:text-kt-red"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></a>
                        <button wire:click="confirmDelete(0, 'cv')" class="p-2 text-gray-400 hover:text-red-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                    </div>
                </div>
            @endif

            <div x-data="{ uploading: false, progress: 0 }" 
                 x-on:livewire-upload-start="uploading = true" 
                 x-on:livewire-upload-finish="uploading = false" 
                 x-on:livewire-upload-error="uploading = false" 
                 x-on:livewire-upload-progress="progress = $event.detail.progress"
                 class="relative group">
                <input type="file" wire:model="cvUpload" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                <div class="border-2 border-dashed border-gray-100 rounded-3xl p-8 flex flex-col items-center justify-center text-center group-hover:border-kt-red transition-colors">
                    <div class="w-12 h-12 bg-red-50 text-kt-red rounded-full flex items-center justify-center mb-4">
                        <svg wire:loading.remove wire:target="cvUpload" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                        <svg wire:loading wire:target="cvUpload" class="animate-spin h-6 w-6 text-kt-red" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </div>
                    <p class="text-sm font-bold text-gray-900">{{ $applicant->cv_file ? 'Ganti File CV' : 'Unggah File CV' }}</p>
                    <p class="text-xs text-gray-400 mt-1">Klik atau drag & drop file PDF Anda di sini</p>
                    
                    {{-- Progress Bar CV --}}
                    <div x-show="uploading" class="w-full mt-4">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-[10px] font-black text-kt-red uppercase tracking-widest">Mengirim CV...</span>
                            <span class="text-[10px] font-black text-kt-red" x-text="progress + '%'"></span>
                        </div>
                        <div class="w-full bg-red-50 rounded-full h-1 overflow-hidden">
                            <div class="bg-kt-red h-full rounded-full transition-all duration-300" :style="'width: ' + progress + '%'"></div>
                        </div>
                    </div>

                    <p class="text-[10px] text-gray-300 mt-4 uppercase tracking-widest font-bold">PDF, DOCX (Maks 5MB)</p>
                    @error('cvUpload') <span class="text-red-500 text-[10px] font-bold mt-2">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        {{-- Section: Keamanan Akun --}}
        <div class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-gray-50 shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-3">
                    <span class="w-1.5 h-6 bg-kt-red rounded-full"></span>
                    Keamanan Akun
                </h3>
                <button wire:click="$toggle('showPasswordForm')" class="flex items-center gap-2 px-4 py-2 bg-red-50 hover:bg-kt-red text-kt-red hover:text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition-all duration-300 shadow-sm shadow-red-50/50">
                    @if($showPasswordForm)
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        Batal
                    @else
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                        Ganti Password
                    @endif
                </button>
            </div>

            @if($showPasswordForm)
                <form wire:submit.prevent="updatePassword" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="flex flex-col gap-1">
                        <label class="text-[10px] text-gray-400 font-bold uppercase tracking-widest ml-1">Password Saat Ini</label>
                        <input type="password" wire:model="current_password" class="w-full h-11 bg-gray-50 border-none rounded-xl px-4 text-sm focus:ring-2 focus:ring-kt-red shadow-sm">
                        @error('current_password') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-[10px] text-gray-400 font-bold uppercase tracking-widest ml-1">Password Baru</label>
                        <input type="password" wire:model="new_password" class="w-full h-11 bg-gray-50 border-none rounded-xl px-4 text-sm focus:ring-2 focus:ring-kt-red shadow-sm">
                        @error('new_password') <span class="text-red-500 text-[10px] font-bold">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-[10px] text-gray-400 font-bold uppercase tracking-widest ml-1">Konfirmasi Password</label>
                        <input type="password" wire:model="new_password_confirmation" class="w-full h-11 bg-gray-50 border-none rounded-xl px-4 text-sm focus:ring-2 focus:ring-kt-red shadow-sm">
                    </div>
                    <div class="md:col-span-3 flex justify-end mt-2">
                        <button type="submit" class="px-6 py-2 bg-kt-red text-white text-xs font-bold rounded-xl shadow-lg shadow-red-50">Perbarui Password</button>
                    </div>
                </form>
            @else
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl border border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center border border-gray-100">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-900">Kata Sandi</p>
                            <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">Ganti password secara berkala untuk keamanan</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Section: Hapus Akun (Pelamar Only) --}}
        @if(auth()->user()->role === 'pelamar')
            <div class="mt-12 bg-red-50 p-8 rounded-[2.5rem] border border-red-100 shadow-sm shadow-red-50/50">
                <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="flex-1 text-center md:text-left">
                        <h3 class="text-lg font-black text-red-900 flex items-center justify-center md:justify-start gap-3 mb-2">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            Zona Berbahaya
                        </h3>
                        <p class="text-sm text-red-700/70 font-medium">
                            Menghapus akun akan menghapus seluruh data profil, riwayat pendidikan, pengalaman kerja, sertifikasi, dan seluruh lamaran kerja Anda secara permanen.
                        </p>
                    </div>
                    <button wire:click="confirmDelete(0, 'account')" class="whitespace-nowrap px-8 py-4 bg-kt-red hover:opacity-90 text-white text-sm font-black uppercase tracking-widest rounded-2xl transition-all duration-300 shadow-lg shadow-red-200 active:scale-95">
                        HAPUS PROFILE SAYA
                    </button>
                </div>
            </div>
        @endif
    </div>

    {{-- Modal Konfirmasi Hapus --}}
    <div x-data="{ open: false }" 
         x-on:open-delete-modal.window="open = true" 
         x-on:close-delete-modal.window="open = false"
         x-show="open" 
         class="fixed inset-0 z-[100] overflow-y-auto" 
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-gray-900/40" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative z-10 inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-[2.5rem] shadow-2xl sm:my-8 sm:align-middle sm:max-w-sm sm:w-full sm:p-8">
                <div>
                    <div class="flex items-center justify-center w-16 h-16 mx-auto bg-red-50 rounded-full">
                        <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </div>
                    <div class="mt-4 text-center">
                        <h3 class="text-xl font-black text-gray-900 tracking-tighter">
                            {{ $confirmDeletionType === 'account' ? 'Hapus Akun Permanen?' : 'Hapus Data?' }}
                        </h3>
                        <p class="mt-2 text-sm text-gray-500">
                            {{ $confirmDeletionType === 'account' 
                                ? 'Tindakan ini akan menghapus akun Anda dan seluruh data terkait secara permanen. Anda tidak akan bisa masuk kembali menggunakan akun ini.' 
                                : 'Tindakan ini tidak dapat dibatalkan. Apakah Anda yakin ingin menghapus data ini dari profil Anda?' }}
                        </p>
                    </div>
                </div>
                <div class="mt-8 flex flex-col gap-3">
                    <button wire:click="deleteConfirmed" class="w-full py-4 text-sm font-bold text-white bg-kt-red rounded-2xl shadow-lg shadow-red-100 active:scale-95 transition-transform">Ya, Hapus Sekarang</button>
                    <button x-on:click="open = false" class="w-full py-4 text-sm font-bold text-gray-500 bg-gray-50 rounded-2xl hover:bg-gray-100 active:scale-95 transition-transform">Batal</button>
                </div>
            </div>
        </div>
    </div>
</div>
