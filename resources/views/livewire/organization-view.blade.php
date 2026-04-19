<div class="max-w-4xl mx-auto px-4 py-12">
    <!-- Header Section -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-black text-gray-900 tracking-tighter mb-4 uppercase leading-tight">{{ $title }}</h1>
        <div class="w-24 h-2 bg-kt-red mx-auto rounded-full"></div>
        <p class="text-gray-500 mt-6 text-lg">{{ $subtitle }}</p>
    </div>

    <!-- Content Section -->
    <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-xl shadow-gray-100/50 overflow-hidden">
        <div class="p-8 md:p-12 lg:p-16 text-gray-700 text-lg leading-loose whitespace-pre-line" style="line-height: 2.5rem;">
            {!! $content !!}
        </div>
    </div>

    <!-- Footer Decorative -->
    <div class="mt-12 text-center">
        <a href="/job" class="inline-flex items-center gap-2 px-8 py-4 bg-kt-red text-white font-bold rounded-2xl shadow-lg shadow-red-100 hover:bg-red-700 transition-all active:scale-95">
            Lihat Lowongan Kerja
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
            </svg>
        </a>
    </div>
</div>
