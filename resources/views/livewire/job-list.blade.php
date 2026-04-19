<div class="max-w-7xl mx-auto md:px-4 lg:px-8 py-8 md:py-12">
    @php
        $company = \App\Models\CompanyProfile::first();
    @endphp
    <section class="px-4 mb-8">
        <div class="flex flex-col gap-6">
            <div>
                <h2 class="text-2xl md:text-3xl font-extrabold text-gray-900">Cari Pekerjaan</h2>
                <p class="text-sm text-gray-500 mt-1">Temukan peluang karir terbaik di wilayah Desa Campaka</p>
            </div>
            
            <div class="flex flex-col md:flex-row gap-3">
                <div class="relative flex-1">
                    <input type="text" wire:model.live="search" placeholder="Cari judul atau keahlian..." class="w-full h-14 bg-white border border-gray-100 rounded-2xl px-6 text-sm shadow-sm focus:ring-2 focus:ring-kt-red focus:border-transparent transition-all">
                </div>
                <div class="flex gap-3">
                    <select wire:model.live="type" class="h-14 bg-white border border-gray-100 rounded-2xl px-4 text-sm shadow-sm focus:ring-2 focus:ring-kt-red">
                        <option value="">Semua Tipe</option>
                        <option value="Full-time">Full-time</option>
                        <option value="Part-time">Part-time</option>
                        <option value="Kontrak">Kontrak</option>
                        <option value="Magang">Magang</option>
                    </select>
                </div>
            </div>
        </div>
    </section>

    <section class="px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($vacancies as $vacancy)
            <div class="bg-white p-6 rounded-[2rem] border border-gray-50 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all group flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-start mb-4">
                        @if($company && $company->logo)
                            <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center border border-gray-100 shadow-sm overflow-hidden">
                                <img src="{{ Storage::url($company->logo) }}" alt="Logo" class="w-full h-full object-contain p-2">
                            </div>
                        @else
                            <div class="w-14 h-14 bg-kt-yellow rounded-2xl flex items-center justify-center border-2 border-kt-red">
                                <span class="text-kt-red font-bold text-xs">KT</span>
                            </div>
                        @endif
                        <span class="px-3 py-1 bg-green-50 text-green-600 text-[10px] font-bold rounded-full">Aktif</span>
                    </div>
                    
                    <h4 class="font-bold text-gray-900 text-lg group-hover:text-kt-red transition-colors mb-1">{{ $vacancy->title }}</h4>
                    <p class="text-xs text-gray-500 font-medium mb-4">{{ $vacancy->branch?->name ?? 'Purwakarta' }}</p>
                    
                    <div class="flex flex-wrap gap-2 mb-6">
                        <span class="px-3 py-1 bg-gray-50 text-gray-500 text-[10px] font-bold rounded-lg">{{ $vacancy->type }}</span>
                        <span class="px-3 py-1 bg-gray-50 text-gray-500 text-[10px] font-bold rounded-lg">PostBy {{ $vacancy->user?->name ?? 'Administrator' }}</span>
                    </div>
                </div>

                <div class="flex items-center justify-between pt-4 border-t border-gray-50">
                    <div class="flex flex-col">
                        <span class="text-[10px] text-gray-400 font-medium uppercase tracking-wider">Batas Akhir</span>
                        <span class="text-xs font-bold text-gray-700">{{ $vacancy->close_date?->format('d M Y') ?? '-' }}</span>
                    </div>
                    <a href="/job/{{ $vacancy->slug }}" class="px-6 py-2 bg-kt-red text-white text-xs font-bold rounded-xl shadow-lg shadow-red-50 hover:bg-red-700 transition-colors">Detail</a>
                </div>
            </div>
            @empty
                <div class="col-span-full py-20 text-center">
                    <p class="text-gray-400">Tidak ada lowongan yang ditemukan.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-12">
            {{ $vacancies->links() }}
        </div>
    </section>
</div>
