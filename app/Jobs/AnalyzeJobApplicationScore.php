<?php

namespace App\Jobs;

use App\Models\JobApplication;
use Gemini\Laravel\Facades\Gemini;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser;

class AnalyzeJobApplicationScore implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $application;

    /**
     * Create a new job instance.
     */
    public function __construct(JobApplication $application)
    {
        $this->application = $application;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->application->update(['scoring_status' => 'processing']);
        \Log::info("Starting AI Scoring for Application #{$this->application->id}");

        try {
            $applicant = $this->application->applicantProfile;
            $vacancy = $this->application->vacancy;

            if (!$applicant || !$vacancy) {
                throw new \Exception("Data pelamar atau lowongan tidak ditemukan.");
            }

            // 1. Extract Text from PDF
            $pdfText = "";
            if ($applicant->cv_file && Storage::exists($applicant->cv_file)) {
                try {
                    $parser = new Parser();
                    $pdfPath = Storage::path($applicant->cv_file);
                    $pdfText = $parser->parseFile($pdfPath)->getText();
                } catch (\Exception $pdfError) {
                    \Log::warning("PDF Parse Warning for Application #{$this->application->id}: " . $pdfError->getMessage());
                    $pdfText = "Gagal mengekstrak teks dari PDF CV.";
                }
            }

            // 2. Prepare Structured Data
            $educations = $applicant->educations->map(fn($e) => "- {$e->degree} {$e->major} di {$e->institution} ({$e->start_year}-{$e->end_year})")->implode("\n") ?: "Tidak ada data pendidikan";
            $experiences = $applicant->experiences->map(fn($e) => "- {$e->position} di {$e->company} ({$e->start_date} s/d {$e->end_date}): {$e->description}")->implode("\n") ?: "Tidak ada data pengalaman";
            $skills = $applicant->skills->pluck('name')->implode(', ') ?: "Tidak ada skill terdaftar";
            $certs = $applicant->certifications->map(fn($c) => "- {$c->name} ({$c->institution}, {$c->year})")->implode("\n") ?: "Tidak ada sertifikasi";

            // 3. Prepare Prompt
            $vacancyReq = strip_tags($vacancy->requirement);
            $vacancyDesc = strip_tags($vacancy->description);

            $prompt = "
            Kamu adalah asisten HR profesional yang cerdas. Tugasmu adalah memberikan skor kecocokan (0-100) antara profil pelamar dan lowongan pekerjaan.
            
            LOWONGAN PEKERJAAN:
            Judul: {$vacancy->title}
            Deskripsi: {$vacancyDesc}
            Persyaratan: {$vacancyReq}
            
            PROFIL PELAMAR (DATA FORM):
            Pendidikan:
            {$educations}
            
            Pengalaman Kerja:
            {$experiences}
            
            Skill: {$skills}
            
            Sertifikasi:
            {$certs}
            
            PROFIL PELAMAR (DARI CV PDF):
            " . ($pdfText ?: "Tidak ada data dari CV") . "
            
            INSTRUKSI PENTING:
            1. Analisis kecocokan secara objektif dan BERIMBANG antara Pendidikan, Pengalaman, dan Skill.
            2. Berikan toleransi untuk jurusan yang SERUMPUN (contoh: Teknik Informatika, Sistem Informasi, Manajemen Informatika, atau Ilmu Komputer dianggap setara untuk posisi IT).
            3. Berikan skor dari 0 sampai 100 berdasarkan total kecocokan.
            4. Berikan alasan singkat (maksimal 2 kalimat) dalam Bahasa Indonesia yang menjelaskan kelebihan dan kekurangan pelamar terhadap posisi ini.
            5. Kembalikan HASIL HANYA DALAM FORMAT JSON: {\"score\": 85, \"reason\": \"Alasan Anda...\"}
            ";

            // 4. Call AI (Dynamic Provider)
            $geminiConfig = \App\Models\GeminiConfig::first();
            $provider = $geminiConfig?->provider ?: 'gemini';
            $apiKey = $geminiConfig?->api_key ?: config('gemini.api_key');
            $modelName = $geminiConfig?->model ?: ($provider === 'groq' ? 'llama-3.3-70b-versatile' : 'gemma-3-1b-it');

            \Log::info("Calling AI API ({$provider}) with model {$modelName} for Application #{$this->application->id}");
            
            try {
                if ($provider === 'groq') {
                    // Call Groq via HTTP (OpenAI Compatible)
                    $response = \Http::withHeaders([
                        'Authorization' => 'Bearer ' . $apiKey,
                    ])->timeout(60)->post('https://api.groq.com/openai/v1/chat/completions', [
                        'model' => $modelName,
                        'messages' => [
                            ['role' => 'user', 'content' => $prompt]
                        ],
                        'temperature' => 0.2,
                    ]);

                    if (!$response->successful()) {
                        throw new \Exception("Groq API Error: " . $response->body());
                    }

                    $responseData = $response->json();
                    $aiResponse = $responseData['choices'][0]['message']['content'];
                } else {
                    // Call Gemini
                    $client = \Gemini::client($apiKey);
                    $result = $client->generativeModel(model: $modelName)->generateContent($prompt);
                    $aiResponse = $result->text();
                }
                
                \Log::info("AI Success for #{$this->application->id}");
            } catch (\Exception $e) {
                \Log::error("AI API Error for #{$this->application->id}: " . $e->getMessage());
                throw $e;
            }

            \Log::info("AI Raw Response: " . $aiResponse);

            // Clean response in case AI adds markdown blocks
            $jsonString = trim(str_replace(['```json', '```'], '', $aiResponse));
            $data = json_decode($jsonString, true);

            if (isset($data['score'])) {
                $this->application->update([
                    'matching_score' => (int) $data['score'],
                    'matching_reason' => $data['reason'] ?? '',
                    'scoring_status' => 'success'
                ]);
                \Log::info("AI Scoring Success for Application #{$this->application->id}");
            } else {
                throw new \Exception("Format respon AI tidak valid: " . $response);
            }

        } catch (\Exception $e) {
            \Log::error("AI Scoring Error for Application #{$this->application->id}: " . $e->getMessage());
            $this->application->update([
                'scoring_status' => 'failed',
                'matching_reason' => 'Gagal menganalisis CV. Error: ' . $e->getMessage()
            ]);
        }
    }
}
