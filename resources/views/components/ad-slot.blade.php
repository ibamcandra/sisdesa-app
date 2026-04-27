@props(['location'])

@php
    $ad = \App\Models\Ad::where('location', $location)
        ->where('is_active', true)
        ->latest()
        ->first();
@endphp

@if($ad)
    <div class="w-full flex items-center justify-center overflow-hidden my-6">
        <div class="relative w-full max-w-full group">
            <span class="absolute top-0 left-0 bg-gray-900/50 text-white text-[8px] px-2 py-0.5 rounded-br-lg z-10 font-bold uppercase tracking-widest backdrop-blur-sm">Sponsor</span>
            
            @if($ad->type === 'image')
                @if($ad->url)
                    <a href="{{ $ad->url }}" target="_blank" rel="noopener noreferrer" class="block w-full">
                @else
                    <div class="block w-full">
                @endif
                
                <img src="{{ Storage::url($ad->image_path) }}" alt="{{ $ad->name }}" class="w-full h-auto max-h-[250px] object-cover rounded-2xl md:rounded-[2rem] shadow-sm group-hover:shadow-xl transition-all duration-300">
                
                @if($ad->url)
                    </a>
                @else
                    </div>
                @endif
            @elseif($ad->type === 'script')
                <div class="w-full min-h-[90px] bg-gray-50 flex items-center justify-center rounded-2xl md:rounded-[2rem] overflow-hidden p-2 text-center text-xs text-gray-400">
                    {!! $ad->script_code !!}
                </div>
            @endif
        </div>
    </div>
@endif
