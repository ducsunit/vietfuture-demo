<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class User extends Model
{
    use HasFactory;

    protected $fillable = [
        'username',
        'password',
        'age',
        'role',
        'point',
        'display_name'
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

    public function userRewards()
    {
        return $this->hasMany(UserReward::class);
    }

    public function rewards()
    {
        return $this->belongsToMany(Reward::class, 'user_rewards')->withTimestamps();
    }

    // Helper methods for rewards
    public function hasReward($rewardId)
    {
        return $this->userRewards()->whereHas('reward', function ($q) use ($rewardId) {
            $q->where('reward_id', $rewardId);
        })->exists();
    }

    public function getEquippedBackground()
    {
        return $this->userRewards()
            ->equipped()
            ->byType(Reward::TYPE_BACKGROUND)
            ->with('reward')
            ->first();
    }

    public function getEquippedBadge()
    {
        return $this->userRewards()
            ->equipped()
            ->byType(Reward::TYPE_BADGE)
            ->with('reward')
            ->first();
    }

    public function getEquippedBadges()
    {
        return $this->userRewards()
            ->equipped()
            ->byType(Reward::TYPE_BADGE)
            ->with('reward')
            ->get();
    }

    public function canAffordReward($points)
    {
        return $this->point >= $points;
    }

    public function spendPoints($amount)
    {
        if ($this->point >= $amount) {
            $this->decrement('point', $amount);
            return true;
        }
        return false;
    }
}

// Removed duplicated class from Breeze/Starter stub
