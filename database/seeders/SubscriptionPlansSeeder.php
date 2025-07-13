<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\SubscriptionPlan;

class SubscriptionPlansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear the table before seeding
        SubscriptionPlan::truncate();

        SubscriptionPlan::create([
            'name' => 'Premium',
            'slug' => 'premium',
            'description' => 'Unlock all premium features, including exclusive analysis, trading signals, and professional tools.',
            'monthly_price' => 28.00,
            'yearly_price' => 280.00,
            'stripe_monthly_price_id' => 'price_1RiTgBBBlYDJOOlgyqRTNpic',
            'stripe_yearly_price_id' => 'price_1RiTfpBBlYDJOOlgKEgmWOjg',
            'features' => json_encode([
                'Everything in the Regular plan',
                'Premium Alerts and Signals',
                'Access to Exclusive Chat Channels',
                'Live Sessions and Recordings',
                'All Courses and Analysis'
            ]),
            'max_alerts_per_month' => null, // unlimited
            'max_courses' => null, // unlimited
            'premium_chat_access' => true,
            'premium_events_access' => true,
            'advanced_analytics' => true,
            'priority_support' => true,
            'is_active' => true,
            'sort_order' => 1,
        ]);
    }
}
