<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunityComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'comment_uid',
        'thread_id',
        'user_id',
        'content',
        'author',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function thread()
    {
        return $this->belongsTo(CommunityThread::class, 'thread_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
