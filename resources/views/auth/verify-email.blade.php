<x-layouts.app>
    <div class="min-h-[80vh] flex items-center justify-center px-4 py-12">
        <div class="max-w-md w-full bg-white rounded-3xl shadow-xl shadow-gray-100 p-8 border border-gray-100">
            <div class="text-center mb-8">
                <div class="w-20 h-20 bg-red-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-kt-red" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-black text-gray-900 mb-2">Verifikasi Email Anda</h2>
                <p class="text-gray-500">Terima kasih telah mendaftar! Sebelum memulai, silakan verifikasi alamat email Anda melalui link yang baru saja kami kirimkan.</p>
            </div>

            @if (session('message'))
                <div class="mb-6 p-4 bg-green-50 text-green-700 text-sm font-medium rounded-2xl border border-green-100 flex items-center gap-3">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    Tautan verifikasi baru telah dikirim!
                </div>
            @endif

            <div class="flex flex-col gap-4">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="w-full py-4 bg-kt-red text-white font-bold rounded-2xl shadow-lg shadow-red-100 hover:bg-red-700 transition-all">
                        Kirim Ulang Email Verifikasi
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full py-4 bg-gray-50 text-gray-600 font-bold rounded-2xl hover:bg-gray-100 transition-all text-sm">
                        Keluar (Logout)
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
