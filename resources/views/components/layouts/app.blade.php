<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    @php
        $company = \App\Models\CompanyProfile::first();
        $site_name = 'Karang Taruna Desa Campaka';
    @endphp

    @if($company && $company->favicon)
        <link rel="icon" type="image/x-icon" href="{{ Storage::url($company->favicon) }}">
    @else
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @endif

    <!-- SEO Meta Tags -->
    <title>{{ ($title ?? null) ? $title . ' | ' . $site_name : 'Desa Campaka Purwakarta | ' . $site_name }}</title>
    <meta name="description" content="{{ $meta_description ?? 'Portal lowongan kerja dan informasi resmi Karang Taruna Desa Campaka, Purwakarta. Temukan karir impianmu dan berkontribusi untuk desa.' }}">
    <meta name="keywords" content="{{ $meta_keywords ?? 'desa campaka purwakarta, loker purwakarta, lowongan kerja desa campaka, karang taruna campaka, kerja di desa, nyarigawe' }}">
    <meta name="author" content="Karang Taruna Desa Campaka">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $title ?? 'Desa Campaka Purwakarta' }}">
    <meta property="og:description" content="{{ $meta_description ?? 'Portal lowongan kerja resmi Desa Campaka.' }}">
    <meta property="og:image" content="{{ $og_image ?? asset('images/og-image.jpg') }}">

    <!-- Local Business Schema -->
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "GovernmentOrganization",
      "name": "Karang Taruna Desa Campaka",
      "alternateName": "Desa Campaka Purwakarta",
      "url": "https://www.karangtarunacampaka.id",
      "logo": "{{ $company && $company->logo ? Storage::url($company->logo) : asset('favicon.ico') }}",
      "contactPoint": {
        "@@type": "ContactPoint",
        "telephone": "",
        "contactType": "customer service",
        "areaServed": "ID",
        "availableLanguage": "Indonesian"
      },
      "address": {
        "@@type": "PostalAddress",
        "streetAddress": "Desa Campaka",
        "addressLocality": "Purwakarta",
        "addressRegion": "Jawa Barat",
        "postalCode": "41181",
        "addressCountry": "ID"
      },
      "sameAs": [
        "https://www.facebook.com/karangtarunacampaka",
        "https://www.instagram.com/karangtarunacampaka"
      ]
    }
    </script>

    @stack('meta')

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @livewireStyles
</head>
<body class="bg-gray-50 text-gray-900" x-data="{ open: false }">

    <!-- Mobile Drawer -->
    <div x-cloak x-show="open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/50 z-[60] md:hidden" 
         @click="open = false">
    </div>

    <div x-cloak x-show="open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="fixed inset-y-0 left-0 w-80 bg-white z-[70] shadow-2xl md:hidden flex flex-col">
        
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                @if($company && $company->logo)
                    <img src="{{ Storage::url($company->logo) }}" alt="Logo {{ $company->name }}" class="h-10 w-auto object-contain">
                @else
                    <div class="w-10 h-10 bg-kt-yellow rounded-xl flex items-center justify-center border-2 border-kt-red">
                        <span class="text-kt-red font-bold text-xs">KT</span>
                    </div>
                @endif
                <div>
                    <h1 class="font-black text-sm text-kt-red leading-tight">{{ $company->name ?? 'KARANG TARUNA' }}</h1>
                    <p class="text-[8px] md:text-[10px] text-gray-400 uppercase tracking-widest font-bold">Desa Campaka - Purwakarta</p>
                </div>
            </div>
            <button @click="open = false" class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-50 text-gray-400">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <nav class="flex-1 p-6 flex flex-col gap-2">
            <a href="/" class="flex items-center gap-4 p-4 {{ request()->is('/') ? 'bg-red-50 text-kt-red' : 'text-gray-600' }} rounded-2xl font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                Home
            </a>
            <a href="/job" class="flex items-center gap-4 p-4 {{ request()->is('job*') ? 'bg-red-50 text-kt-red' : 'text-gray-600' }} rounded-2xl font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Loker
            </a>
            @auth
                @if(auth()->user()->role === 'pelamar')
                    <a href="/history" class="flex items-center gap-4 p-4 {{ request()->is('history*') ? 'bg-red-50 text-kt-red' : 'text-gray-600' }} rounded-2xl font-bold">
                        <svg class="w-5 h-5 {{ request()->is('history*') ? 'text-kt-red' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01m-.01 4h.01"/></svg>
                        Riwayat Lamaran
                    </a>
                @endif
            @endauth
            <a href="/kabar" class="flex items-center gap-4 p-4 text-gray-600 rounded-2xl font-bold">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                Kabar
            </a>
            <a href="/organization" class="flex items-center gap-4 p-4 text-gray-600 rounded-2xl font-bold">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                Struktur Organisasi
            </a>
            <a href="/about" class="flex items-center gap-4 p-4 text-gray-600 rounded-2xl font-bold">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Tentang Kami
            </a>
            
            <div class="mt-6 pt-6 border-t border-gray-100 flex flex-col gap-3">
                @guest
                    <div class="grid grid-cols-2 gap-3">
                        <a href="/login" class="flex items-center justify-center p-4 bg-gray-100 text-gray-600 rounded-2xl font-bold">
                            Login
                        </a>
                        <a href="/register" class="flex items-center justify-center p-4 bg-kt-red text-white rounded-2xl font-bold shadow-lg shadow-red-100">
                            Daftar
                        </a>
                    </div>
                    <a href="/admin" class="flex items-center justify-center gap-2 p-4 border border-gray-100 text-gray-400 rounded-2xl font-bold">
                        Login Admin
                    </a>
                @endguest

                @auth
                    <a href="/profile" class="flex items-center justify-center gap-2 p-4 bg-gray-100 text-gray-600 rounded-2xl font-bold">
                        Profil Saya
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center gap-2 p-4 bg-kt-red text-white rounded-2xl font-bold shadow-lg shadow-red-100">
                            Logout
                        </button>
                    </form>
                @endauth
            </div>
        </nav>
    </div>

    <!-- Desktop Header -->
    <header class="bg-white/80 backdrop-blur-md border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 md:px-8 h-20 flex items-center justify-between">
            <div class="flex items-center gap-3 md:gap-4">
                <button @click="open = true" class="md:hidden w-10 h-10 flex items-center justify-center rounded-xl bg-gray-50 text-gray-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>

                <a href="/" class="flex items-center gap-2 md:gap-4">
                    @if($company && $company->logo)
                        <img src="{{ Storage::url($company->logo) }}" alt="Logo {{ $company->name }}" class="h-10 md:h-12 w-auto object-contain">
                    @else
                        <div class="w-10 h-10 md:w-12 md:h-12 bg-kt-yellow rounded-xl md:rounded-2xl flex items-center justify-center border-2 border-kt-red shadow-sm">
                            <span class="text-kt-red font-bold text-xs md:text-sm">KT</span>
                        </div>
                    @endif
                    <div>
                        <h1 class="font-black text-sm md:text-xl leading-tight text-kt-red tracking-tighter uppercase">{{ $company->name ?? 'KARANG TARUNA' }}</h1>
                        <p class="text-[8px] md:text-[10px] text-gray-400 uppercase tracking-widest font-bold">Desa Campaka - Purwakarta</p>
                    </div>
                </a>
            </div>

            <nav class="hidden md:flex items-center gap-8">
                <a href="/" class="text-sm font-bold {{ request()->is('/') ? 'text-kt-red' : 'text-gray-400 hover:text-kt-red' }}">Home</a>
                <a href="/job" class="text-sm font-bold {{ request()->is('job*') ? 'text-kt-red' : 'text-gray-400 hover:text-kt-red' }}">Loker</a>
                @auth
                    @if(auth()->user()->role === 'pelamar')
                        <a href="/history" class="text-sm font-bold {{ request()->is('history*') ? 'text-kt-red' : 'text-gray-400 hover:text-kt-red' }}">Riwayat</a>
                    @endif
                @endauth
                <a href="/kabar" class="text-sm font-bold text-gray-400 hover:text-kt-red">Kabar</a>
                <a href="/organization" class="text-sm font-bold text-gray-400 hover:text-kt-red">Struktur Organisasi</a>
                <a href="/about" class="text-sm font-bold text-gray-400 hover:text-kt-red">Tentang Kami</a>
            </nav>

            <div class="hidden md:flex items-center gap-3">
                @guest
                    <a href="/login" class="px-6 py-2.5 bg-gray-100 text-gray-600 text-sm font-bold rounded-xl hover:bg-gray-200 transition-colors">
                        Login
                    </a>
                    <a href="/register" class="px-6 py-2.5 bg-kt-red text-white text-sm font-bold rounded-xl hover:bg-red-700 transition-colors shadow-lg shadow-red-100">
                        Daftar
                    </a>
                    <div class="w-px h-6 bg-gray-100 mx-2"></div>
                    <a href="/admin" class="text-xs font-bold text-gray-400 hover:text-kt-red transition-colors">
                        Admin
                    </a>
                @endguest

                @auth
                    <a href="/profile" class="px-6 py-2.5 bg-gray-100 text-gray-600 text-sm font-bold rounded-xl hover:bg-gray-200 transition-colors">
                        Profil Saya
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="px-6 py-2.5 bg-kt-red text-white text-sm font-bold rounded-xl hover:bg-red-700 transition-colors shadow-lg shadow-red-100">
                            Logout
                        </button>
                    </form>
                @endauth
            </div>
        </div>
    </header>

    <main class="min-h-screen pb-24 md:pb-12">
        <div class="max-w-6xl mx-auto px-4 mt-4">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-100 text-green-700 text-sm font-bold rounded-2xl flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-4 bg-red-50 border border-red-100 text-red-700 text-sm font-bold rounded-2xl flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif
        </div>
        {{ $slot }}
    </main>

    <!-- Mobile Bottom Nav -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white/90 backdrop-blur-lg border-t border-gray-100 h-20 flex items-center justify-around px-2 z-50">
        <a href="/" class="flex flex-col items-center gap-1 {{ request()->is('/') ? 'text-kt-red' : 'text-gray-400' }}">
            <div class="w-10 h-10 {{ request()->is('/') ? 'bg-red-50' : '' }} rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7"/></svg>
            </div>
            <span class="text-[10px] font-bold uppercase tracking-wider">Home</span>
        </a>
        <a href="/job" class="flex flex-col items-center gap-1 {{ request()->is('job*') ? 'text-kt-red' : 'text-gray-400' }}">
            <div class="w-10 h-10 {{ request()->is('job*') ? 'bg-red-50' : '' }} rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
            <span class="text-[10px] font-bold uppercase tracking-wider">Loker</span>
        </a>
        @auth
            @if(auth()->user()->role === 'pelamar')
                <a href="/history" class="flex flex-col items-center gap-1 {{ request()->is('history*') ? 'text-kt-red' : 'text-gray-400' }}">
                    <div class="w-10 h-10 {{ request()->is('history*') ? 'bg-red-50' : '' }} rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01m-.01 4h.01"/></svg>
                    </div>
                    <span class="text-[10px] font-bold uppercase tracking-wider">Riwayat</span>
                </a>
            @endif
        @endauth
        <a href="/kabar" class="flex flex-col items-center gap-1 text-gray-400">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
            </div>
            <span class="text-[10px] font-bold uppercase tracking-wider">Kabar</span>
        </a>
        <a href="/profile" class="flex flex-col items-center gap-1 {{ request()->is('profile*') ? 'text-kt-red' : 'text-gray-400' }}">
            <div class="w-10 h-10 {{ request()->is('profile*') ? 'bg-red-50' : '' }} rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
            <span class="text-[10px] font-bold uppercase tracking-wider">Profil</span>
        </a>
    </nav>

    @livewireScripts
</body>
</html>
