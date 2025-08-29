<?php

namespace Database\Seeders;

use App\Models\Reward;
use Illuminate\Database\Seeder;

class RewardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rewards = [
            // Stickers
            [
                'reward_id' => 'stk-phao',
                'name' => 'Phao cứu hộ',
                'emoji' => '🛟',
                'type' => Reward::TYPE_STICKER,
                'points' => 10,
                'description' => 'Sticker phao cứu hộ dễ thương'
            ],
            [
                'reward_id' => 'stk-ca-heo',
                'name' => 'Bạn cá heo',
                'emoji' => '🐬',
                'type' => Reward::TYPE_STICKER,
                'points' => 15,
                'description' => 'Sticker cá heo thông minh'
            ],
            [
                'reward_id' => 'stk-sao-bien',
                'name' => 'Sao biển',
                'emoji' => '⭐',
                'type' => Reward::TYPE_STICKER,
                'points' => 20,
                'description' => 'Sticker sao biển lấp lánh'
            ],
            [
                'reward_id' => 'stk-cua',
                'name' => 'Cua biển',
                'emoji' => '🦀',
                'type' => Reward::TYPE_STICKER,
                'points' => 25,
                'description' => 'Sticker cua biển vui nhộn'
            ],
            [
                'reward_id' => 'stk-rong',
                'name' => 'Rồng biển',
                'emoji' => '🐉',
                'type' => Reward::TYPE_STICKER,
                'points' => 30,
                'description' => 'Sticker rồng biển huyền thoại'
            ],
            [
                'reward_id' => 'stk-ca-voi',
                'name' => 'Cá voi xanh',
                'emoji' => '🐋',
                'type' => Reward::TYPE_STICKER,
                'points' => 35,
                'description' => 'Sticker cá voi xanh khổng lồ'
            ],
            [
                'reward_id' => 'stk-thuy-cung',
                'name' => 'Thủy cung',
                'emoji' => '🏛️',
                'type' => Reward::TYPE_STICKER,
                'points' => 40,
                'description' => 'Sticker thủy cung tuyệt đẹp'
            ],

            // Badges  
            [
                'reward_id' => 'bd-swimmer',
                'name' => 'Vận động viên bơi lội',
                'emoji' => '🏊‍♂️',
                'type' => Reward::TYPE_BADGE,
                'points' => 40,
                'description' => 'Huy hiệu cho người bơi giỏi'
            ],
            [
                'reward_id' => 'bd-hero',
                'name' => 'Người hùng an toàn nước',
                'emoji' => '🏅',
                'type' => Reward::TYPE_BADGE,
                'points' => 50,
                'description' => 'Huy hiệu danh dự cao nhất'
            ],
            [
                'reward_id' => 'bd-lifeguard',
                'name' => 'Cứu hộ viên',
                'emoji' => '🚑',
                'type' => Reward::TYPE_BADGE,
                'points' => 60,
                'description' => 'Huy hiệu cứu hộ viên chuyên nghiệp'
            ],
            [
                'reward_id' => 'bd-teacher',
                'name' => 'Giáo viên an toàn',
                'emoji' => '👨‍🏫',
                'type' => Reward::TYPE_BADGE,
                'points' => 70,
                'description' => 'Huy hiệu cho người chia sẻ kiến thức'
            ],
            [
                'reward_id' => 'bd-champion',
                'name' => 'Nhà vô địch',
                'emoji' => '🏆',
                'type' => Reward::TYPE_BADGE,
                'points' => 100,
                'description' => 'Huy hiệu dành cho nhà vô địch'
            ],


        ];

        foreach ($rewards as $reward) {
            Reward::updateOrCreate(
                ['reward_id' => $reward['reward_id']],
                $reward
            );
        }
    }
}