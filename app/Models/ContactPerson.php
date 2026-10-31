<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContactPerson extends Model
{
    use HasFactory;

    protected $table = 'contact_persons';

    protected $fillable = [
        'name',
        'role',
        'whatsapp_number',
        'default_message',
    ];

    /**
     * Get the formatted WhatsApp click URL.
     */
    public function getWhatsappUrlAttribute(): string
    {
        $phone = preg_replace('/[^0-9]/', '', (string) $this->whatsapp_number);
        if (str_starts_with($phone, '0')) {
            $phone = '62'.substr($phone, 1);
        }

        $message = urlencode($this->default_message ?? 'Halo Pak/Ibu, saya ingin bertanya tentang AOCC (Alfa Omega Community Church).');

        return "https://wa.me/{$phone}?text={$message}";
    }

    public function services(): HasMany
    {
        return $this->hasMany(ChurchService::class, 'contact_person_id');
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class, 'contact_person_id');
    }
}
