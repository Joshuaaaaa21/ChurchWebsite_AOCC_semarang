<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sermon extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'scripture_ref',
        'preached_at',
        'speaker_id',
        'speaker_name',
        'service_category_id',
        'youtube_url',
        'youtube_id',
        'thumbnail_path',
        'description',
        'is_published',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'preached_at' => 'date',
            'is_published' => 'boolean',
        ];
    }

    public function speaker(): BelongsTo
    {
        return $this->belongsTo(Pastor::class, 'speaker_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'service_category_id');
    }
}
