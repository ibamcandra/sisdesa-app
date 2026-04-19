<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Menghapus check constraint bawaan enum PostgreSQL
        DB::statement('ALTER TABLE job_applications DROP CONSTRAINT IF EXISTS job_applications_status_check');
    }

    public function down(): void
    {
        // Tidak perlu mengembalikan karena kita ingin kolom ini tetap string
    }
};
