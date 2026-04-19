<div class="min-h-screen bg-white flex flex-col items-center justify-center p-6 bg-[radial-gradient(circle_at_top_right,_var(--tw-gradient-stops))] from-red-50 via-white to-white">
    <div class="w-full max-w-[420px]">
        <!-- Header -->
        <div class="mb-10 text-center">
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-2">Password Baru</h1>
            <p class="text-gray-500">Silakan masukkan password baru Anda di bawah ini.</p>
        </div>

        <form wire:submit.prevent="resetPassword" class="space-y-6">
            <input type="hidden" wire:model="token">
            
            <div class="space-y-2">
                <label for="email" class="text-[10px] uppercase font-bold tracking-widest text-gray-400 ml-1">Alamat Email</label>
                <div class="relative group opacity-60">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                        </svg>
                    </div>
                    <input wire:model="email" type="email" id="email" readonly
                           class="w-full h-14 pl-12 bg-gray-100 border-none rounded-2xl text-sm font-medium cursor-not-allowed">
                </div>
                @error('email') <span class="text-xs text-red-500 font-bold ml-1">{{ $message }}</span> @enderror
            </div>

            <div class="space-y-2">
                <label for="password" class="text-[10px] uppercase font-bold tracking-widest text-gray-400 ml-1">Password Baru</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-kt-red transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <input wire:model="password" type="password" id="password" 
                           class="w-full h-14 pl-12 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-kt-red/20 focus:bg-white transition-all text-sm font-medium" 
                           placeholder="Minimal 8 karakter">
                </div>
                @error('password') <span class="text-xs text-red-500 font-bold ml-1">{{ $message }}</span> @enderror
            </div>

            <div class="space-y-2">
                <label for="password_confirmation" class="text-[10px] uppercase font-bold tracking-widest text-gray-400 ml-1">Konfirmasi Password</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-kt-red transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <input wire:model="password_confirmation" type="password" id="password_confirmation" 
                           class="w-full h-14 pl-12 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-kt-red/20 focus:bg-white transition-all text-sm font-medium" 
                           placeholder="Ulangi password baru">
                </div>
            </div>

            <button type="submit" class="w-full h-14 bg-kt-red text-white font-bold rounded-2xl shadow-lg shadow-red-100 mt-4 active:scale-95 transition-transform flex items-center justify-center gap-3">
                <span wire:loading.remove>Simpan Password Baru</span>
                <span wire:loading>Menyimpan...</span>
            </button>
        </form>
    </div>
</div>
