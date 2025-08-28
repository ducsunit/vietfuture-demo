<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserReward extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reward_id',
        'purchased_at',
        'is_equipped'
    ];

    protected $casts = [
        'purchased_at' => 'datetime',
        'is_equipped' => 'boolean',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reward()
    {
        return $this->belongsTo(Reward::class);
    }

    // Scopes
    public function scopeEquipped($query)
    {
        return $query->where('is_equipped', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->whereHas('reward', function ($q) use ($type) {
            $q->where('type', $type);
        });
    }

    // Helper methods
    public function equip()
    {
        // Unequip other items of the same type for this user (exclusive per type)
        // Backgrounds remain exclusive; badges can be equipped in multiples
        if ($this->reward->type === Reward::TYPE_BACKGROUND) {
            self::where('user_id', $this->user_id)
                ->byType(Reward::TYPE_BACKGROUND)
                ->where('id', '!=', $this->id)
                ->update(['is_equipped' => false]);
        }

        $this->update(['is_equipped' => true]);
    }

    public function unequip()
    {
        $this->update(['is_equipped' => false]);
    }
}
