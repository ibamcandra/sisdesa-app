<div class="min-h-[80vh] flex flex-col items-center justify-center px-4">
    <div class="w-full max-w-md bg-white p-8 md:p-12 rounded-[2.5rem] border border-gray-50 shadow-2xl shadow-gray-200/50">
        <div class="flex flex-col items-center mb-10">
            @php
                $company = \App\Models\CompanyProfile::first();
            @endphp
            @if($company && $company->logo)
                <img src="{{ Storage::url($company->logo) }}" alt="Logo" class="h-16 w-auto object-contain mb-4">
            @else
                <div class="w-16 h-16 bg-kt-yellow rounded-[1.5rem] flex items-center justify-center border-2 border-kt-red shadow-sm mb-4">
                    <span class="text-kt-red font-black text-xl">KT</span>
                </div>
            @endif
            <h2 class="text-2xl font-black text-gray-900 tracking-tighter">Selamat Datang</h2>
            <p class="text-sm text-gray-400 mt-1">Masuk untuk melamar pekerjaan impianmu</p>
        </div>

        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 text-green-700 text-sm font-bold rounded-2xl border border-green-100 flex items-center gap-3 animate-bounce">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Pesan Error Umum --}}
        @if (session('error'))
            <div class="mb-6 p-4 bg-red-50 text-red-700 text-sm font-medium rounded-2xl border border-red-100 flex items-center gap-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        <form wire:submit.prevent="authenticate" class="flex flex-col gap-5">
            <div class="flex flex-col gap-2">
                <label class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Alamat Email</label>
                <div class="relative">
                    <input wire:model="email" type="email" placeholder="nama@email.com" class="w-full h-14 bg-gray-50 border-none rounded-2xl px-6 text-sm focus:ring-2 focus:ring-kt-red transition-all @error('email') ring-2 ring-red-500 @enderror">
                </div>
                @error('email') <span class="text-red-500 text-[10px] font-bold ml-1">{{ $message }}</span> @enderror
            </div>

            <div class="flex flex-col gap-2">
                <label class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Kata Sandi</label>
                <div class="relative">
                    <input wire:model="password" type="password" placeholder="••••••••" class="w-full h-14 bg-gray-50 border-none rounded-2xl px-6 text-sm focus:ring-2 focus:ring-kt-red transition-all @error('password') ring-2 ring-red-500 @enderror">
                </div>
                @error('password') <span class="text-red-500 text-[10px] font-bold ml-1">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-center justify-between mt-2">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input wire:model="remember" type="checkbox" class="w-5 h-5 rounded-lg border-gray-200 text-kt-red focus:ring-kt-red">
                    <span class="text-xs font-bold text-gray-500">Ingatkan saya</span>
                </label>
                <a href="{{ route('password.request') }}" class="text-xs font-bold text-kt-red hover:underline">Lupa sandi?</a>
            </div>

            <button type="submit" class="w-full h-14 bg-kt-red text-white font-bold rounded-2xl shadow-lg shadow-red-100 mt-4 active:scale-95 transition-transform flex items-center justify-center gap-3">
                <span wire:loading.remove>Masuk Sekarang</span>
                <span wire:loading>Memproses...</span>
            </button>
        </form>

        <div class="mt-10 pt-8 border-t border-gray-50 flex flex-col items-center gap-4">
            <p class="text-sm text-gray-400">Belum punya akun?</p>
            <a href="{{ route('register') }}" class="w-full h-14 bg-white border border-gray-100 text-gray-600 font-bold rounded-2xl flex items-center justify-center hover:bg-gray-50 transition-colors">
                Daftar Akun Baru
            </a>
        </div>
    </div>
</div>
