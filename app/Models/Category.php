<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'name_en', 'slug', 'icon', 'color', 'description', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];

    // Nama sesuai bahasa aktif
    public function getLocalizedNameAttribute(): string
    {
        $lang = app()->getLocale();
        return ($lang === 'en' && $this->name_en) ? $this->name_en : $this->name;
    }

    public function destinations()
    {
        return $this->hasMany(Destination::class);
    }
}
