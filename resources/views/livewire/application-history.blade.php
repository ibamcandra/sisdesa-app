<div class="max-w-6xl mx-auto px-4 py-12">
    <!-- Header Section -->
    <div class="mb-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-gray-900 tracking-tighter mb-2">Riwayat Lamaran</h1>
            <p class="text-gray-500">Pantau status lamaran pekerjaan yang telah Anda kirimkan.</p>
        </div>
        <a href="/job" class="inline-flex items-center gap-2 px-6 py-3 bg-kt-red text-white font-bold rounded-2xl shadow-lg shadow-red-100 hover:bg-red-700 transition-all active:scale-95 w-fit">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
            Cari Lowongan Lagi
        </a>
    </div>

    @if (session('success'))
        <div class="mb-8 p-4 bg-green-50 border border-green-100 text-green-700 text-sm font-bold rounded-2xl flex items-center gap-3 animate-pulse">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-8 p-4 bg-red-50 border border-red-100 text-red-700 text-sm font-bold rounded-2xl flex items-center gap-3">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <!-- Table Section -->
    <div class="bg-white rounded-[2.5rem] border border-gray-100 shadow-xl shadow-gray-100/50 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-50">Judul Lowongan</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-50">Cabang/Branch</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-50">Tanggal Kirim</th>
                        <th class="px-8 py-6 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-50 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($applications as $app)
                        <tr class="hover:bg-gray-50/30 transition-colors group">
                            <td class="px-8 py-6">
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-gray-900 group-hover:text-kt-red transition-colors">{{ $app->vacancy->title }}</span>
                                    <span class="text-[10px] text-gray-400 font-medium uppercase mt-1">{{ $app->vacancy->type }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full bg-kt-yellow"></div>
                                    <span class="text-sm font-medium text-gray-600">{{ $app->vacancy->branch->name ?? '-' }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-sm font-medium text-gray-500">
                                {{ $app->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="px-8 py-6 text-center">
                                @php
                                    $statusClasses = [
                                        'Terkirim' => 'bg-blue-50 text-blue-600 border-blue-100',
                                        'Review' => 'bg-orange-50 text-orange-600 border-orange-100',
                                        'Interview' => 'bg-purple-50 text-purple-600 border-purple-100',
                                        'Diterima' => 'bg-green-50 text-green-600 border-green-100',
                                        'Ditolak' => 'bg-red-50 text-red-600 border-red-100',
                                    ];
                                    $class = $statusClasses[$app->status] ?? 'bg-gray-50 text-gray-600 border-gray-100';
                                @endphp
                                <span 
                                    @if($app->status === 'Interview')
                                        x-on:click="$dispatch('open-interview', { 
                                            date: '{{ $app->interview_date?->format('d M Y') }}', 
                                            time: '{{ $app->interview_time }}', 
                                            location: '{{ $app->interview_location }}' 
                                        })"
                                        class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider border cursor-pointer hover:scale-105 transition-transform {{ $class }}"
                                    @else
                                        class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider border {{ $class }}"
                                    @endif
                                >
                                    {{ $app->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-gray-400 font-bold">Belum ada riwayat lamaran.</p>
                                    <a href="/job" class="mt-4 text-kt-red text-sm font-bold hover:underline">Mulai cari lowongan &rarr;</a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Detail Interview (Alpine.js) -->
    <div x-data="{ open: false, interview: {} }" 
         x-show="open" 
         x-on:open-interview.window="open = true; interview = $event.detail"
         class="fixed inset-0 z-50 overflow-y-auto" 
         style="display: none;">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm"></div>
            </div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border-4 border-kt-red/5">
                <div class="bg-white px-8 pt-10 pb-8">
                    <div class="text-center mb-8">
                        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-2xl bg-purple-50 text-purple-600 mb-4">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-black text-gray-900 tracking-tighter" id="modal-title">Jadwal Interview</h3>
                        <p class="text-gray-500 text-sm mt-1">Harap hadir tepat waktu sesuai jadwal berikut.</p>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Tanggal</p>
                            <p class="font-bold text-gray-900" x-text="interview.date"></p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Jam</p>
                            <p class="font-bold text-gray-900" x-text="interview.time"></p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100">
                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Tempat / Link</p>
                            <p class="font-bold text-gray-900" x-text="interview.location"></p>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-8 py-6 flex flex-col sm:flex-row-reverse gap-3">
                    <button type="button" x-on:click="open = false" class="w-full inline-flex justify-center rounded-2xl border border-transparent shadow-lg px-6 py-3 bg-kt-red text-base font-bold text-white hover:bg-red-700 transition-all sm:w-auto sm:text-sm active:scale-95">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
