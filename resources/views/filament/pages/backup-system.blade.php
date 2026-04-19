<x-filament-panels::page>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Database Backup -->
        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-50 rounded-lg">
                        <x-heroicon-o-circle-stack class="w-6 h-6 text-blue-600" />
                    </div>
                    <span>Backup Database</span>
                </div>
            </x-slot>

            <p class="text-sm text-gray-500 mb-6">
                Download seluruh data database dalam format file <span class="font-mono text-xs bg-gray-100 px-1 py-0.5 rounded">.sql</span>. File ini berisi skema tabel dan seluruh isi datanya.
            </p>

            <x-filament::button 
                wire:click="downloadDatabase" 
                icon="heroicon-m-arrow-down-tray"
                color="info"
                class="w-full"
            >
                Download SQL
            </x-filament::button>
        </x-filament::section>

        <!-- Source Code Backup -->
        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-amber-50 rounded-lg">
                        <x-heroicon-o-code-bracket class="w-6 h-6 text-amber-600" />
                    </div>
                    <span>Backup Source Code</span>
                </div>
            </x-slot>

            <p class="text-sm text-gray-500 mb-6">
                Download seluruh kode program dalam format <span class="font-mono text-xs bg-gray-100 px-1 py-0.5 rounded">.zip</span>. Folder <span class="italic">vendor</span> dan <span class="italic">node_modules</span> dikecualikan untuk mempercepat proses.
            </p>

            <x-filament::button 
                wire:click="downloadSource" 
                icon="heroicon-m-archive-box"
                color="warning"
                class="w-full"
            >
                Download ZIP
            </x-filament::button>
        </x-filament::section>
    </div>

    @if (session()->has('error'))
        <div class="mt-4 p-4 bg-red-50 border border-red-100 text-red-600 rounded-xl text-sm font-medium">
            {{ session('error') }}
        </div>
    @endif

    <x-filament::section class="mt-6">
        <x-slot name="heading">Informasi Penting</x-slot>
        <ul class="list-disc list-inside text-sm text-gray-500 space-y-2">
            <li>Lakukan backup secara berkala sebelum melakukan perubahan besar pada sistem.</li>
            <li>Simpan file backup di tempat yang aman (Cloud Storage atau Hardisk Eksternal).</li>
            <li>File backup database (.sql) dapat direstore menggunakan aplikasi seperti <span class="font-bold">HeidiSQL</span>, <span class="font-bold">DBeaver</span>, atau <span class="font-bold">PgAdmin</span>.</li>
        </ul>
    </x-filament::section>
</x-filament-panels::page>
