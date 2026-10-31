<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Facility extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'name_en', 'icon'];

    public function getLocalizedNameAttribute(): string
    {
        $lang = app()->getLocale();
        return ($lang === 'en' && $this->name_en) ? $this->name_en : $this->name;
    }

    public function destinations()
    {
        return $this->belongsToMany(Destination::class, 'destination_facilities');
    }
}
