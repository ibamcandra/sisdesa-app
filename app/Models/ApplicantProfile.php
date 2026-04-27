<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ApplicantProfile extends Model
{
    use LogsActivity;

    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function province(): BelongsTo { return $this->belongsTo(Province::class); }
    public function city(): BelongsTo { return $this->belongsTo(City::class); }
    public function district(): BelongsTo { return $this->belongsTo(District::class); }
    public function educationLevel(): BelongsTo { return $this->belongsTo(EducationLevel::class); }
    public function skills(): BelongsToMany { return $this->belongsToMany(Skill::class, 'applicant_skill'); }
    public function applications(): HasMany { return $this->hasMany(JobApplication::class); }

    // Relasi baru
    public function experiences(): HasMany { return $this->hasMany(ApplicantExperience::class); }
    public function educations(): HasMany { return $this->hasMany(ApplicantEducation::class); }
    public function certifications(): HasMany { return $this->hasMany(ApplicantCertification::class); }

    public function getAvatarUrlAttribute()
    {
        if ($this->avatar && Storage::disk('public')->exists($this->avatar)) {
            return Storage::disk('public')->url($this->avatar);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }
}
