<div class="min-h-screen bg-white flex flex-col items-center justify-center p-6 bg-[radial-gradient(circle_at_top_right,_var(--tw-gradient-stops))] from-red-50 via-white to-white">
    <div class="w-full max-w-[420px]">
        <!-- Header -->
        <div class="mb-10 text-center">
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-2">Lupa Password?</h1>
            <p class="text-gray-500">Masukkan email Anda untuk menerima tautan reset password.</p>
        </div>

        @if (session('status'))
            <div class="mb-6 p-4 bg-green-50 border border-green-100 text-green-700 text-sm rounded-2xl flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ session('status') }}
            </div>
        @endif

        <form wire:submit.prevent="sendResetLink" class="space-y-6">
            <div class="space-y-2">
                <label for="email" class="text-[10px] uppercase font-bold tracking-widest text-gray-400 ml-1">Alamat Email</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-kt-red transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                        </svg>
                    </div>
                    <input wire:model="email" type="email" id="email" 
                           class="w-full h-14 pl-12 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-kt-red/20 focus:bg-white transition-all text-sm font-medium" 
                           placeholder="nama@email.com">
                </div>
                @error('email') <span class="text-xs text-red-500 font-bold ml-1">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="w-full h-14 bg-kt-red text-white font-bold rounded-2xl shadow-lg shadow-red-100 mt-4 active:scale-95 transition-transform flex items-center justify-center gap-3">
                <span wire:loading.remove>Kirim Link Reset</span>
                <span wire:loading>Mengirim...</span>
            </button>
        </form>

        <div class="mt-10 pt-8 border-t border-gray-50 flex flex-col items-center gap-4">
            <a href="{{ route('login') }}" class="text-sm font-bold text-gray-500 hover:text-kt-red transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali ke Login
            </a>
        </div>
    </div>
</div>
