<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DestinationImage extends Model
{
    use HasFactory;

    protected $fillable = ['destination_id', 'image_path', 'caption', 'caption_en', 'sort_order', 'is_cover'];
    protected $casts = ['is_cover' => 'boolean'];

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    public function getLocalizedCaptionAttribute(): ?string
    {
        $lang = app()->getLocale();
        return ($lang === 'en' && $this->caption_en) ? $this->caption_en : $this->caption;
    }
}
