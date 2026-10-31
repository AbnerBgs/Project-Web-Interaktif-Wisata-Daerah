<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'destination_id', 'user_id', 'reviewer_name', 'reviewer_email',
        'rating', 'title', 'comment', 'status', 'admin_note', 'moderated_by', 'moderated_at',
    ];

    protected $casts = ['moderated_at' => 'datetime'];

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function moderator()
    {
        return $this->belongsTo(User::class, 'moderated_by');
    }

    // Scope untuk tiap status moderasi
    public function scopePending($query)   { return $query->where('status', 'pending'); }
    public function scopeApproved($query)  { return $query->where('status', 'approved'); }
    public function scopeRejected($query)  { return $query->where('status', 'rejected'); }
}
