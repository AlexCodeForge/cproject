# OptionRocket Database Schema Documentation

## Overview

This document provides comprehensive documentation for the OptionRocket database schema, including entity definitions, relationships, constraints, indexing strategies, and migration processes.

## Database Architecture

- **Database Engine**: MySQL (production), SQLite (local development)
- **ORM**: Laravel Eloquent
- **Migration System**: Laravel Migrations
- **Seeding**: Laravel Seeders
- **Indexes**: Optimized for query performance
- **Constraints**: Full referential integrity enforcement

## Entity Relationship Overview

The OptionRocket database consists of 11 core entity groups:

1. **User Management**: Users, User Profiles
2. **Subscription & Billing**: Subscription Plans, User Subscriptions (via Laravel Cashier)
3. **Role & Permission**: Roles, Permissions, Model Associations (via Spatie)
4. **Content Management**: Posts, Post Categories, Post Interactions
5. **Trading Alert**: Trading Alerts, User Alert Subscriptions
6. **Chat System**: Chat Channels, Chat Messages, Chat Participants
7. **Event Management**: Events, Event Registrations
8. **Learning Management**: Courses, Course Enrollments
9. **P&L Tracking**: Integrated within Trading Alerts
10. **Notification**: Integrated within various entities
11. **System**: Cache, Jobs, Sessions (Laravel defaults)

## Core Tables

### User Management

#### users
**Purpose**: Core user authentication and basic information
```sql
- id: Primary Key
- name: User's full name
- email: Unique email address
- email_verified_at: Email verification timestamp
- password: Hashed password
- remember_token: Session token
- created_at, updated_at: Timestamps
```

**Indexes**: 
- Primary key on `id`
- Unique index on `email`

#### user_profiles
**Purpose**: Extended user profile information and preferences
```sql
- id: Primary Key
- user_id: Foreign Key to users table
- avatar: Profile picture path
- bio: User biography
- location: User's location
- website: Personal website URL
- twitter_handle: Twitter username
- linkedin_url: LinkedIn profile URL
- trading_experience: ENUM ['beginner', 'intermediate', 'advanced', 'expert']
- trading_interests: JSON array of interests
- portfolio_value: Decimal portfolio value
- public_profile: Boolean visibility setting
- show_portfolio: Boolean portfolio visibility
- timezone: User's timezone
- language: Preferred language
- notification_preferences: JSON notification settings
- last_active_at: Last activity timestamp
- created_at, updated_at: Timestamps
```

**Indexes**:
- `user_id` (foreign key)
- `trading_experience`
- `public_profile`
- `last_active_at`

**Constraints**:
- Foreign key constraint on `user_id` with CASCADE delete

### Subscription & Billing

#### subscription_plans
**Purpose**: Define available subscription tiers and pricing
```sql
- id: Primary Key
- name: Plan name (e.g., 'Free', 'Premium', 'Pro')
- slug: URL-friendly identifier
- description: Plan description
- monthly_price: Monthly pricing (decimal)
- yearly_price: Annual pricing (decimal)
- stripe_monthly_price_id: Stripe price ID for monthly billing
- stripe_yearly_price_id: Stripe price ID for yearly billing
- features: JSON array of included features
- max_alerts_per_month: Alert limit
- max_courses: Course access limit
- premium_chat_access: Boolean premium chat access
- premium_events_access: Boolean premium event access
- advanced_analytics: Boolean advanced analytics access
- priority_support: Boolean priority support access
- is_active: Boolean plan availability
- sort_order: Display order
- created_at, updated_at: Timestamps
```

**Indexes**:
- `slug` (unique)
- `is_active`
- `sort_order`

### Content Management

#### post_categories
**Purpose**: Categorize posts and content
```sql
- id: Primary Key
- name: Category name
- slug: URL-friendly identifier
- description: Category description
- color: Display color
- icon: Category icon
- is_active: Boolean category availability
- sort_order: Display order
- created_at, updated_at: Timestamps
```

**Indexes**:
- `slug` (unique)
- `is_active`
- `sort_order`

#### posts
**Purpose**: Main content management for articles and posts
```sql
- id: Primary Key
- user_id: Foreign Key to users table (author)
- post_category_id: Foreign Key to post_categories table
- title: Post title
- slug: URL-friendly identifier
- excerpt: Post summary
- content: Full post content (longText)
- featured_image: Featured image path
- images: JSON array of additional images
- status: ENUM ['draft', 'published', 'archived']
- is_premium: Boolean premium content flag
- is_featured: Boolean featured content flag
- tags: JSON array of tags
- meta_title: SEO title
- meta_description: SEO description
- views_count: View counter
- likes_count: Like counter
- comments_count: Comment counter
- shares_count: Share counter
- published_at: Publication timestamp
- reading_time: Estimated reading time (minutes)
- difficulty_level: Content difficulty (1.0-5.0)
- created_at, updated_at: Timestamps
```

**Indexes**:
- `user_id`
- `post_category_id`
- `slug` (unique)
- `status` + `published_at` (composite)
- `is_premium`
- `is_featured`
- `views_count`
- `likes_count`

#### post_interactions
**Purpose**: Track user interactions with posts
```sql
- id: Primary Key
- user_id: Foreign Key to users table
- post_id: Foreign Key to posts table
- interaction_type: ENUM ['like', 'comment', 'share', 'bookmark']
- interaction_data: JSON additional data
- created_at, updated_at: Timestamps
```

**Indexes**:
- `user_id`
- `post_id`
- `interaction_type`
- `user_id` + `post_id` + `interaction_type` (composite unique)

### Trading Alert System

#### trading_alerts
**Purpose**: Core trading alert functionality
```sql
- id: Primary Key
- user_id: Foreign Key to users table (alert creator)
- symbol: Stock/crypto symbol
- company_name: Company name
- alert_type: ENUM ['buy', 'sell', 'hold', 'watch']
- market_type: ENUM ['stocks', 'options', 'crypto', 'forex', 'commodities']
- entry_price: Target entry price
- target_price: Price target
- stop_loss: Stop loss price
- current_price: Current market price
- analysis: Alert analysis text
- reasoning: Alert reasoning
- risk_level: ENUM ['low', 'medium', 'high']
- time_frame: ENUM ['intraday', 'swing', 'position']
- status: ENUM ['active', 'triggered', 'closed', 'expired', 'cancelled']
- actual_entry_price: Actual entry price
- actual_exit_price: Actual exit price
- profit_loss: Profit/loss amount
- profit_loss_percentage: Profit/loss percentage
- is_premium: Boolean premium alert flag
- is_featured: Boolean featured alert flag
- triggered_at: Alert trigger timestamp
- closed_at: Alert close timestamp
- expires_at: Alert expiration timestamp
- views_count: View counter
- followers_count: Follower counter
- technical_indicators: JSON technical analysis data
- chart_image: Chart image path
- created_at, updated_at: Timestamps
```

**Indexes**:
- `user_id`
- `symbol`
- `alert_type`
- `market_type`
- `status`
- `is_premium`
- `status` + `created_at` (composite)
- `risk_level`
- `time_frame`

#### user_alert_subscriptions
**Purpose**: Track user subscriptions to specific alerts
```sql
- id: Primary Key
- user_id: Foreign Key to users table
- trading_alert_id: Foreign Key to trading_alerts table
- notification_enabled: Boolean notification setting
- created_at, updated_at: Timestamps
```

**Indexes**:
- `user_id`
- `trading_alert_id`
- `user_id` + `trading_alert_id` (composite unique)

### Chat System

#### chat_channels
**Purpose**: Define chat channels and rooms
```sql
- id: Primary Key
- name: Channel name
- slug: URL-friendly identifier
- description: Channel description
- is_premium: Boolean premium channel flag
- is_private: Boolean private channel flag
- is_active: Boolean channel availability
- max_participants: Maximum participants
- created_by: Foreign Key to users table
- created_at, updated_at: Timestamps
```

**Indexes**:
- `slug` (unique)
- `is_premium`
- `is_private`
- `is_active`
- `created_by`

#### chat_messages
**Purpose**: Store chat messages
```sql
- id: Primary Key
- chat_channel_id: Foreign Key to chat_channels table
- user_id: Foreign Key to users table
- message: Message content
- message_type: ENUM ['text', 'image', 'file', 'system']
- attachment_path: File attachment path
- is_edited: Boolean edit flag
- edited_at: Edit timestamp
- created_at, updated_at: Timestamps
```

**Indexes**:
- `chat_channel_id`
- `user_id`
- `message_type`
- `chat_channel_id` + `created_at` (composite)

#### chat_participants
**Purpose**: Track channel membership
```sql
- id: Primary Key
- chat_channel_id: Foreign Key to chat_channels table
- user_id: Foreign Key to users table
- role: ENUM ['member', 'moderator', 'admin']
- joined_at: Join timestamp
- last_read_at: Last read timestamp
- is_muted: Boolean mute setting
- created_at, updated_at: Timestamps
```

**Indexes**:
- `chat_channel_id`
- `user_id`
- `role`
- `chat_channel_id` + `user_id` (composite unique)

### Event Management

#### events
**Purpose**: Manage premium and free events
```sql
- id: Primary Key
- title: Event title
- slug: URL-friendly identifier
- description: Event description
- start_date: Event start date/time
- end_date: Event end date/time
- timezone: Event timezone
- is_premium: Boolean premium event flag
- is_featured: Boolean featured event flag
- max_attendees: Maximum attendees
- current_attendees: Current attendee count
- price: Event price
- location: Event location (or virtual)
- speaker_name: Speaker name
- speaker_bio: Speaker biography
- speaker_image: Speaker image path
- status: ENUM ['draft', 'published', 'cancelled', 'completed']
- registration_deadline: Registration deadline
- created_at, updated_at: Timestamps
```

**Indexes**:
- `slug` (unique)
- `start_date`
- `is_premium`
- `is_featured`
- `status`
- `start_date` + `status` (composite)

#### event_registrations
**Purpose**: Track event registrations
```sql
- id: Primary Key
- event_id: Foreign Key to events table
- user_id: Foreign Key to users table
- registration_status: ENUM ['registered', 'confirmed', 'cancelled', 'attended']
- payment_status: ENUM ['pending', 'paid', 'failed', 'refunded']
- payment_amount: Payment amount
- notes: Registration notes
- created_at, updated_at: Timestamps
```

**Indexes**:
- `event_id`
- `user_id`
- `registration_status`
- `payment_status`
- `event_id` + `user_id` (composite unique)

### Learning Management System

#### courses
**Purpose**: Manage course catalog
```sql
- id: Primary Key
- title: Course title
- slug: URL-friendly identifier
- description: Course description
- thumbnail: Course thumbnail image
- is_premium: Boolean premium course flag
- is_featured: Boolean featured course flag
- difficulty_level: ENUM ['beginner', 'intermediate', 'advanced']
- duration_minutes: Course duration
- price: Course price
- instructor_name: Instructor name
- instructor_bio: Instructor biography
- instructor_image: Instructor image path
- status: ENUM ['draft', 'published', 'archived']
- sort_order: Display order
- prerequisites: JSON array of prerequisite courses
- learning_objectives: JSON array of learning objectives
- created_at, updated_at: Timestamps
```

**Indexes**:
- `slug` (unique)
- `is_premium`
- `is_featured`
- `difficulty_level`
- `status`
- `sort_order`

#### course_enrollments
**Purpose**: Track course enrollments and progress
```sql
- id: Primary Key
- course_id: Foreign Key to courses table
- user_id: Foreign Key to users table
- enrollment_status: ENUM ['enrolled', 'in_progress', 'completed', 'dropped']
- progress_percentage: Progress percentage (0-100)
- last_accessed_at: Last access timestamp
- completed_at: Completion timestamp
- certificate_issued: Boolean certificate flag
- certificate_path: Certificate file path
- created_at, updated_at: Timestamps
```

**Indexes**:
- `course_id`
- `user_id`
- `enrollment_status`
- `course_id` + `user_id` (composite unique)

## Relationship Mapping

### Primary Relationships

1. **Users → User Profiles**: One-to-One
2. **Users → Posts**: One-to-Many (author relationship)
3. **Users → Trading Alerts**: One-to-Many (creator relationship)
4. **Users → Chat Messages**: One-to-Many
5. **Users → Event Registrations**: One-to-Many
6. **Users → Course Enrollments**: One-to-Many
7. **Post Categories → Posts**: One-to-Many
8. **Chat Channels → Chat Messages**: One-to-Many
9. **Chat Channels → Chat Participants**: One-to-Many
10. **Events → Event Registrations**: One-to-Many
11. **Courses → Course Enrollments**: One-to-Many

### Junction Tables (Many-to-Many)

1. **post_interactions**: Users ↔ Posts
2. **user_alert_subscriptions**: Users ↔ Trading Alerts
3. **chat_participants**: Users ↔ Chat Channels

## Indexing Strategy

### Performance Optimization

1. **Primary Keys**: All tables have auto-incrementing primary keys
2. **Foreign Keys**: All foreign key columns are indexed
3. **Unique Constraints**: Email, slugs, and composite unique keys
4. **Query Optimization**: Indexes on frequently queried columns
5. **Composite Indexes**: Multi-column indexes for complex queries

### Specific Index Purposes

- **User Activity**: `last_active_at` for user engagement tracking
- **Content Discovery**: `status` + `published_at` for content queries
- **Premium Features**: `is_premium` flags for subscription filtering
- **Performance Metrics**: Counter columns for analytics
- **Time-based Queries**: Timestamp columns for chronological data

## Migration Process

### Migration Files Structure

```
database/migrations/
├── 0001_01_01_000000_create_users_table.php (Laravel default)
├── 0001_01_01_000001_create_cache_table.php (Laravel default)
├── 0001_01_01_000002_create_jobs_table.php (Laravel default)
├── 2025_07_08_002507_create_user_profiles_table.php
├── 2025_07_08_002513_create_subscription_plans_table.php
├── 2025_07_08_002518_create_posts_table.php
├── 2025_07_08_002523_create_post_categories_table.php
├── 2025_07_08_002528_create_trading_alerts_table.php
├── 2025_07_08_002533_create_chat_channels_table.php
├── 2025_07_08_002538_create_chat_messages_table.php
├── 2025_07_08_002543_create_events_table.php
├── 2025_07_08_002548_create_event_registrations_table.php
├── 2025_07_08_002553_create_courses_table.php
├── 2025_07_08_002558_create_course_enrollments_table.php
├── 2025_07_08_002923_create_chat_participants_table.php
├── 2025_07_08_002928_create_post_interactions_table.php
├── 2025_07_08_002933_create_user_alert_subscriptions_table.php
└── 2025_07_08_003233_add_foreign_key_to_posts_table.php
```

### Migration Execution Order

1. **Core Tables**: Users, subscription plans, categories
2. **Content Tables**: Posts, trading alerts, courses, events
3. **Relationship Tables**: Chat system, enrollments, registrations
4. **Junction Tables**: Many-to-many relationships
5. **Foreign Key Constraints**: Added after all tables exist

### Migration Commands

```bash
# Run all migrations
php artisan migrate

# Fresh migration (development only)
php artisan migrate:fresh

# Rollback migrations
php artisan migrate:rollback

# Check migration status
php artisan migrate:status
```

## Data Seeding

### Seeder Structure

```
database/seeders/
├── DatabaseSeeder.php (main seeder)
├── SubscriptionPlansSeeder.php
├── PostCategoriesSeeder.php
└── ChatChannelsSeeder.php
```

### Seeding Strategy

1. **Master Data**: Subscription plans, categories, default channels
2. **Test Data**: Sample users, posts, alerts for development
3. **Production Data**: Essential configuration data only

### Seeding Commands

```bash
# Run all seeders
php artisan db:seed

# Run specific seeder
php artisan db:seed --class=SubscriptionPlansSeeder

# Fresh migration with seeding
php artisan migrate:fresh --seed
```

## Constraint Verification

### Referential Integrity

- **CASCADE DELETE**: User deletion removes related profiles, posts, alerts
- **RESTRICT DELETE**: Prevent deletion of referenced categories, plans
- **SET NULL**: Optional foreign keys set to null on parent deletion

### Data Validation

- **ENUM Constraints**: Predefined values for status, types, levels
- **UNIQUE Constraints**: Email addresses, slugs, composite keys
- **NOT NULL Constraints**: Required fields enforced at database level
- **CHECK Constraints**: Value ranges and business rules

## Schema Optimization

### Performance Considerations

1. **Denormalization**: Counter columns for frequently accessed counts
2. **JSON Fields**: Flexible data storage for features, preferences
3. **Composite Indexes**: Multi-column indexes for complex queries
4. **Partitioning**: Consider for large tables (messages, alerts)

### Scalability Strategies

1. **Read Replicas**: Separate read/write database instances
2. **Caching**: Redis for frequently accessed data
3. **Queue Processing**: Background jobs for heavy operations
4. **Archive Strategy**: Move old data to archive tables

## Security Considerations

### Data Protection

1. **Password Hashing**: Laravel's built-in password hashing
2. **Sensitive Data**: Encrypted storage for financial information
3. **Access Control**: Role-based permissions via Spatie package
4. **Audit Trails**: Timestamp tracking for all operations

### Compliance

1. **GDPR**: User data deletion and export capabilities
2. **Financial Regulations**: Audit trails for trading-related data
3. **Data Retention**: Policies for different data types
4. **Encryption**: At-rest and in-transit encryption

## Monitoring and Maintenance

### Database Health

1. **Query Performance**: Monitor slow queries and optimize
2. **Index Usage**: Analyze index effectiveness
3. **Storage Growth**: Monitor table sizes and growth patterns
4. **Backup Strategy**: Regular automated backups

### Maintenance Tasks

1. **Statistics Updates**: Regular database statistics refresh
2. **Index Maintenance**: Rebuild fragmented indexes
3. **Cleanup Jobs**: Remove expired or obsolete data
4. **Performance Tuning**: Ongoing optimization based on usage patterns

## Future Considerations

### Planned Enhancements

1. **Full-Text Search**: Elasticsearch integration for content search
2. **Time-Series Data**: Specialized storage for market data
3. **Real-Time Features**: WebSocket support for live updates
4. **Analytics**: Data warehouse integration for business intelligence

### Migration Roadmap

1. **Phase 1**: Current core schema implementation
2. **Phase 2**: Advanced features and optimization
3. **Phase 3**: Scalability and performance enhancements
4. **Phase 4**: Analytics and business intelligence integration

---

*This documentation is maintained alongside the codebase and should be updated with any schema changes or optimizations.* 
