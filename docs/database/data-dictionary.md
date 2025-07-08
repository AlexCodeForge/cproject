# OptionRocket Data Dictionary

## Overview

This data dictionary provides detailed information about all database tables, columns, data types, constraints, and business rules for the OptionRocket platform.

## Table Reference

### users
**Purpose**: Core user authentication and basic information

| Column | Type | Constraints | Description | Business Rules |
|--------|------|-------------|-------------|----------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Unique user identifier | System generated |
| name | VARCHAR(255) | NOT NULL | User's full name | Required for registration |
| email | VARCHAR(255) | NOT NULL, UNIQUE | Email address | Must be valid email format |
| email_verified_at | TIMESTAMP | NULL | Email verification timestamp | Set when email verified |
| password | VARCHAR(255) | NOT NULL | Hashed password | Laravel bcrypt hash |
| remember_token | VARCHAR(100) | NULL | Session remember token | Laravel session management |
| created_at | TIMESTAMP | NULL | Record creation timestamp | Auto-generated |
| updated_at | TIMESTAMP | NULL | Record update timestamp | Auto-updated |

### user_profiles
**Purpose**: Extended user profile information and preferences

| Column | Type | Constraints | Description | Business Rules |
|--------|------|-------------|-------------|----------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Unique profile identifier | System generated |
| user_id | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY, UNIQUE | Reference to users table | One profile per user |
| avatar | VARCHAR(255) | NULL | Profile picture file path | Optional, stored in storage/app/public |
| bio | TEXT | NULL | User biography | Optional, max 1000 characters |
| location | VARCHAR(255) | NULL | User's location | Optional |
| website | VARCHAR(255) | NULL | Personal website URL | Optional, must be valid URL |
| twitter_handle | VARCHAR(255) | NULL | Twitter username | Optional, without @ symbol |
| linkedin_url | VARCHAR(255) | NULL | LinkedIn profile URL | Optional, must be valid URL |
| trading_experience | ENUM | DEFAULT 'beginner' | Trading experience level | ['beginner', 'intermediate', 'advanced', 'expert'] |
| trading_interests | JSON | NULL | Array of trading interests | ['options', 'crypto', 'forex', 'stocks', etc.] |
| portfolio_value | DECIMAL(15,2) | NULL | User's portfolio value | Optional, in USD |
| public_profile | BOOLEAN | DEFAULT TRUE | Profile visibility setting | Controls public profile access |
| show_portfolio | BOOLEAN | DEFAULT FALSE | Portfolio visibility setting | Controls portfolio value display |
| timezone | VARCHAR(255) | DEFAULT 'UTC' | User's timezone | Standard timezone identifier |
| language | VARCHAR(255) | DEFAULT 'en' | Preferred language | ISO language code |
| notification_preferences | JSON | NULL | Notification settings | Email, push, SMS preferences |
| last_active_at | TIMESTAMP | NULL | Last activity timestamp | Updated on user actions |
| created_at | TIMESTAMP | NULL | Record creation timestamp | Auto-generated |
| updated_at | TIMESTAMP | NULL | Record update timestamp | Auto-updated |

### subscription_plans
**Purpose**: Define available subscription tiers and pricing

| Column | Type | Constraints | Description | Business Rules |
|--------|------|-------------|-------------|----------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Unique plan identifier | System generated |
| name | VARCHAR(255) | NOT NULL | Plan name | 'Free', 'Premium', 'Pro' |
| slug | VARCHAR(255) | NOT NULL, UNIQUE | URL-friendly identifier | Lowercase, hyphenated |
| description | TEXT | NULL | Plan description | Marketing description |
| monthly_price | DECIMAL(8,2) | DEFAULT 0 | Monthly price in USD | 0 for free plans |
| yearly_price | DECIMAL(8,2) | DEFAULT 0 | Annual price in USD | Usually discounted |
| stripe_monthly_price_id | VARCHAR(255) | NULL | Stripe monthly price ID | From Stripe dashboard |
| stripe_yearly_price_id | VARCHAR(255) | NULL | Stripe yearly price ID | From Stripe dashboard |
| features | JSON | NULL | Array of included features | Feature list for display |
| max_alerts_per_month | INT | NULL | Monthly alert limit | NULL = unlimited |
| max_courses | INT | NULL | Course access limit | NULL = unlimited |
| premium_chat_access | BOOLEAN | DEFAULT FALSE | Premium chat access | Access to premium channels |
| premium_events_access | BOOLEAN | DEFAULT FALSE | Premium event access | Access to premium events |
| advanced_analytics | BOOLEAN | DEFAULT FALSE | Advanced analytics access | Advanced dashboard features |
| priority_support | BOOLEAN | DEFAULT FALSE | Priority support access | Priority customer support |
| is_active | BOOLEAN | DEFAULT TRUE | Plan availability | Controls plan visibility |
| sort_order | INT | DEFAULT 0 | Display order | Lower numbers first |
| created_at | TIMESTAMP | NULL | Record creation timestamp | Auto-generated |
| updated_at | TIMESTAMP | NULL | Record update timestamp | Auto-updated |

### post_categories
**Purpose**: Categorize posts and content

| Column | Type | Constraints | Description | Business Rules |
|--------|------|-------------|-------------|----------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Unique category identifier | System generated |
| name | VARCHAR(255) | NOT NULL | Category name | Display name |
| slug | VARCHAR(255) | NOT NULL, UNIQUE | URL-friendly identifier | Lowercase, hyphenated |
| description | TEXT | NULL | Category description | Optional description |
| color | VARCHAR(7) | NULL | Display color hex code | Format: #RRGGBB |
| icon | VARCHAR(255) | NULL | Category icon name | Icon identifier |
| is_active | BOOLEAN | DEFAULT TRUE | Category availability | Controls category visibility |
| sort_order | INT | DEFAULT 0 | Display order | Lower numbers first |
| created_at | TIMESTAMP | NULL | Record creation timestamp | Auto-generated |
| updated_at | TIMESTAMP | NULL | Record update timestamp | Auto-updated |

### posts
**Purpose**: Main content management for articles and posts

| Column | Type | Constraints | Description | Business Rules |
|--------|------|-------------|-------------|----------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Unique post identifier | System generated |
| user_id | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | Reference to author | Must be valid user |
| post_category_id | BIGINT UNSIGNED | NULL, FOREIGN KEY | Reference to category | Optional categorization |
| title | VARCHAR(255) | NOT NULL | Post title | Required, max 255 chars |
| slug | VARCHAR(255) | NOT NULL, UNIQUE | URL-friendly identifier | Auto-generated from title |
| excerpt | TEXT | NULL | Post summary | Optional, for previews |
| content | LONGTEXT | NOT NULL | Full post content | Required, rich text |
| featured_image | VARCHAR(255) | NULL | Featured image path | Optional, stored in storage |
| images | JSON | NULL | Array of additional images | Optional image gallery |
| status | ENUM | DEFAULT 'draft' | Publication status | ['draft', 'published', 'archived'] |
| is_premium | BOOLEAN | DEFAULT FALSE | Premium content flag | Requires subscription |
| is_featured | BOOLEAN | DEFAULT FALSE | Featured content flag | Highlighted content |
| tags | JSON | NULL | Array of tags | Optional, for search/filtering |
| meta_title | VARCHAR(255) | NULL | SEO title | Optional, for SEO |
| meta_description | TEXT | NULL | SEO description | Optional, for SEO |
| views_count | INT | DEFAULT 0 | View counter | Incremented on view |
| likes_count | INT | DEFAULT 0 | Like counter | Incremented on like |
| comments_count | INT | DEFAULT 0 | Comment counter | Incremented on comment |
| shares_count | INT | DEFAULT 0 | Share counter | Incremented on share |
| published_at | TIMESTAMP | NULL | Publication timestamp | Set when published |
| reading_time | INT | NULL | Estimated reading time | In minutes |
| difficulty_level | DECIMAL(2,1) | NULL | Content difficulty | 1.0 to 5.0 scale |
| created_at | TIMESTAMP | NULL | Record creation timestamp | Auto-generated |
| updated_at | TIMESTAMP | NULL | Record update timestamp | Auto-updated |

### post_interactions
**Purpose**: Track user interactions with posts

| Column | Type | Constraints | Description | Business Rules |
|--------|------|-------------|-------------|----------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Unique interaction identifier | System generated |
| user_id | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | Reference to user | Must be valid user |
| post_id | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | Reference to post | Must be valid post |
| interaction_type | ENUM | NOT NULL | Type of interaction | ['like', 'comment', 'share', 'bookmark'] |
| interaction_data | JSON | NULL | Additional interaction data | Optional metadata |
| created_at | TIMESTAMP | NULL | Record creation timestamp | Auto-generated |
| updated_at | TIMESTAMP | NULL | Record update timestamp | Auto-updated |

**Unique Constraint**: user_id + post_id + interaction_type (prevent duplicate interactions)

### trading_alerts
**Purpose**: Core trading alert functionality

| Column | Type | Constraints | Description | Business Rules |
|--------|------|-------------|-------------|----------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Unique alert identifier | System generated |
| user_id | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | Reference to alert creator | Must be valid user |
| symbol | VARCHAR(255) | NOT NULL | Stock/crypto symbol | Uppercase ticker symbol |
| company_name | VARCHAR(255) | NULL | Company name | Optional, for display |
| alert_type | ENUM | DEFAULT 'buy' | Alert recommendation | ['buy', 'sell', 'hold', 'watch'] |
| market_type | ENUM | DEFAULT 'stocks' | Market category | ['stocks', 'options', 'crypto', 'forex', 'commodities'] |
| entry_price | DECIMAL(10,4) | NULL | Target entry price | Optional, in USD |
| target_price | DECIMAL(10,4) | NULL | Price target | Optional, in USD |
| stop_loss | DECIMAL(10,4) | NULL | Stop loss price | Optional, in USD |
| current_price | DECIMAL(10,4) | NULL | Current market price | Updated via API |
| analysis | TEXT | NULL | Alert analysis | Optional, detailed analysis |
| reasoning | TEXT | NULL | Alert reasoning | Optional, why this alert |
| risk_level | ENUM | DEFAULT 'medium' | Risk assessment | ['low', 'medium', 'high'] |
| time_frame | ENUM | DEFAULT 'swing' | Trading time frame | ['intraday', 'swing', 'position'] |
| status | ENUM | DEFAULT 'active' | Alert status | ['active', 'triggered', 'closed', 'expired', 'cancelled'] |
| actual_entry_price | DECIMAL(10,4) | NULL | Actual entry price | Set when triggered |
| actual_exit_price | DECIMAL(10,4) | NULL | Actual exit price | Set when closed |
| profit_loss | DECIMAL(10,4) | NULL | Profit/loss amount | Calculated on close |
| profit_loss_percentage | DECIMAL(5,2) | NULL | Profit/loss percentage | Calculated on close |
| is_premium | BOOLEAN | DEFAULT FALSE | Premium alert flag | Requires subscription |
| is_featured | BOOLEAN | DEFAULT FALSE | Featured alert flag | Highlighted alert |
| triggered_at | TIMESTAMP | NULL | Alert trigger timestamp | Set when triggered |
| closed_at | TIMESTAMP | NULL | Alert close timestamp | Set when closed |
| expires_at | TIMESTAMP | NULL | Alert expiration timestamp | Optional expiration |
| views_count | INT | DEFAULT 0 | View counter | Incremented on view |
| followers_count | INT | DEFAULT 0 | Follower counter | Users subscribed to alert |
| technical_indicators | JSON | NULL | Technical analysis data | RSI, MACD, etc. |
| chart_image | VARCHAR(255) | NULL | Chart image path | Optional, stored in storage |
| created_at | TIMESTAMP | NULL | Record creation timestamp | Auto-generated |
| updated_at | TIMESTAMP | NULL | Record update timestamp | Auto-updated |

### user_alert_subscriptions
**Purpose**: Track user subscriptions to specific alerts

| Column | Type | Constraints | Description | Business Rules |
|--------|------|-------------|-------------|----------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Unique subscription identifier | System generated |
| user_id | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | Reference to user | Must be valid user |
| trading_alert_id | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | Reference to alert | Must be valid alert |
| notification_enabled | BOOLEAN | DEFAULT TRUE | Notification setting | Enable/disable notifications |
| created_at | TIMESTAMP | NULL | Record creation timestamp | Auto-generated |
| updated_at | TIMESTAMP | NULL | Record update timestamp | Auto-updated |

**Unique Constraint**: user_id + trading_alert_id (prevent duplicate subscriptions)

### chat_channels
**Purpose**: Define chat channels and rooms

| Column | Type | Constraints | Description | Business Rules |
|--------|------|-------------|-------------|----------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Unique channel identifier | System generated |
| name | VARCHAR(255) | NOT NULL | Channel name | Display name |
| slug | VARCHAR(255) | NOT NULL, UNIQUE | URL-friendly identifier | Lowercase, hyphenated |
| description | TEXT | NULL | Channel description | Optional description |
| is_premium | BOOLEAN | DEFAULT FALSE | Premium channel flag | Requires subscription |
| is_private | BOOLEAN | DEFAULT FALSE | Private channel flag | Invite-only access |
| is_active | BOOLEAN | DEFAULT TRUE | Channel availability | Controls channel visibility |
| max_participants | INT | NULL | Maximum participants | NULL = unlimited |
| created_by | BIGINT UNSIGNED | NULL, FOREIGN KEY | Reference to creator | Optional, can be system |
| created_at | TIMESTAMP | NULL | Record creation timestamp | Auto-generated |
| updated_at | TIMESTAMP | NULL | Record update timestamp | Auto-updated |

### chat_messages
**Purpose**: Store chat messages

| Column | Type | Constraints | Description | Business Rules |
|--------|------|-------------|-------------|----------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Unique message identifier | System generated |
| chat_channel_id | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | Reference to channel | Must be valid channel |
| user_id | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | Reference to sender | Must be valid user |
| message | TEXT | NOT NULL | Message content | Required, max 2000 chars |
| message_type | ENUM | DEFAULT 'text' | Message type | ['text', 'image', 'file', 'system'] |
| attachment_path | VARCHAR(255) | NULL | File attachment path | Optional, stored in storage |
| is_edited | BOOLEAN | DEFAULT FALSE | Edit flag | Indicates if message was edited |
| edited_at | TIMESTAMP | NULL | Edit timestamp | Set when message edited |
| created_at | TIMESTAMP | NULL | Record creation timestamp | Auto-generated |
| updated_at | TIMESTAMP | NULL | Record update timestamp | Auto-updated |

### chat_participants
**Purpose**: Track channel membership

| Column | Type | Constraints | Description | Business Rules |
|--------|------|-------------|-------------|----------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Unique participation identifier | System generated |
| chat_channel_id | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | Reference to channel | Must be valid channel |
| user_id | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | Reference to user | Must be valid user |
| role | ENUM | DEFAULT 'member' | Participant role | ['member', 'moderator', 'admin'] |
| joined_at | TIMESTAMP | NULL | Join timestamp | Set when user joins |
| last_read_at | TIMESTAMP | NULL | Last read timestamp | Updated on message read |
| is_muted | BOOLEAN | DEFAULT FALSE | Mute setting | Disable notifications |
| created_at | TIMESTAMP | NULL | Record creation timestamp | Auto-generated |
| updated_at | TIMESTAMP | NULL | Record update timestamp | Auto-updated |

**Unique Constraint**: chat_channel_id + user_id (prevent duplicate participation)

### events
**Purpose**: Manage premium and free events

| Column | Type | Constraints | Description | Business Rules |
|--------|------|-------------|-------------|----------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Unique event identifier | System generated |
| title | VARCHAR(255) | NOT NULL | Event title | Required, max 255 chars |
| slug | VARCHAR(255) | NOT NULL, UNIQUE | URL-friendly identifier | Auto-generated from title |
| description | TEXT | NULL | Event description | Optional, rich text |
| start_date | TIMESTAMP | NOT NULL | Event start date/time | Required |
| end_date | TIMESTAMP | NOT NULL | Event end date/time | Must be after start_date |
| timezone | VARCHAR(255) | DEFAULT 'UTC' | Event timezone | Standard timezone identifier |
| is_premium | BOOLEAN | DEFAULT FALSE | Premium event flag | Requires subscription |
| is_featured | BOOLEAN | DEFAULT FALSE | Featured event flag | Highlighted event |
| max_attendees | INT | NULL | Maximum attendees | NULL = unlimited |
| current_attendees | INT | DEFAULT 0 | Current attendee count | Auto-calculated |
| price | DECIMAL(8,2) | DEFAULT 0 | Event price | 0 for free events |
| location | VARCHAR(255) | NULL | Event location | Physical or virtual |
| speaker_name | VARCHAR(255) | NULL | Speaker name | Optional |
| speaker_bio | TEXT | NULL | Speaker biography | Optional |
| speaker_image | VARCHAR(255) | NULL | Speaker image path | Optional, stored in storage |
| status | ENUM | DEFAULT 'draft' | Event status | ['draft', 'published', 'cancelled', 'completed'] |
| registration_deadline | TIMESTAMP | NULL | Registration deadline | Optional |
| created_at | TIMESTAMP | NULL | Record creation timestamp | Auto-generated |
| updated_at | TIMESTAMP | NULL | Record update timestamp | Auto-updated |

### event_registrations
**Purpose**: Track event registrations

| Column | Type | Constraints | Description | Business Rules |
|--------|------|-------------|-------------|----------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Unique registration identifier | System generated |
| event_id | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | Reference to event | Must be valid event |
| user_id | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | Reference to user | Must be valid user |
| registration_status | ENUM | DEFAULT 'registered' | Registration status | ['registered', 'confirmed', 'cancelled', 'attended'] |
| payment_status | ENUM | DEFAULT 'pending' | Payment status | ['pending', 'paid', 'failed', 'refunded'] |
| payment_amount | DECIMAL(8,2) | NULL | Payment amount | Actual amount paid |
| notes | TEXT | NULL | Registration notes | Optional notes |
| created_at | TIMESTAMP | NULL | Record creation timestamp | Auto-generated |
| updated_at | TIMESTAMP | NULL | Record update timestamp | Auto-updated |

**Unique Constraint**: event_id + user_id (prevent duplicate registrations)

### courses
**Purpose**: Manage course catalog

| Column | Type | Constraints | Description | Business Rules |
|--------|------|-------------|-------------|----------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Unique course identifier | System generated |
| title | VARCHAR(255) | NOT NULL | Course title | Required, max 255 chars |
| slug | VARCHAR(255) | NOT NULL, UNIQUE | URL-friendly identifier | Auto-generated from title |
| description | TEXT | NULL | Course description | Optional, rich text |
| thumbnail | VARCHAR(255) | NULL | Course thumbnail path | Optional, stored in storage |
| is_premium | BOOLEAN | DEFAULT FALSE | Premium course flag | Requires subscription |
| is_featured | BOOLEAN | DEFAULT FALSE | Featured course flag | Highlighted course |
| difficulty_level | ENUM | DEFAULT 'beginner' | Course difficulty | ['beginner', 'intermediate', 'advanced'] |
| duration_minutes | INT | NULL | Course duration | In minutes |
| price | DECIMAL(8,2) | DEFAULT 0 | Course price | 0 for free courses |
| instructor_name | VARCHAR(255) | NULL | Instructor name | Optional |
| instructor_bio | TEXT | NULL | Instructor biography | Optional |
| instructor_image | VARCHAR(255) | NULL | Instructor image path | Optional, stored in storage |
| status | ENUM | DEFAULT 'draft' | Course status | ['draft', 'published', 'archived'] |
| sort_order | INT | DEFAULT 0 | Display order | Lower numbers first |
| prerequisites | JSON | NULL | Array of prerequisite courses | Course IDs |
| learning_objectives | JSON | NULL | Array of learning objectives | Text objectives |
| created_at | TIMESTAMP | NULL | Record creation timestamp | Auto-generated |
| updated_at | TIMESTAMP | NULL | Record update timestamp | Auto-updated |

### course_enrollments
**Purpose**: Track course enrollments and progress

| Column | Type | Constraints | Description | Business Rules |
|--------|------|-------------|-------------|----------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Unique enrollment identifier | System generated |
| course_id | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | Reference to course | Must be valid course |
| user_id | BIGINT UNSIGNED | NOT NULL, FOREIGN KEY | Reference to user | Must be valid user |
| enrollment_status | ENUM | DEFAULT 'enrolled' | Enrollment status | ['enrolled', 'in_progress', 'completed', 'dropped'] |
| progress_percentage | INT | DEFAULT 0 | Progress percentage | 0-100 |
| last_accessed_at | TIMESTAMP | NULL | Last access timestamp | Updated on course access |
| completed_at | TIMESTAMP | NULL | Completion timestamp | Set when completed |
| certificate_issued | BOOLEAN | DEFAULT FALSE | Certificate flag | Indicates if certificate issued |
| certificate_path | VARCHAR(255) | NULL | Certificate file path | Optional, stored in storage |
| created_at | TIMESTAMP | NULL | Record creation timestamp | Auto-generated |
| updated_at | TIMESTAMP | NULL | Record update timestamp | Auto-updated |

**Unique Constraint**: course_id + user_id (prevent duplicate enrollments)

## Data Types Reference

### Laravel/MySQL Data Types

| Laravel Type | MySQL Type | Description | Usage |
|-------------|------------|-------------|--------|
| id() | BIGINT UNSIGNED AUTO_INCREMENT | Primary key | All tables |
| string() | VARCHAR(255) | Variable length string | Names, titles, URLs |
| text() | TEXT | Medium text | Descriptions, content |
| longText() | LONGTEXT | Large text | Rich content |
| integer() | INT | Signed integer | Counters, quantities |
| decimal(p,s) | DECIMAL(p,s) | Fixed-point decimal | Prices, percentages |
| boolean() | TINYINT(1) | Boolean flag | True/false values |
| timestamp() | TIMESTAMP | Date and time | Timestamps |
| json() | JSON | JSON data | Arrays, objects |
| enum() | ENUM | Enumerated values | Status, types |
| foreignId() | BIGINT UNSIGNED | Foreign key | Relationships |

### JSON Field Structures

#### user_profiles.trading_interests
```json
["options", "crypto", "forex", "stocks", "commodities"]
```

#### user_profiles.notification_preferences
```json
{
  "email": {
    "alerts": true,
    "posts": false,
    "events": true
  },
  "push": {
    "alerts": true,
    "messages": true
  },
  "sms": {
    "alerts": false
  }
}
```

#### subscription_plans.features
```json
[
  "Unlimited alerts",
  "Premium chat access",
  "Advanced analytics",
  "Priority support"
]
```

#### posts.tags
```json
["trading", "options", "analysis", "bullish"]
```

#### posts.images
```json
[
  "images/posts/chart1.png",
  "images/posts/analysis.jpg"
]
```

#### trading_alerts.technical_indicators
```json
{
  "rsi": 65.5,
  "macd": {
    "signal": 1.2,
    "histogram": 0.8
  },
  "moving_averages": {
    "sma_20": 150.25,
    "sma_50": 148.75
  }
}
```

## Business Rules and Constraints

### User Management
- Each user must have exactly one profile
- Email addresses must be unique across all users
- Passwords are automatically hashed using Laravel's bcrypt

### Content Management
- Posts must have an author (user_id cannot be null)
- Published posts must have a published_at timestamp
- Premium content is only accessible to subscribed users
- Slugs must be unique within their entity type

### Trading Alerts
- Alerts must have a valid symbol
- Price fields use 4 decimal places for precision
- Profit/loss calculations are automatic when alerts are closed
- Premium alerts require subscription validation

### Chat System
- Users must be participants before sending messages
- Premium channels require subscription access
- Message history is preserved even if users leave channels
- System messages are generated automatically

### Events and Courses
- Event end dates must be after start dates
- Registration deadlines must be before event start dates
- Course prerequisites must be completed before enrollment
- Certificates are issued only upon course completion

### Subscription System
- Free plans have price = 0
- Premium features are gated by subscription status
- Stripe integration handles payment processing
- Plan changes are handled through Laravel Cashier

## Index Strategy

### Primary Indexes
- All tables have auto-incrementing primary keys
- Foreign keys are automatically indexed
- Unique constraints on email, slugs, and natural keys

### Performance Indexes
- Composite indexes on frequently queried column combinations
- Indexes on status and date columns for filtering
- Indexes on counter columns for sorting

### Query Optimization
- Use indexes for WHERE clauses
- Composite indexes for complex queries
- Avoid indexes on frequently updated columns
- Regular index maintenance and statistics updates

---

*This data dictionary should be kept current with any schema changes and serves as the authoritative reference for the OptionRocket database structure.* 
