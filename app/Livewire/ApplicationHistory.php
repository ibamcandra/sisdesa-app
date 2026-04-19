<?php

namespace App\Livewire;

use App\Models\JobApplication;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Riwayat Lamaran')]
class ApplicationHistory extends Component
{
    public function render()
    {
        $user = auth()->user();
        
        // Ambil lamaran berdasarkan profil pelamar
        $applications = JobApplication::whereHas('applicantProfile', function($query) use ($user) {
            $query->where('user_id', $user->id);
        })
        ->with(['vacancy.branch'])
        ->latest()
        ->get();

        return view('livewire.application-history', [
            'applications' => $applications
        ]);
    }
}
