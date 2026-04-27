<div class="max-w-7xl mx-auto md:px-4 lg:px-8">
    @php
        $company = \App\Models\CompanyProfile::first();
    @endphp
    <section class="px-4 py-8 bg-white md:bg-transparent md:py-16 md:flex md:items-center md:justify-between gap-12">
        <div class="flex flex-col gap-4 md:max-w-xl">
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-red-50 text-kt-red rounded-full w-fit">
                <span class="animate-pulse w-2 h-2 bg-kt-red rounded-full"></span>
                <span class="text-[10px] font-bold uppercase tracking-wider">Karang Taruna Desa Campaka</span>
            </div>
            <h1 class="text-3xl md:text-5xl font-extrabold text-gray-900 leading-tight">
                Website Informasi <br class="hidden md:block"/> <span class="text-kt-red italic underline decoration-kt-yellow decoration-4">Lowongan Kerja</span> dan Kegiatan.
            </h1>
            <p class="text-gray-500 text-sm md:text-lg">Guyub Rukun membangun Desa Campaka yang lebih maju dan berdaya saing.</p>
            
            {{-- Registrasi untuk Mobile --}}
            @guest
                <div class="mt-6 flex flex-col gap-3 md:hidden">
                    <a href="/register" class="h-14 bg-kt-red text-white font-bold rounded-2xl shadow-lg shadow-red-200 flex items-center justify-center gap-2 active:scale-95 transition-transform">
                        Daftar Sebagai Pelamar
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    </a>
                    <p class="text-[10px] text-gray-400 text-center uppercase tracking-widest font-bold">Gratis & Cepat</p>
                </div>
            @endguest
        </div>
        
        <div class="mt-8 md:mt-0 flex flex-col gap-3 w-full md:max-w-md bg-white p-6 md:p-8 rounded-[2rem] md:shadow-2xl md:shadow-gray-200/50">
            <h4 class="hidden md:block font-bold text-gray-900 mb-2">Cari Pekerjaan Impian</h4>
            <div class="relative">
                <input type="text" placeholder="Cari judul lowongan..." class="w-full h-14 bg-gray-100 border-none rounded-2xl px-6 text-sm focus:ring-2 focus:ring-kt-red transition-all">
            </div>
            <div class="hidden md:grid grid-cols-2 gap-3">
                <select class="h-14 bg-gray-100 border-none rounded-2xl px-4 text-sm focus:ring-2 focus:ring-kt-red">
                    <option value="">Semua Lokasi</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->name }}">{{ $branch->name }}</option>
                    @endforeach
                </select>
                <select class="h-14 bg-gray-100 border-none rounded-2xl px-4 text-sm focus:ring-2 focus:ring-kt-red">
                    <option value="">Semua Tipe</option>
                    <option value="Full-time">Full-time</option>
                    <option value="Part-time">Part-time</option>
                    <option value="Kontrak">Kontrak</option>
                    <option value="Magang">Magang</option>
                </select>
            </div>
            <a href="/job" class="h-14 bg-kt-red text-white font-bold rounded-2xl shadow-lg shadow-red-200 flex items-center justify-center gap-2 active:scale-95 transition-transform hover:bg-red-700">
                Cari Sekarang
            </a>
        </div>
    </section>

    <section class="px-4 py-8">
        <div class="flex items-center justify-between mb-6">
            <div class="flex flex-col">
                <h3 class="font-bold text-gray-900 text-lg">Kabar Terbaru</h3>
                <p class="text-xs text-gray-400">Info & Kegiatan Karang Taruna</p>
            </div>
            <a href="/kabar" class="text-xs font-bold text-kt-red flex items-center gap-1 group">
                Lihat Semua 
                <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
        <div class="flex gap-6 overflow-x-auto md:grid md:grid-cols-3 pb-8 scrollbar-hide px-1">
            @forelse($posts as $post)
            <a href="/kabar/{{ $post->slug }}" class="flex-none w-72 md:w-full bg-white border border-gray-100 rounded-[2.5rem] overflow-hidden shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all group">
                <div class="h-48 bg-gray-50 relative overflow-hidden">
                    <div class="absolute top-4 left-4 z-10 px-3 py-1 bg-white/90 backdrop-blur-md text-kt-red text-[8px] font-bold rounded-full uppercase tracking-wider shadow-sm">{{ $post->category ?? 'Umum' }}</div>
                    @if($post->thumbnail)
                        <img src="{{ Storage::url($post->thumbnail) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" alt="{{ $post->title }}" loading="lazy">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-kt-yellow/20 text-kt-red/20 font-black text-2xl italic">KT</div>
                    @endif
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-2 text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-3">
                        <span>{{ $post->published_at?->format('d M Y') ?? $post->created_at->format('d M Y') }}</span>
                    </div>
                    <h4 class="font-bold text-gray-900 group-hover:text-kt-red transition-colors line-clamp-2 leading-snug">{{ $post->title }}</h4>
                </div>
            </a>
            @empty
                <p class="text-gray-400 text-sm">Belum ada kabar terbaru.</p>
            @endforelse
        </div>
    </section>

    <!-- Ad Slot: Home Middle -->
    <div class="px-4 md:px-0">
        <x-ad-slot location="home_middle" />
    </div>

    <section class="px-4 py-8">
        <div class="flex items-center justify-between mb-6">
            <div class="flex flex-col">
                <h3 class="font-bold text-gray-900 text-lg">Lowongan Terbaru</h3>
                <p class="text-xs text-gray-400">Temukan pekerjaan yang cocok untukmu</p>
            </div>
            <span class="px-3 py-1 bg-gray-100 text-gray-600 text-[10px] font-bold rounded-full">{{ $vacancies->count() }} Loker Tersedia</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($vacancies as $vacancy)
            <div class="bg-white p-6 rounded-[2.5rem] border border-gray-50 shadow-sm flex flex-col justify-between hover:shadow-xl hover:-translate-y-1 transition-all group">
                <div>
                    <div class="flex justify-between items-start mb-6">
                        @if($company && $company->logo)
                            <div class="w-16 h-16 bg-white rounded-[1.5rem] flex items-center justify-center border border-gray-100 shadow-sm overflow-hidden">
                                <img src="{{ Storage::url($company->logo) }}" alt="Logo" class="w-full h-full object-contain p-2" loading="lazy">
                            </div>
                        @else
                            <div class="w-16 h-16 bg-kt-yellow rounded-[1.5rem] flex items-center justify-center border-2 border-kt-red">
                                <span class="text-kt-red font-black text-lg">KT</span>
                            </div>
                        @endif
                        <span class="px-3 py-1 bg-red-50 text-kt-red text-[10px] font-bold rounded-full">Aktif</span>
                    </div>
                    
                    <h4 class="font-bold text-gray-900 text-lg group-hover:text-kt-red transition-colors mb-1">{{ $vacancy->title }}</h4>
                    <p class="text-xs text-gray-500 font-medium mb-4">{{ $vacancy->branch?->name ?? 'Purwakarta' }}</p>
                    
                    <div class="flex flex-wrap gap-2 mb-6">
                        <span class="px-3 py-1 bg-gray-50 text-gray-500 text-[10px] font-bold rounded-lg">{{ $vacancy->type }}</span>
                        <span class="px-3 py-1 bg-gray-50 text-gray-500 text-[10px] font-bold rounded-lg">PostBy {{ $vacancy->user?->name ?? 'Administrator' }}</span>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-6 border-t border-gray-50">
                    <div class="flex flex-col">
                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Batas Akhir</span>
                        <span class="text-xs font-bold text-gray-700">{{ $vacancy->close_date?->format('d M Y') ?? '-' }}</span>
                    </div>
                    <a href="/job/{{ $vacancy->slug }}" class="px-8 py-3 bg-kt-red text-white text-xs font-bold rounded-2xl shadow-lg shadow-red-50 hover:bg-red-700 transition-all active:scale-95">Detail</a>
                </div>
            </div>
            @endforeach
        </div>
    </section>
</div>
