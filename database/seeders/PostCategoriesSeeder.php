<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Options Trading',
                'slug' => 'options-trading',
                'description' => 'Everything about options trading strategies, analysis, and techniques',
                'color' => '#3B82F6', // blue
                'is_active' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Market Analysis',
                'slug' => 'market-analysis',
                'description' => 'Daily market analysis, trends, and forecasts',
                'color' => '#10B981', // green
                'is_active' => true,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Trading Strategies',
                'slug' => 'trading-strategies',
                'description' => 'Proven trading strategies and methodologies',
                'color' => '#F59E0B', // amber
                'is_active' => true,
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Risk Management',
                'slug' => 'risk-management',
                'description' => 'Risk management techniques and portfolio protection',
                'color' => '#EF4444', // red
                'is_active' => true,
                'sort_order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Technical Analysis',
                'slug' => 'technical-analysis',
                'description' => 'Chart patterns, indicators, and technical trading methods',
                'color' => '#8B5CF6', // purple
                'is_active' => true,
                'sort_order' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Earnings Plays',
                'slug' => 'earnings-plays',
                'description' => 'Earnings-focused trading strategies and analysis',
                'color' => '#06B6D4', // cyan
                'is_active' => true,
                'sort_order' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Market News',
                'slug' => 'market-news',
                'description' => 'Breaking market news and economic updates',
                'color' => '#84CC16', // lime
                'is_active' => true,
                'sort_order' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Education',
                'slug' => 'education',
                'description' => 'Educational content for traders at all levels',
                'color' => '#F97316', // orange
                'is_active' => true,
                'sort_order' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Crypto Trading',
                'slug' => 'crypto-trading',
                'description' => 'Cryptocurrency trading and analysis',
                'color' => '#FBBF24', // yellow
                'is_active' => true,
                'sort_order' => 9,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Forex',
                'slug' => 'forex',
                'description' => 'Foreign exchange trading and currency analysis',
                'color' => '#14B8A6', // teal
                'is_active' => true,
                'sort_order' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Swing Trading',
                'slug' => 'swing-trading',
                'description' => 'Swing trading strategies and multi-day positions',
                'color' => '#EC4899', // pink
                'is_active' => true,
                'sort_order' => 11,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Day Trading',
                'slug' => 'day-trading',
                'description' => 'Intraday trading strategies and techniques',
                'color' => '#6366F1', // indigo
                'is_active' => true,
                'sort_order' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Psychology',
                'slug' => 'psychology',
                'description' => 'Trading psychology and mental game',
                'color' => '#8B5A2B', // brown
                'is_active' => true,
                'sort_order' => 13,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tools & Resources',
                'slug' => 'tools-resources',
                'description' => 'Trading tools, platforms, and resources',
                'color' => '#6B7280', // gray
                'is_active' => true,
                'sort_order' => 14,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Community',
                'slug' => 'community',
                'description' => 'Community discussions and member spotlights',
                'color' => '#DC2626', // red-600
                'is_active' => true,
                'sort_order' => 15,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('post_categories')->insert($categories);
    }
}
