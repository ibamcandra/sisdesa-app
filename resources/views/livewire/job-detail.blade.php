<div class="max-w-7xl mx-auto md:px-4 lg:px-8 py-6 md:py-12 pb-32">
    <!-- Breadcrumb & Back -->
    <div class="px-4 mb-6 flex items-center justify-between">
        <a href="/job" class="flex items-center gap-2 text-sm font-bold text-gray-400 hover:text-kt-red transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Daftar
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 flex flex-col gap-6">
            <!-- Job Header Card -->
            <div class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-gray-50 shadow-sm">
                <div class="flex flex-col md:flex-row gap-6 items-start">
                    @php
                        $company = \App\Models\CompanyProfile::first();
                    @endphp
                    @if($company && $company->logo)
                        <div class="w-20 h-20 bg-white rounded-[1.5rem] flex items-center justify-center border border-gray-100 shrink-0 shadow-sm overflow-hidden">
                            <img src="{{ Storage::url($company->logo) }}" alt="Logo" class="w-full h-full object-contain p-2">
                        </div>
                    @else
                        <div class="w-20 h-20 bg-kt-yellow rounded-[1.5rem] flex items-center justify-center border-2 border-kt-red shrink-0">
                            <span class="text-kt-red font-black text-xl">KT</span>
                        </div>
                    @endif
                    <div class="flex-1">
                        <div class="flex flex-wrap gap-2 mb-3">
                            <span class="px-3 py-1 bg-red-50 text-kt-red text-[10px] font-bold rounded-full uppercase">{{ $vacancy->jobCategory?->name ?? 'Lowongan' }}</span>
                        </div>
                        <h2 class="text-2xl md:text-3xl font-black text-gray-900 leading-tight mb-2">{{ $vacancy->title }}</h2>
                        <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-sm text-gray-500 font-medium">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-kt-red" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                {{ $vacancy->user?->name ?? 'Administrator' }}
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-kt-red" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                {{ $vacancy->branch?->name ?? 'Purwakarta' }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8 pt-8 border-t border-gray-50">
                    <div class="flex flex-col">
                        <span class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1">Gaji</span>
                        <span class="text-sm font-bold text-gray-900">Kompetitif</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1">Tipe</span>
                        <span class="text-sm font-bold text-gray-900">{{ $vacancy->type }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1">Kategori</span>
                        <span class="text-sm font-bold text-gray-900">{{ $vacancy->jobCategory?->name ?? '-' }}</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[10px] text-gray-400 uppercase font-bold tracking-widest mb-1">Batas</span>
                        <span class="text-sm font-bold {{ ($vacancy->close_date && $vacancy->close_date->isPast()) ? 'text-red-600' : 'text-gray-900' }}">
                            {{ $vacancy->close_date ? $vacancy->close_date->format('d M Y') : '-' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="bg-white p-6 md:p-10 rounded-[2.5rem] border border-gray-50 shadow-sm">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                    <span class="w-1.5 h-6 bg-kt-red rounded-full"></span>
                    Deskripsi Pekerjaan
                </h3>
                <div class="prose prose-sm max-w-none text-gray-600 leading-relaxed">
                    {!! $vacancy->description !!}
                </div>
                <br>
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                    <span class="w-1.5 h-6 bg-kt-red rounded-full"></span>
                    Kualifikasi
                </h3>
                <div class="prose prose-sm max-w-none text-gray-600 leading-relaxed">
                    {!! $vacancy->requirement !!}
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="flex flex-col gap-6">
            <!-- Skills Card -->
            <div class="bg-white p-8 rounded-[2.5rem] border border-gray-50 shadow-sm">
                <h3 class="font-bold text-gray-900 mb-6">Keahlian yang Dibutuhkan</h3>
                <div class="flex flex-wrap gap-2">
                    @forelse($vacancy->skills as $skill)
                        <span class="px-4 py-2 bg-gray-50 text-gray-700 text-xs font-bold rounded-xl">{{ $skill->name }}</span>
                    @empty
                        <p class="text-xs text-gray-400">Tidak ada keahlian khusus.</p>
                    @endforelse
                </div>
            </div>

            <!-- Action Card (Desktop Only) -->
            <div class="hidden md:block bg-kt-red p-8 rounded-[2.5rem] text-white shadow-xl shadow-red-100">
                <h3 class="font-bold text-lg mb-2">Tertarik dengan posisi ini?</h3>
                <p class="text-white/70 text-sm mb-6">Klik tombol di bawah untuk mengirimkan lamaran terbaik Anda.</p>
                @if($vacancy->close_date && $vacancy->close_date->isPast())
                    <button class="w-full py-4 bg-white/20 text-white font-bold rounded-2xl cursor-not-allowed" disabled>Lowongan Ditutup</button>
                @elseif(auth()->check() && auth()->user()->role === 'pelamar')
                    <button wire:click="apply" wire:loading.attr="disabled" class="w-full py-4 bg-white text-kt-red font-bold rounded-2xl hover:bg-gray-50 transition-colors flex items-center justify-center gap-2">
                        <span wire:loading.remove>Lamar Sekarang</span>
                        <span wire:loading>Memproses...</span>
                    </button>
                @else
                    <a href="/login" class="block w-full py-4 bg-white text-kt-red font-bold rounded-2xl hover:bg-gray-50 transition-colors text-center">Login untuk Melamar</a>
                @endif
            </div>
        </div>
    </div>

    <!-- Sticky Bottom Apply (Mobile Only) -->
    <div class="md:hidden fixed bottom-24 left-4 right-4 z-40">
        @if($vacancy->close_date && $vacancy->close_date->isPast())
            <button class="w-full py-4 bg-gray-400 text-white font-bold rounded-2xl shadow-2xl border-2 border-white/20 cursor-not-allowed" disabled>
                Lowongan Ditutup
            </button>
        @elseif(auth()->check() && auth()->user()->role === 'pelamar')
            <button wire:click="apply" wire:loading.attr="disabled" class="w-full py-4 bg-kt-red text-white font-bold rounded-2xl shadow-2xl shadow-red-200 border-2 border-white/20 active:scale-95 transition-transform flex items-center justify-center gap-2">
                <span wire:loading.remove>Lamar Sekarang</span>
                <span wire:loading>Memproses...</span>
            </button>
        @else
            <a href="/login" class="block w-full py-4 bg-kt-red text-white font-bold rounded-2xl shadow-2xl shadow-red-200 border-2 border-white/20 text-center active:scale-95 transition-transform">
                Login untuk Melamar
            </a>
        @endif
    </div>
</div>
