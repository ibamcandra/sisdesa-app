<div class="text-center space-y-2">
    @if($company && $company->logo)
        <div class="flex justify-center mb-4">
            <img src="{{ Storage::url($company->logo) }}" alt="{{ $company->name ?? 'Logo Perusahaan' }}" class="h-16 w-auto object-contain">
        </div>
    @endif

    @if($company && $company->name)
        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
            {{ $company->name }}
        </p>
    @endif
</div>
