<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CV - {{ $applicant->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        @media print {
            .no-print { display: none; }
            body { background: white; padding: 0; }
            .cv-container { box-shadow: none; border: none; width: 100%; max-width: 100%; margin: 0; }
        }
    </style>
</head>
<body class="bg-gray-100 py-10 px-4">
    <!-- Print Button -->
    <div class="max-w-4xl mx-auto mb-6 flex justify-end no-print">
        <button onclick="window.print()" class="px-6 py-2 bg-indigo-600 text-white font-bold rounded-xl shadow-lg hover:bg-indigo-700 transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2-2v4h10z"/></svg>
            Cetak CV
        </button>
    </div>

    <div class="max-w-4xl mx-auto bg-white shadow-2xl rounded-[2rem] overflow-hidden cv-container flex flex-col md:flex-row min-h-[1100px]">
        
        <!-- Sidebar -->
        <div class="w-full md:w-1/3 bg-slate-900 text-white p-8">
            <div class="flex flex-col items-center text-center mb-10">
                <div class="w-40 h-40 rounded-3xl border-4 border-slate-800 overflow-hidden mb-6 shadow-2xl">
                    <img src="{{ $applicant->avatar_url }}" alt="Avatar" class="w-full h-full object-cover">
                </div>
                <h1 class="text-2xl font-black tracking-tighter leading-tight">{{ $applicant->name }}</h1>
                <p class="text-indigo-400 font-bold uppercase text-[10px] tracking-[0.2em] mt-2">
                    {{ $applicant->educationLevel->name ?? 'Pelamar' }}
                </p>
            </div>

            <!-- Contact -->
            <div class="space-y-6 mb-10">
                <h3 class="text-xs font-black uppercase tracking-widest text-slate-500 border-b border-slate-800 pb-2">Kontak</h3>
                <div class="flex items-start gap-3">
                    <div class="p-2 bg-slate-800 rounded-lg text-indigo-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div class="text-sm">
                        <p class="text-slate-400 text-[10px] font-bold uppercase">Email</p>
                        <p class="font-medium truncate">{{ $applicant->email }}</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="p-2 bg-slate-800 rounded-lg text-indigo-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </div>
                    <div class="text-sm">
                        <p class="text-slate-400 text-[10px] font-bold uppercase">Telepon</p>
                        <p class="font-medium">{{ $applicant->phone ?? '-' }}</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="p-2 bg-slate-800 rounded-lg text-indigo-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div class="text-sm">
                        <p class="text-slate-400 text-[10px] font-bold uppercase">Lokasi</p>
                        <p class="font-medium">{{ $applicant->city->name ?? '-' }}, {{ $applicant->province->name ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Skills -->
            <div class="space-y-4">
                <h3 class="text-xs font-black uppercase tracking-widest text-slate-500 border-b border-slate-800 pb-2">Keahlian</h3>
                <div class="flex flex-wrap gap-2">
                    @forelse($applicant->skills as $skill)
                        <span class="px-3 py-1 bg-slate-800 text-[10px] font-bold rounded-lg">{{ $skill->name }}</span>
                    @empty
                        <p class="text-slate-500 text-xs italic">Belum mengisi keahlian</p>
                    @endforelse
                </div>
            </div>

            <!-- Info -->
            <div class="mt-12 p-4 bg-indigo-500/10 rounded-2xl border border-indigo-500/20">
                <p class="text-[10px] text-indigo-300 leading-relaxed italic">
                    "Data ini dihasilkan secara otomatis dari sistem Karang Taruna Desa Campaka sebagai referensi resmi proses rekrutmen."
                </p>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-10 bg-white">
            <!-- Header/Summary -->
            <div class="mb-12">
                <h2 class="text-sm font-black text-indigo-600 uppercase tracking-widest mb-4">Ringkasan Profil</h2>
                <div class="grid grid-cols-2 gap-6 text-sm">
                    <div>
                        <p class="text-gray-400 text-[10px] font-bold uppercase">Jenis Kelamin</p>
                        <p class="font-bold text-gray-800">{{ $applicant->gender ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-[10px] font-bold uppercase">Usia</p>
                        <p class="font-bold text-gray-800">{{ $applicant->birth_date ? \Carbon\Carbon::parse($applicant->birth_date)->age . ' Tahun' : '-' }}</p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-gray-400 text-[10px] font-bold uppercase">Alamat Lengkap</p>
                        <p class="font-medium text-gray-700 leading-relaxed">{{ $applicant->address ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Work Experience -->
            <div class="mb-12">
                <h2 class="text-sm font-black text-indigo-600 uppercase tracking-widest mb-6 border-b pb-2">Pengalaman Kerja</h2>
                <div class="space-y-8">
                    @forelse($applicant->experiences as $exp)
                        <div class="relative pl-6 border-l-2 border-indigo-100">
                            <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full bg-indigo-600 border-4 border-white"></div>
                            <div class="flex justify-between items-start mb-1">
                                <h4 class="font-bold text-gray-900">{{ $exp->position }}</h4>
                                <span class="text-[10px] font-bold text-indigo-500 bg-indigo-50 px-2 py-1 rounded-md">
                                    {{ $exp->start_date->format('M Y') }} - {{ $exp->is_current ? 'Sekarang' : $exp->end_date?->format('M Y') }}
                                </span>
                            </div>
                            <p class="text-sm font-bold text-gray-500 mb-2">{{ $exp->company_name }}</p>
                            <p class="text-xs text-gray-500 leading-relaxed">{{ $exp->description }}</p>
                        </div>
                    @empty
                        <p class="text-gray-400 text-xs italic">Tidak ada riwayat pengalaman kerja.</p>
                    @endforelse
                </div>
            </div>

            <!-- Education -->
            <div class="mb-12">
                <h2 class="text-sm font-black text-indigo-600 uppercase tracking-widest mb-6 border-b pb-2">Pendidikan</h2>
                <div class="space-y-6">
                    @forelse($applicant->educations as $edu)
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-bold text-gray-900">{{ $edu->institution_name }}</h4>
                                <p class="text-xs font-medium text-gray-500">{{ $edu->degree }} {{ $edu->major ? ' - ' . $edu->major : '' }}</p>
                            </div>
                            <span class="text-[10px] font-bold text-gray-400">{{ $edu->start_year }} - {{ $edu->end_year ?? 'Sekarang' }}</span>
                        </div>
                    @empty
                        <!-- Fallback to basic profile data -->
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-bold text-gray-900">{{ $applicant->educationLevel->name ?? '-' }}</h4>
                                <p class="text-xs font-medium text-gray-500">{{ $applicant->major ?? '-' }}</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Certifications -->
            <div>
                <h2 class="text-sm font-black text-indigo-600 uppercase tracking-widest mb-6 border-b pb-2">Sertifikasi & Pelatihan</h2>
                <div class="grid grid-cols-1 gap-4">
                    @forelse($applicant->certifications as $cert)
                        <div class="p-4 bg-gray-50 rounded-2xl flex justify-between items-center">
                            <div>
                                <h4 class="text-sm font-bold text-gray-900">{{ $cert->certification_name }}</h4>
                                <p class="text-[10px] text-gray-400 font-bold uppercase">{{ $cert->issuing_organization }}</p>
                            </div>
                            @if($cert->credential_url)
                                <a href="{{ $cert->credential_url }}" target="_blank" class="text-indigo-600"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg></a>
                            @endif
                        </div>
                    @empty
                        <p class="text-gray-400 text-xs italic">Belum ada sertifikasi.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto mt-8 text-center no-print">
        <p class="text-gray-400 text-xs font-medium">Sistem Rekrutmen &copy; 2026 Karang Taruna Desa Campaka</p>
    </div>
</body>
</html>
