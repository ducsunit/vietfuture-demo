<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class User extends Model
{
    use HasFactory;

    protected $fillable = [
        'username', 'password', 'age', 'role', 'point', 'display_name'
    ];

    protected $hidden = ['password'];

    public function setPasswordAttribute($value): void
    {
        $this->attributes['password'] = Hash::make($value);
    }

    // Relationships
    public function progressRecords()
    {
        return $this->hasMany(ProgressRecord::class);
    }

    public function communityThreads()
    {
        return $this->hasMany(CommunityThread::class);
    }

    public function communityComments()
    {
        return $this->hasMany(CommunityComment::class);
    }
}

// Removed duplicated class from Breeze/Starter stub
