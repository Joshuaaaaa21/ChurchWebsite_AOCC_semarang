<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyVerse extends Model
{
    use HasFactory;

    protected $fillable = [
        'verse_date',
        'book',
        'chapter',
        'verse_from',
        'verse_to',
        'text',
        'translation',
        'is_default',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'verse_date' => 'date',
            'chapter' => 'integer',
            'verse_from' => 'integer',
            'verse_to' => 'integer',
            'is_default' => 'boolean',
        ];
    }

    /**
     * Get human-friendly reference e.g., "Kejadian 1 : 1" or "Yohanes 3 : 16-17"
     */
    public function getReferenceAttribute(): string
    {
        if ($this->verse_to && $this->verse_to !== $this->verse_from) {
            return "{$this->book} {$this->chapter} : {$this->verse_from}-{$this->verse_to}";
        }

        return "{$this->book} {$this->chapter} : {$this->verse_from}";
    }
}
