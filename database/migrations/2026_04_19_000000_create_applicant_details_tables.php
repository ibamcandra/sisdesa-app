<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah kolom gender dan cv_file ke applicant_profiles
        Schema::table('applicant_profiles', function (Blueprint $table) {
            $table->enum('gender', ['Laki-laki', 'Perempuan'])->nullable()->after('birth_date');
            $table->string('cv_file')->nullable()->after('major');
        });

        // Tabel Pengalaman Kerja
        Schema::create('applicant_experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_profile_id')->constrained()->onDelete('cascade');
            $table->string('company_name');
            $table->string('position');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_current')->default(false);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Tabel Riwayat Pendidikan
        Schema::create('applicant_educations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_profile_id')->constrained()->onDelete('cascade');
            $table->string('institution_name');
            $table->string('degree'); // S1, SMA, D3, dll
            $table->string('major')->nullable();
            $table->integer('start_year');
            $table->integer('end_year')->nullable();
            $table->boolean('is_graduated')->default(true);
            $table->timestamps();
        });

        // Tabel Sertifikasi
        Schema::create('applicant_certifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('applicant_profile_id')->constrained()->onDelete('cascade');
            $table->string('certification_name');
            $table->string('issuing_organization');
            $table->date('issue_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('credential_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applicant_certifications');
        Schema::dropIfExists('applicant_educations');
        Schema::dropIfExists('applicant_experiences');

        Schema::table('applicant_profiles', function (Blueprint $table) {
            $table->dropColumn(['gender', 'cv_file']);
        });
    }
};
