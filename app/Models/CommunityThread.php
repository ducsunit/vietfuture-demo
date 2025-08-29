<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityThread extends Model
{
    use HasFactory;

    protected $fillable = [
        'thread_uid',
        'user_id',
        'title',
        'content',
        'author',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(CommunityComment::class, 'thread_id')->orderBy('created_at');
    }
}
