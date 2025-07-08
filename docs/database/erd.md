# OptionRocket Database Entity Relationship Diagram

## Visual Schema Overview

This document provides a textual representation of the OptionRocket database Entity Relationship Diagram (ERD).

## Entity Relationships

### Core User Management
```
users ||--o{ user_profiles : "has profile"
users ||--o{ posts : "creates"
users ||--o{ trading_alerts : "creates"
users ||--o{ chat_messages : "sends"
users ||--o{ event_registrations : "registers for"
users ||--o{ course_enrollments : "enrolls in"
```

### Content Management
```
post_categories ||--o{ posts : "categorizes"
posts ||--o{ post_interactions : "receives"
users ||--o{ post_interactions : "performs"
```

### Trading System
```
trading_alerts ||--o{ user_alert_subscriptions : "subscribed to"
users ||--o{ user_alert_subscriptions : "subscribes to"
```

### Chat System
```
chat_channels ||--o{ chat_messages : "contains"
chat_channels ||--o{ chat_participants : "has members"
users ||--o{ chat_participants : "participates in"
users ||--|| chat_channels : "creates"
```

### Event Management
```
events ||--o{ event_registrations : "has registrations"
users ||--o{ event_registrations : "registers for"
```

### Learning Management
```
courses ||--o{ course_enrollments : "has enrollments"
users ||--o{ course_enrollments : "enrolls in"
```

### Subscription System
```
subscription_plans ||--o{ users : "subscribed by" (via Laravel Cashier)
```

## Detailed Entity Definitions

### User Management Entities

#### users
- **Type**: Core Entity
- **Purpose**: Authentication and basic user data
- **Key Relationships**: 
  - One-to-One with user_profiles
  - One-to-Many with posts, trading_alerts, chat_messages
  - Many-to-Many with posts (via post_interactions)

#### user_profiles
- **Type**: Extension Entity
- **Purpose**: Extended user information and preferences
- **Key Relationships**:
  - One-to-One with users (required)

### Content Management Entities

#### post_categories
- **Type**: Reference Entity
- **Purpose**: Categorization of content
- **Key Relationships**:
  - One-to-Many with posts

#### posts
- **Type**: Core Entity
- **Purpose**: Main content storage
- **Key Relationships**:
  - Many-to-One with users (author)
  - Many-to-One with post_categories
  - One-to-Many with post_interactions

#### post_interactions
- **Type**: Junction Entity
- **Purpose**: Track user engagement with content
- **Key Relationships**:
  - Many-to-One with users
  - Many-to-One with posts

### Trading System Entities

#### trading_alerts
- **Type**: Core Entity
- **Purpose**: Trading signal management
- **Key Relationships**:
  - Many-to-One with users (creator)
  - One-to-Many with user_alert_subscriptions

#### user_alert_subscriptions
- **Type**: Junction Entity
- **Purpose**: User subscriptions to alerts
- **Key Relationships**:
  - Many-to-One with users
  - Many-to-One with trading_alerts

### Chat System Entities

#### chat_channels
- **Type**: Core Entity
- **Purpose**: Chat room management
- **Key Relationships**:
  - Many-to-One with users (creator)
  - One-to-Many with chat_messages
  - One-to-Many with chat_participants

#### chat_messages
- **Type**: Transaction Entity
- **Purpose**: Message storage
- **Key Relationships**:
  - Many-to-One with users (sender)
  - Many-to-One with chat_channels

#### chat_participants
- **Type**: Junction Entity
- **Purpose**: Channel membership management
- **Key Relationships**:
  - Many-to-One with users
  - Many-to-One with chat_channels

### Event Management Entities

#### events
- **Type**: Core Entity
- **Purpose**: Event management
- **Key Relationships**:
  - One-to-Many with event_registrations

#### event_registrations
- **Type**: Junction Entity
- **Purpose**: Event attendance tracking
- **Key Relationships**:
  - Many-to-One with users
  - Many-to-One with events

### Learning Management Entities

#### courses
- **Type**: Core Entity
- **Purpose**: Course catalog management
- **Key Relationships**:
  - One-to-Many with course_enrollments

#### course_enrollments
- **Type**: Junction Entity
- **Purpose**: Student enrollment tracking
- **Key Relationships**:
  - Many-to-One with users
  - Many-to-One with courses

### Subscription System Entities

#### subscription_plans
- **Type**: Reference Entity
- **Purpose**: Define subscription tiers
- **Key Relationships**:
  - Connected to users via Laravel Cashier tables

## Cardinality Definitions

### One-to-One Relationships
- users ↔ user_profiles (required)

### One-to-Many Relationships
- users → posts (author)
- users → trading_alerts (creator)
- users → chat_messages (sender)
- users → chat_channels (creator)
- post_categories → posts
- chat_channels → chat_messages
- chat_channels → chat_participants
- events → event_registrations
- courses → course_enrollments
- trading_alerts → user_alert_subscriptions

### Many-to-Many Relationships
- users ↔ posts (via post_interactions)
- users ↔ trading_alerts (via user_alert_subscriptions)
- users ↔ chat_channels (via chat_participants)
- users ↔ events (via event_registrations)
- users ↔ courses (via course_enrollments)

## Foreign Key Constraints

### CASCADE DELETE
- user_profiles.user_id → users.id
- posts.user_id → users.id
- trading_alerts.user_id → users.id
- chat_messages.user_id → users.id
- chat_messages.chat_channel_id → chat_channels.id
- All junction table foreign keys

### SET NULL
- posts.post_category_id → post_categories.id (optional)
- chat_channels.created_by → users.id (optional)

### RESTRICT DELETE
- Subscription plans (cannot delete if users subscribed)
- Categories with associated posts

## Indexes and Performance

### Primary Indexes
- All tables have auto-incrementing primary keys
- Unique indexes on email, slugs, and natural keys

### Foreign Key Indexes
- All foreign key columns are indexed
- Composite indexes for junction tables

### Query Optimization Indexes
- Frequently queried columns (status, type, date ranges)
- Composite indexes for complex queries
- Counter columns for performance metrics

## Data Integrity Rules

### Business Rules
1. Users must have a profile
2. Posts must have an author
3. Premium content requires subscription validation
4. Chat participants must exist before sending messages
5. Event registrations respect capacity limits
6. Course enrollments track progress

### Validation Rules
1. Email uniqueness across users
2. Slug uniqueness within entity types
3. Enum value constraints
4. Date range validations
5. Numeric range constraints

## Security Considerations

### Access Control
- Role-based permissions via Spatie package
- Premium content access control
- Chat channel access restrictions
- Admin-only operations

### Data Protection
- Sensitive data encryption
- Audit trail maintenance
- Soft deletes for important records
- GDPR compliance support

---

*This ERD documentation should be used in conjunction with the full database schema documentation for complete understanding of the OptionRocket data model.* 
