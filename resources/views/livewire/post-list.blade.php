<div class="max-w-7xl mx-auto md:px-4 lg:px-8 py-8 md:py-12">
    {{-- Header Section --}}
    <section class="px-4 mb-12 text-center md:text-left">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <h2 class="text-3xl md:text-4xl font-black text-gray-900 tracking-tight">Kabar Desa</h2>
                <p class="text-gray-500 mt-2 font-medium">Informasi terbaru seputar kegiatan, lowongan, dan berita Desa Campaka</p>
            </div>
            
            {{-- Filters --}}
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="relative">
                    <input type="text" wire:model.live="search" placeholder="Cari kabar..." class="w-full sm:w-64 h-12 bg-white border border-gray-100 rounded-2xl px-5 text-sm shadow-sm focus:ring-2 focus:ring-kt-red focus:border-transparent transition-all">
                </div>
                <select wire:model.live="category" class="h-12 bg-white border border-gray-100 rounded-2xl px-4 text-sm shadow-sm focus:ring-2 focus:ring-kt-red">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </section>

    {{-- Articles Grid --}}
    <section class="px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($posts as $post)
                <article class="bg-white rounded-[2.5rem] border border-gray-50 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all group overflow-hidden flex flex-col h-full">
                    {{-- Thumbnail --}}
                    <a href="/kabar/{{ $post->slug }}" class="relative aspect-[16/10] overflow-hidden">
                        @if($post->thumbnail)
                            <img src="{{ Storage::url($post->thumbnail) }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="w-full h-full bg-kt-yellow/30 flex items-center justify-center">
                                <span class="text-kt-red font-black text-2xl opacity-20">KT</span>
                            </div>
                        @endif
                        <div class="absolute top-4 left-4">
                            <span class="px-4 py-1.5 bg-white/90 backdrop-blur-md text-kt-red text-[10px] font-bold rounded-full uppercase tracking-wider shadow-sm">{{ $post->category ?? 'Umum' }}</span>
                        </div>
                    </a>

                    {{-- Content --}}
                    <div class="p-8 flex flex-col flex-1">
                        <div class="flex items-center gap-3 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4">
                            <span>{{ $post->published_at ? $post->published_at->format('d M Y') : $post->created_at->format('d M Y') }}</span>
                            <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                            <span>5 Menit Baca</span>
                        </div>
                        
                        <h3 class="text-xl font-bold text-gray-900 group-hover:text-kt-red transition-colors leading-tight mb-4 flex-1">
                            <a href="/kabar/{{ $post->slug }}">{{ $post->title }}</a>
                        </h3>

                        <div class="flex items-center justify-between pt-6 border-t border-gray-50">
                            <a href="/kabar/{{ $post->slug }}" class="text-sm font-bold text-gray-900 flex items-center gap-2 group/btn">
                                Baca Selengkapnya
                                <svg class="w-4 h-4 transform group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full py-24 text-center">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Belum ada kabar</h3>
                    <p class="text-gray-400">Silakan kembali lagi nanti untuk informasi terbaru.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-16">
            {{ $posts->links() }}
        </div>
    </section>
</div>
