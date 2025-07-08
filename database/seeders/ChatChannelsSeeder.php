<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChatChannelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First, ensure we have a user to set as creator
        $adminUser = DB::table('users')->first();
        if (!$adminUser) {
            // Create a system admin user if none exists
            $adminUserId = DB::table('users')->insertGetId([
                'name' => 'System Admin',
                'email' => 'admin@optionrocket.com',
                'email_verified_at' => now(),
                'password' => bcrypt('password'),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $adminUserId = $adminUser->id;
        }

        $channels = [
            [
                'name' => 'General Trading',
                'slug' => 'general-trading',
                'description' => 'General trading discussions and market talk',
                'type' => 'public',
                'color' => '#3B82F6',
                'icon' => 'chart-line',
                'is_active' => true,
                'requires_premium' => false,
                'max_members' => null,
                'members_count' => 0,
                'messages_count' => 0,
                'created_by' => $adminUserId,
                'moderators' => json_encode([$adminUserId]),
                'settings' => json_encode([
                    'allow_file_uploads' => true,
                    'allow_reactions' => true,
                    'message_retention_days' => 365
                ]),
                'last_message_at' => null,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Options Flow',
                'slug' => 'options-flow',
                'description' => 'Real-time options flow and unusual activity alerts',
                'type' => 'premium',
                'color' => '#10B981',
                'icon' => 'trending-up',
                'is_active' => true,
                'requires_premium' => true,
                'max_members' => null,
                'members_count' => 0,
                'messages_count' => 0,
                'created_by' => $adminUserId,
                'moderators' => json_encode([$adminUserId]),
                'settings' => json_encode([
                    'allow_file_uploads' => true,
                    'allow_reactions' => true,
                    'message_retention_days' => 365
                ]),
                'last_message_at' => null,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Market News',
                'slug' => 'market-news',
                'description' => 'Breaking market news and economic updates',
                'type' => 'public',
                'color' => '#F59E0B',
                'icon' => 'newspaper',
                'is_active' => true,
                'requires_premium' => false,
                'max_members' => null,
                'members_count' => 0,
                'messages_count' => 0,
                'created_by' => $adminUserId,
                'moderators' => json_encode([$adminUserId]),
                'settings' => json_encode([
                    'allow_file_uploads' => false,
                    'allow_reactions' => true,
                    'message_retention_days' => 90
                ]),
                'last_message_at' => null,
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Earnings Plays',
                'slug' => 'earnings-plays',
                'description' => 'Earnings season strategies and plays',
                'type' => 'premium',
                'color' => '#8B5CF6',
                'icon' => 'calendar',
                'is_active' => true,
                'requires_premium' => true,
                'max_members' => null,
                'members_count' => 0,
                'messages_count' => 0,
                'created_by' => $adminUserId,
                'moderators' => json_encode([$adminUserId]),
                'settings' => json_encode([
                    'allow_file_uploads' => true,
                    'allow_reactions' => true,
                    'message_retention_days' => 365
                ]),
                'last_message_at' => null,
                'sort_order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'SPY/QQQ Discussion',
                'slug' => 'spy-qqq-discussion',
                'description' => 'SPY and QQQ focused trading discussions',
                'type' => 'public',
                'color' => '#EF4444',
                'icon' => 'chart-bar',
                'is_active' => true,
                'requires_premium' => false,
                'max_members' => null,
                'members_count' => 0,
                'messages_count' => 0,
                'created_by' => $adminUserId,
                'moderators' => json_encode([$adminUserId]),
                'settings' => json_encode([
                    'allow_file_uploads' => true,
                    'allow_reactions' => true,
                    'message_retention_days' => 365
                ]),
                'last_message_at' => null,
                'sort_order' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Beginners Corner',
                'slug' => 'beginners-corner',
                'description' => 'Safe space for new traders to ask questions',
                'type' => 'public',
                'color' => '#06B6D4',
                'icon' => 'academic-cap',
                'is_active' => true,
                'requires_premium' => false,
                'max_members' => null,
                'members_count' => 0,
                'messages_count' => 0,
                'created_by' => $adminUserId,
                'moderators' => json_encode([$adminUserId]),
                'settings' => json_encode([
                    'allow_file_uploads' => false,
                    'allow_reactions' => true,
                    'message_retention_days' => 365,
                    'moderation_level' => 'strict'
                ]),
                'last_message_at' => null,
                'sort_order' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'VIP Elite',
                'slug' => 'vip-elite',
                'description' => 'Exclusive channel for Elite tier members',
                'type' => 'private',
                'color' => '#F97316',
                'icon' => 'star',
                'is_active' => true,
                'requires_premium' => true,
                'max_members' => 100,
                'members_count' => 0,
                'messages_count' => 0,
                'created_by' => $adminUserId,
                'moderators' => json_encode([$adminUserId]),
                'settings' => json_encode([
                    'allow_file_uploads' => true,
                    'allow_reactions' => true,
                    'message_retention_days' => -1, // never delete
                    'invite_only' => true
                ]),
                'last_message_at' => null,
                'sort_order' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Support',
                'slug' => 'support',
                'description' => 'Platform support and technical help',
                'type' => 'public',
                'color' => '#6B7280',
                'icon' => 'support',
                'is_active' => true,
                'requires_premium' => false,
                'max_members' => null,
                'members_count' => 0,
                'messages_count' => 0,
                'created_by' => $adminUserId,
                'moderators' => json_encode([$adminUserId]),
                'settings' => json_encode([
                    'allow_file_uploads' => true,
                    'allow_reactions' => false,
                    'message_retention_days' => 90,
                    'support_channel' => true
                ]),
                'last_message_at' => null,
                'sort_order' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('chat_channels')->insert($channels);
    }
}
