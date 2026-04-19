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
        $filename = 'db_backup_' . date('Y-m-d_H-i-s') . '.sql';
        $path = storage_path('app/' . $filename);

        // Command to run pg_dump
        // Since the DB_HOST is 'db' in .env, we assume the PHP container can reach it
        $command = sprintf(
            'PGPASSWORD="%s" pg_dump -h %s -U %s %s > %s',
            config('database.connections.pgsql.password'),
            config('database.connections.pgsql.host'),
            config('database.connections.pgsql.username'),
            config('database.connections.pgsql.database'),
            $path
        );

        exec($command);

        if (file_exists($path)) {
            return Response::download($path)->deleteFileAfterSend(true);
        }

        session()->flash('error', 'Gagal membuat backup database. Pastikan pg_dump terinstal di container aplikasi.');
    }

    public function downloadSource()
    {
        $filename = 'source_backup_' . date('Y-m-d_H-i-s') . '.zip';
        $path = storage_path('app/' . $filename);
        $rootPath = base_path();

        // Zip command excluding heavy directories
        $command = sprintf(
            'cd %s && zip -r %s . -x "vendor/*" -x "node_modules/*" -x "storage/*" -x ".git/*" -x "*.log"',
            $rootPath,
            $path
        );

        exec($command);

        if (file_exists($path)) {
            return Response::download($path)->deleteFileAfterSend(true);
        }

        session()->flash('error', 'Gagal membuat backup source code.');
    }
}
