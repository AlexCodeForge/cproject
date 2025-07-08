<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubscriptionPlansSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Free',
                'slug' => 'free',
                'description' => 'Basic access to OptionRocket platform with limited features',
                'monthly_price' => 0.00,
                'yearly_price' => 0.00,
                'stripe_monthly_price_id' => null,
                'stripe_yearly_price_id' => null,
                'features' => json_encode([
                    'Basic trading alerts',
                    'Community chat access',
                    'Limited post views (10/month)',
                    'Basic market analysis',
                    'Email support'
                ]),
                'max_alerts_per_month' => 10,
                'max_courses' => 0,
                'premium_chat_access' => false,
                'premium_events_access' => false,
                'advanced_analytics' => false,
                'priority_support' => false,
                'is_active' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Starter',
                'slug' => 'starter',
                'description' => 'Perfect for individual traders getting started with options trading',
                'monthly_price' => 29.99,
                'yearly_price' => 287.90, // 20% discount
                'stripe_monthly_price_id' => 'price_starter_monthly',
                'stripe_yearly_price_id' => 'price_starter_yearly',
                'features' => json_encode([
                    'Unlimited basic trading alerts',
                    'All community chat channels',
                    'Unlimited post views',
                    'Basic market analysis',
                    'Email support',
                    'Mobile app access',
                    'Trading journal basics'
                ]),
                'max_alerts_per_month' => null, // unlimited
                'max_courses' => 3,
                'premium_chat_access' => false,
                'premium_events_access' => false,
                'advanced_analytics' => false,
                'priority_support' => false,
                'is_active' => true,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pro',
                'slug' => 'pro',
                'description' => 'Advanced features for serious traders and active investors',
                'monthly_price' => 79.99,
                'yearly_price' => 719.91, // 25% discount
                'stripe_monthly_price_id' => 'price_pro_monthly',
                'stripe_yearly_price_id' => 'price_pro_yearly',
                'features' => json_encode([
                    'All Starter features',
                    'Premium trading alerts',
                    'Advanced market analysis',
                    'Real-time options flow',
                    'Custom watchlists',
                    'Priority support',
                    'Advanced trading journal',
                    'Profit & Loss tracking',
                    'Risk management tools'
                ]),
                'max_alerts_per_month' => null, // unlimited
                'max_courses' => null, // unlimited
                'premium_chat_access' => true,
                'premium_events_access' => true,
                'advanced_analytics' => true,
                'priority_support' => true,
                'is_active' => true,
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Elite',
                'slug' => 'elite',
                'description' => 'Premium tier for professional traders and fund managers',
                'monthly_price' => 199.99,
                'yearly_price' => 1799.91, // 25% discount
                'stripe_monthly_price_id' => 'price_elite_monthly',
                'stripe_yearly_price_id' => 'price_elite_yearly',
                'features' => json_encode([
                    'All Pro features',
                    'Exclusive VIP alerts',
                    'One-on-one mentoring sessions',
                    'Custom trading strategies',
                    'Advanced analytics dashboard',
                    'API access',
                    'White-label solutions',
                    'Priority customer success manager',
                    'Institutional-grade tools'
                ]),
                'max_alerts_per_month' => null, // unlimited
                'max_courses' => null, // unlimited
                'premium_chat_access' => true,
                'premium_events_access' => true,
                'advanced_analytics' => true,
                'priority_support' => true,
                'is_active' => true,
                'sort_order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('subscription_plans')->insert($plans);
    }
}
