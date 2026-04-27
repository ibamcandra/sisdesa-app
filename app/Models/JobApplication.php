<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplication extends Model {
    protected $guarded = [];

    protected $casts = [
        'interview_date' => 'date',
        'start_date' => 'date',
    ];

    public function vacancy(): BelongsTo { return $this->belongsTo(Vacancy::class); }
    public function applicantProfile(): BelongsTo { return $this->belongsTo(ApplicantProfile::class); }
}
