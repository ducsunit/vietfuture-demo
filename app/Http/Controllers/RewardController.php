<?php

namespace App\Http\Controllers;

use App\Models\Reward;
use App\Models\User;
use App\Models\UserReward;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RewardController extends Controller
{
    /**
     * Get all available rewards grouped by type
     */
    public function index(Request $request): JsonResponse
    {
        $userId = session('user_id');
        $user = $userId ? User::find($userId) : null;

        $rewards = Reward::active()->get()->groupBy('type');
        
        $response = [];
        foreach ($rewards as $type => $typeRewards) {
            $response[$type] = $typeRewards->map(function ($reward) use ($user) {
                return [
                    'id' => $reward->reward_id,
                    'name' => $reward->name,
                    'emoji' => $reward->emoji,
                    'points' => $reward->points,
                    'description' => $reward->description,
                    'is_owned' => $user ? $user->hasReward($reward->reward_id) : false,
                    'can_afford' => $user ? $user->canAffordReward($reward->points) : false,
                ];
            });
        }

        return response()->json([
            'rewards' => $response,
            'user_points' => $user ? $user->point : 0
        ]);
    }

    /**
     * Get user's owned rewards
     */
    public function getUserRewards(Request $request): JsonResponse
    {
        $userId = session('user_id');
        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = User::find($userId);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $userRewards = $user->userRewards()->with('reward')->get();
        
        $grouped = $userRewards->groupBy('reward.type');
        
        $response = [];
        foreach ($grouped as $type => $rewards) {
            $response[$type] = $rewards->map(function ($userReward) {
                return [
                    'id' => $userReward->reward->reward_id,
                    'name' => $userReward->reward->name,
                    'emoji' => $userReward->reward->emoji,
                    'purchased_at' => $userReward->purchased_at,
                    'is_equipped' => $userReward->is_equipped,
                ];
            });
        }

        return response()->json([
            'rewards' => $response,
            'user_points' => $user->point
        ]);
    }

    /**
     * Purchase a reward
     */
    public function purchase(Request $request): JsonResponse
    {
        $userId = session('user_id');
        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'reward_id' => 'required|string'
        ]);

        $user = User::find($userId);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $reward = Reward::where('reward_id', $request->reward_id)
                        ->where('is_active', true)
                        ->first();

        if (!$reward) {
            return response()->json(['error' => 'Reward not found'], 404);
        }

        // Check if user already owns this reward
        if ($user->hasReward($reward->reward_id)) {
            return response()->json(['error' => 'You already own this reward'], 400);
        }

        // Check if user has enough points
        if (!$user->canAffordReward($reward->points)) {
            return response()->json(['error' => 'Not enough points'], 400);
        }

        // Purchase the reward
        if ($user->spendPoints($reward->points)) {
            UserReward::create([
                'user_id' => $user->id,
                'reward_id' => $reward->id,
                'purchased_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => "Successfully purchased {$reward->name}!",
                'user_points' => $user->fresh()->point
            ]);
        }

        return response()->json(['error' => 'Purchase failed'], 500);
    }

    /**
     * Equip/unequip a reward (for backgrounds)
     */
    public function equip(Request $request): JsonResponse
    {
        $userId = session('user_id');
        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'reward_id' => 'required|string',
            'action' => 'required|in:equip,unequip'
        ]);

        $user = User::find($userId);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $userReward = $user->userRewards()
            ->whereHas('reward', function ($q) use ($request) {
                $q->where('reward_id', $request->reward_id);
            })
            ->first();

        if (!$userReward) {
            return response()->json(['error' => 'You do not own this reward'], 400);
        }

        if ($request->action === 'equip') {
            $userReward->equip();
            $message = "Equipped {$userReward->reward->name}";
        } else {
            $userReward->unequip();
            $message = "Unequipped {$userReward->reward->name}";
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    /**
     * Get user's equipped background for applying to interface
     */
    public function getEquippedBackground(Request $request): JsonResponse
    {
        $userId = session('user_id');
        if (!$userId) {
            return response()->json(['background' => null]);
        }

        $user = User::find($userId);
        if (!$user) {
            return response()->json(['background' => null]);
        }

        $equippedBackground = $user->getEquippedBackground();
        
        if ($equippedBackground) {
            return response()->json([
                'background' => [
                    'id' => $equippedBackground->reward->reward_id,
                    'name' => $equippedBackground->reward->name,
                    'emoji' => $equippedBackground->reward->emoji,
                ]
            ]);
        }

        return response()->json(['background' => null]);
    }
}