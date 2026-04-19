<?php

namespace App\Livewire;

use App\Models\AboutPage;
use Livewire\Component;
use Illuminate\Support\Str;

class AboutView extends Component
{
    public function render()
    {
        $about = AboutPage::first();
        
        return view('livewire.about-view', [
            'title' => $about?->title ?? 'Tentang Kami',
            'subtitle' => $about?->subtitle ?? 'Mengenal lebih dekat Karang Taruna Desa Campaka.',
            'content' => Str::markdown($about?->content ?? 'Profil tentang kami belum diperbarui.')
        ]);
    }
}
