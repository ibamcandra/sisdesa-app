<?php

namespace App\Livewire;

use App\Models\Post;
use App\Models\Vacancy;
use App\Models\Branch;
use Livewire\Component;
use Livewire\Attributes\Title;

class Home extends Component
{
    #[Title('Portal Informasi & Lowongan Kerja Desa Campaka')]
    public function render()
    {
        return view('livewire.home-page', [
            'posts' => Post::where('is_published', true)->latest('published_at')->take(3)->get(),
            'vacancies' => Vacancy::with(['branch', 'user'])->where(function($q) {
                $q->whereNull('close_date')
                  ->orWhere('close_date', '>=', now()->startOfDay());
            })->latest()->take(4)->get(),
            'branches' => Branch::all(),
        ]);
    }
}
