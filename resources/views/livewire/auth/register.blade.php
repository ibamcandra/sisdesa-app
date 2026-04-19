<div class="min-h-[90vh] flex flex-col items-center justify-center px-4 py-12">
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
            <h2 class="text-2xl font-black text-gray-900 tracking-tighter">Daftar Akun</h2>
            <p class="text-sm text-gray-400 mt-1 text-center">Bergabunglah untuk mulai melamar pekerjaan impianmu di Desa Campaka</p>
        </div>

        <form wire:submit.prevent="register" class="flex flex-col gap-5">
            <div class="flex flex-col gap-2">
                <label class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                <input wire:model="name" type="text" placeholder="Masukkan nama lengkap" class="w-full h-14 bg-gray-50 border-none rounded-2xl px-6 text-sm focus:ring-2 focus:ring-kt-red transition-all @error('name') ring-2 ring-red-500 @enderror">
                @error('name') <span class="text-red-500 text-[10px] font-bold ml-1">{{ $message }}</span> @enderror
            </div>

            <div class="flex flex-col gap-2">
                <label class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Alamat Email</label>
                <input wire:model="email" type="email" placeholder="nama@email.com" class="w-full h-14 bg-gray-50 border-none rounded-2xl px-6 text-sm focus:ring-2 focus:ring-kt-red transition-all @error('email') ring-2 ring-red-500 @enderror">
                @error('email') <span class="text-red-500 text-[10px] font-bold ml-1">{{ $message }}</span> @enderror
            </div>

            <div class="flex flex-col gap-2">
                <label class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Kata Sandi</label>
                <input wire:model="password" type="password" placeholder="••••••••" class="w-full h-14 bg-gray-50 border-none rounded-2xl px-6 text-sm focus:ring-2 focus:ring-kt-red transition-all @error('password') ring-2 ring-red-500 @enderror">
                @error('password') <span class="text-red-500 text-[10px] font-bold ml-1">{{ $message }}</span> @enderror
            </div>

            <div class="flex flex-col gap-2">
                <label class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">Konfirmasi Sandi</label>
                <input wire:model="password_confirmation" type="password" placeholder="••••••••" class="w-full h-14 bg-gray-50 border-none rounded-2xl px-6 text-sm focus:ring-2 focus:ring-kt-red transition-all">
            </div>

            <p class="text-[10px] text-gray-400 leading-relaxed px-1">
                Dengan mendaftar, Anda menyetujui <a href="#" class="text-kt-red font-bold">Syarat & Ketentuan</a> serta <a href="#" class="text-kt-red font-bold">Kebijakan Privasi</a> kami.
            </p>

            <button type="submit" class="w-full h-14 bg-kt-red text-white font-bold rounded-2xl shadow-lg shadow-red-100 mt-2 active:scale-95 transition-transform flex items-center justify-center gap-3">
                <span wire:loading.remove>Buat Akun Sekarang</span>
                <span wire:loading>Memproses...</span>
            </button>
        </form>

        <div class="mt-10 pt-8 border-t border-gray-50 flex flex-col items-center gap-4">
            <p class="text-sm text-gray-400">Sudah punya akun?</p>
            <a href="{{ route('login') }}" class="w-full h-14 bg-white border border-gray-100 text-gray-600 font-bold rounded-2xl flex items-center justify-center hover:bg-gray-50 transition-colors">
                Masuk ke Akun
            </a>
        </div>
    </div>
</div>
