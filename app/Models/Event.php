<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'destination_id', 'title', 'title_en', 'slug', 'description', 'description_en',
        'thumbnail', 'location', 'latitude', 'longitude',
        'start_date', 'end_date', 'organizer', 'ticket_info', 'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'latitude'   => 'float',
        'longitude'  => 'float',
        'is_active'  => 'boolean',
    ];

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    public function getLocalizedTitleAttribute(): string
    {
        $lang = app()->getLocale();
        return ($lang === 'en' && $this->title_en) ? $this->title_en : $this->title;
    }

    // Scope: event yang sedang berlangsung atau mendatang
    public function scopeUpcoming($query)
    {
        return $query->where('end_date', '>=', now()->toDateString())->where('is_active', true);
    }
}
