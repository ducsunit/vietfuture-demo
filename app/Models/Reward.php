<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    use HasFactory;

    protected $fillable = [
        'reward_id',
        'name',
        'emoji',
        'type',
        'points',
        'description',
        'is_active'
    ];

    protected $casts = [
        'points' => 'integer',
        'is_active' => 'boolean',
    ];

    // Define reward types
    const TYPE_STICKER = 'sticker';
    const TYPE_BADGE = 'badge';

    // Relationships
    public function userRewards()
    {
        return $this->hasMany(UserReward::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_rewards')->withTimestamps();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeStickers($query)
    {
        return $query->byType(self::TYPE_STICKER);
    }

    public function scopeBadges($query)
    {
        return $query->byType(self::TYPE_BADGE);
    }



    // Helper methods
    public function isOwnedByUser($userId)
    {
        return $this->userRewards()->where('user_id', $userId)->exists();
    }

    public function canBeAffordedBy($userPoints)
    {
        return $userPoints >= $this->points;
    }
}