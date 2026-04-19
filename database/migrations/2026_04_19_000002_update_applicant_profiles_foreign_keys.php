<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applicant_profiles', function (Blueprint $table) {
            // Drop constraints lama (nama constraint biasanya otomatis dibuat Laravel)
            // Kita gunakan try catch atau DB statement untuk lebih aman di PostgreSQL
            try {
                $table->dropForeign(['province_id']);
                $table->dropForeign(['city_id']);
                $table->dropForeign(['district_id']);
            } catch (\Exception $e) {
                // Jika gagal lewat Blueprint, kita biarkan saja atau log
            }
        });

        // Hubungkan ke tabel baru Laravolt
        Schema::table('applicant_profiles', function (Blueprint $table) {
            $table->foreign('province_id')->references('id')->on('indonesia_provinces')->onDelete('set null');
            $table->foreign('city_id')->references('id')->on('indonesia_cities')->onDelete('set null');
            $table->foreign('district_id')->references('id')->on('indonesia_districts')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('applicant_profiles', function (Blueprint $table) {
            $table->dropForeign(['province_id']);
            $table->dropForeign(['city_id']);
            $table->dropForeign(['district_id']);
        });
    }
};
