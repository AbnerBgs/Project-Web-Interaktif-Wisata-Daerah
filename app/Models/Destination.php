<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Destination extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'user_id', 'name', 'name_en', 'slug', 'description', 'description_en',
        'address', 'region', 'province', 'latitude', 'longitude', 'thumbnail',
        'open_hours', 'ticket_price', 'ticket_price_en', 'phone', 'website',
        'rating_avg', 'review_count', 'view_count', 'is_popular', 'is_active', 'meta',
    ];

    protected $casts = [
        'latitude'   => 'float',
        'longitude'  => 'float',
        'is_popular' => 'boolean',
        'is_active'  => 'boolean',
        'meta'       => 'array',
    ];

    // ── Relationships ──────────────────────────────────────────────

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->hasMany(DestinationImage::class)->orderBy('sort_order');
    }

    public function coverImage()
    {
        return $this->hasOne(DestinationImage::class)->where('is_cover', true);
    }

    public function facilities()
    {
        return $this->belongsToMany(Facility::class, 'destination_facilities');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews()
    {
        return $this->hasMany(Review::class)->where('status', 'approved');
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    // ── Helpers ────────────────────────────────────────────────────

    /**
     * Nama destinasi sesuai bahasa aktif aplikasi.
     */
    public function getLocalizedNameAttribute(): string
    {
        $lang = app()->getLocale();
        return ($lang === 'en' && $this->name_en) ? $this->name_en : $this->name;
    }

    /**
     * Deskripsi sesuai bahasa aktif.
     */
    public function getLocalizedDescriptionAttribute(): string
    {
        $lang = app()->getLocale();
        return ($lang === 'en' && $this->description_en) ? $this->description_en : $this->description;
    }

    /**
     * Hitung jarak (km) antara destinasi dengan koordinat pengunjung.
     * Menggunakan formula Haversine.
     *
     * @param float $lat Latitude pengunjung
     * @param float $lng Longitude pengunjung
     * @return float Jarak dalam km
     */
    public function distanceTo(float $lat, float $lng): float
    {
        $earthRadius = 6371; // km
        $dLat = deg2rad($this->latitude - $lat);
        $dLng = deg2rad($this->longitude - $lng);
        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat)) * cos(deg2rad($this->latitude)) * sin($dLng / 2) ** 2;
        return round($earthRadius * 2 * asin(sqrt($a)), 2);
    }

    /**
     * Recalculate dan simpan rating rata-rata dari ulasan yang disetujui.
     * Dipanggil setiap kali review diapprove/reject.
     */
    public function recalculateRating(): void
    {
        $approved = $this->approvedReviews();
        $avg   = $approved->avg('rating') ?? 0;
        $count = $approved->count();
        $this->update([
            'rating_avg'   => round($avg),
            'review_count' => $count,
        ]);
    }

    /**
     * Scope: destinasi aktif saja
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: filter berdasarkan kategori
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Scope: filter berdasarkan wilayah/region
     */
    public function scopeByRegion($query, $region)
    {
        return $query->where('region', $region);
    }

    /**
     * Scope: cari berdasarkan keyword (nama atau deskripsi)
     */
    public function scopeSearch($query, string $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('name', 'like', "%{$keyword}%")
              ->orWhere('description', 'like', "%{$keyword}%")
              ->orWhere('region', 'like', "%{$keyword}%");
        });
    }
}
