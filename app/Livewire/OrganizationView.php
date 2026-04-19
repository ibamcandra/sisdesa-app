<?php

namespace App\Livewire;

use App\Models\OrganizationStructure;
use Livewire\Component;

class OrganizationView extends Component
{
    public function render()
    {
        $organization = OrganizationStructure::first();
        
        return view('livewire.organization-view', [
            'title' => $organization?->title ?? 'Struktur Organisasi',
            'subtitle' => $organization?->subtitle ?? 'Mengenal lebih dekat susunan kepengurusan Karang Taruna Desa Campaka.',
            'content' => \Illuminate\Support\Str::markdown($organization?->content ?? 'Struktur organisasi belum diperbarui.')
        ]);
    }
}
