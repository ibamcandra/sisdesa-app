<div class="max-w-7xl mx-auto md:px-4 lg:px-8 py-8 md:py-12">
    {{-- Back Link --}}
    <div class="px-4 mb-8">
        <a href="/kabar" class="flex items-center gap-2 text-sm font-bold text-gray-400 hover:text-kt-red transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Kabar
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
        {{-- Main Article --}}
        <article class="lg:col-span-2 px-4">
            <div class="mb-8">
                <div class="flex flex-wrap items-center gap-4 mb-6">
                    <span class="px-4 py-1.5 bg-red-50 text-kt-red text-[10px] font-bold rounded-full uppercase tracking-widest">{{ $post->category ?? 'Umum' }}</span>
                    <div class="flex items-center gap-2 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                        <span>{{ $post->published_at ? $post->published_at->format('d M Y') : $post->created_at->format('d M Y') }}</span>
                    </div>
                </div>
                
                <h1 class="text-3xl md:text-5xl font-black text-gray-900 leading-tight mb-8">{{ $post->title }}</h1>
                
                @if($post->thumbnail)
                    <div class="rounded-[3rem] overflow-hidden mb-10 shadow-2xl shadow-gray-200">
                        <img src="{{ Storage::url($post->thumbnail) }}" alt="{{ $post->title }}" class="w-full h-auto">
                    </div>
                @endif
            </div>

            <div class="prose prose-lg prose-red max-w-none text-gray-700 leading-relaxed prose-headings:font-black prose-headings:text-gray-900 prose-p:mb-6">
                {!! $post->content !!}
            </div>

            {{-- Share --}}
            <div class="mt-16 pt-8 border-t border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <span class="text-sm font-bold text-gray-900">Bagikan:</span>
                    <div class="flex gap-2">
                        <button class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center hover:scale-110 transition-transform"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg></button>
                        <button class="w-10 h-10 rounded-full bg-sky-400 text-white flex items-center justify-center hover:scale-110 transition-transform"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.84 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg></button>
                    </div>
                </div>
            </div>
        </article>

        {{-- Sidebar --}}
        <aside class="px-4">
            <div class="sticky top-8 flex flex-col gap-8">
                {{-- Recent Posts --}}
                <div class="bg-white p-8 rounded-[2.5rem] border border-gray-50 shadow-sm">
                    <h3 class="font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <span class="w-1.5 h-4 bg-kt-red rounded-full"></span>
                        Kabar Terbaru Lainnya
                    </h3>
                    <div class="flex flex-col gap-6">
                        @foreach($recentPosts as $recent)
                            <a href="/kabar/{{ $recent->slug }}" class="group block">
                                <h4 class="text-sm font-bold text-gray-900 group-hover:text-kt-red transition-colors line-clamp-2 mb-2">{{ $recent->title }}</h4>
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $recent->published_at ? $recent->published_at->format('d M Y') : $recent->created_at->format('d M Y') }}</span>
                            </a>
                        @endforeach
                    </div>
                    <a href="/kabar" class="block w-full text-center mt-8 py-3 bg-gray-50 text-gray-900 text-xs font-bold rounded-xl hover:bg-gray-100 transition-colors">Lihat Semua</a>
                </div>

                <!-- Ad Slot: Sidebar -->
                <x-ad-slot location="sidebar" />

                {{-- CTA Card --}}
                <div class="bg-kt-red p-8 rounded-[2.5rem] text-white shadow-xl shadow-red-100">
                    <h4 class="font-bold text-lg mb-2">Cari Pekerjaan?</h4>
                    <p class="text-white/70 text-sm mb-6">Temukan peluang karir terbaik di wilayah Desa Campaka.</p>
                    <a href="/job" class="block w-full py-3 bg-white text-kt-red text-center font-bold rounded-xl hover:bg-gray-50 transition-colors">Lihat Lowongan</a>
                </div>
            </div>
        </aside>
    </div>
</div>
