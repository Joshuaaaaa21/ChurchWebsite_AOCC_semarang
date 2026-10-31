<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pastor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'title',
        'photo_path',
        'bio',
        'is_primary',
        'sort_order',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function sermons(): HasMany
    {
        return $this->hasMany(Sermon::class, 'speaker_id');
    }
}
