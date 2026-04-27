<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Post extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    protected $fillable = [
        'title',
        'slug',
        'content',
        'thumbnail',
        'category',
        'is_published',
        'views',
        'published_at',
        'excerpt',
        'seo_title',
        'seo_description',
        'seo_keywords',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_published' => 'boolean',
        'views' => 'integer',
    ];

    protected static function booted()
    {
        static::creating(function ($post) {
            if (!$post->slug) {
                $post->slug = Str::slug($post->title);
            }
        });

        static::saved(function ($post) {
            \App\Jobs\UpdateSitemapJob::dispatch();
        });

        static::deleted(function ($post) {
            \App\Jobs\UpdateSitemapJob::dispatch();
        });
    }
}
