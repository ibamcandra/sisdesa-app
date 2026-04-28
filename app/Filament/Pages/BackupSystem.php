<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class BackupSystem extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-tray';
    protected static ?string $navigationGroup = 'General Setting';
    protected static ?string $navigationLabel = 'Backup System';
    protected static ?string $title = 'Backup & Recovery';
    protected static ?int $navigationSort = 10;

    protected static string $view = 'filament.pages.backup-system';

    public static function canAccess(): bool
    {
        return Auth::user()->role === 'super_admin';
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->role === 'super_admin';
    }

    public function downloadDatabase()
    {
        // Check if pg_dump is available
        $checkCommand = 'command -v pg_dump';
        exec($checkCommand, $output, $resultCode);
        
        if ($resultCode !== 0) {
            session()->flash('error', 'pg_dump tidak ditemukan di sistem. Pastikan postgresql-client sudah terinstal.');
            return;
        }

        $filename = 'db_backup_' . date('Y-m-d_H-i-s') . '.sql';
        $path = storage_path('app/' . $filename);

        // Command to run pg_dump
        // We use 2>&1 to capture error output
        $command = sprintf(
            'PGPASSWORD="%s" pg_dump -h %s -U %s %s > %s 2>&1',
            config('database.connections.pgsql.password'),
            config('database.connections.pgsql.host'),
            config('database.connections.pgsql.username'),
            config('database.connections.pgsql.database'),
            $path
        );

        exec($command, $errorOutput, $exitCode);

        if ($exitCode !== 0) {
            if (file_exists($path)) {
                unlink($path); // Remove empty or partial file
            }
            $errorMessage = !empty($errorOutput) ? implode(' ', $errorOutput) : 'Unknown error';
            session()->flash('error', 'Gagal membuat backup database: ' . $errorMessage);
            return;
        }

        if (file_exists($path) && filesize($path) > 0) {
            return Response::download($path)->deleteFileAfterSend(true);
        }

        session()->flash('error', 'Gagal membuat backup database. File hasil backup kosong (0 KB).');
    }

    public function downloadSource()
    {
        // Check if zip is available
        $checkCommand = 'command -v zip';
        exec($checkCommand, $output, $resultCode);

        if ($resultCode !== 0) {
            session()->flash('error', 'zip tidak ditemukan di sistem. Pastikan tool zip sudah terinstal.');
            return;
        }

        $filename = 'source_backup_' . date('Y-m-d_H-i-s') . '.zip';
        $path = storage_path('app/' . $filename);
        $rootPath = base_path();

        // Zip command excluding heavy directories
        // Capture stderr as well
        $command = sprintf(
            'cd %s && zip -r %s . -x "vendor/*" -x "node_modules/*" -x "storage/*" -x ".git/*" -x "*.log" 2>&1',
            $rootPath,
            $path
        );

        exec($command, $errorOutput, $exitCode);

        if ($exitCode !== 0) {
            if (file_exists($path)) {
                unlink($path);
            }
            $errorMessage = !empty($errorOutput) ? implode(' ', $errorOutput) : 'Unknown error';
            session()->flash('error', 'Gagal membuat backup source code: ' . $errorMessage);
            return;
        }

        if (file_exists($path) && filesize($path) > 0) {
            return Response::download($path)->deleteFileAfterSend(true);
        }

        session()->flash('error', 'Gagal membuat backup source code. File hasil backup kosong.');
    }
}
