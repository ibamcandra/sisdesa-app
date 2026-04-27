<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CleanupUnverifiedUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cleanup-unverified-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hapus user dengan role pelamar yang tidak memverifikasi email dalam 24 jam';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = User::where('role', 'pelamar')
            ->whereNull('email_verified_at')
            ->where('created_at', '<=', now()->subHours(24))
            ->delete();

        $this->info("Berhasil menghapus {$count} user pelamar yang tidak diverifikasi.");
    }
}
