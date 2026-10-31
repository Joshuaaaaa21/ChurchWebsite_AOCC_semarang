<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Church extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'short_name',
        'full_name',
        'tagline',
        'logo_path',
        'address',
        'phone',
        'email',
        'instagram',
        'youtube',
        'maps_url',
    ];

    public function aboutContent(): HasOne
    {
        return $this->hasOne(AboutContent::class);
    }
}
