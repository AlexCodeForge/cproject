# LMS (Learning Management System) Module Implementation Plan

## 1. Overview

This document outlines the plan to build a simple Learning Management System (LMS) within the OptionRocket platform. The module will allow administrators to create and manage courses, which are composed of sections and video lessons. Users will be able to browse available courses, enroll in them, and track their progress.

The implementation will heavily leverage Livewire for dynamic components on both the user-facing side and the admin panel, following the existing architecture of the project.

## 2. Database Schema

We will introduce four new tables to support the LMS functionality.

### `courses` table
Stores the main information for each course.

-   `id` (PK)
-   `title` (string)
-   `slug` (string, unique)
-   `description` (text) - Will store rich text from a WYSIWYG editor.
-   `image_url` (string, nullable) - Cover image for the course.
-   `is_premium` (boolean, default: false) - To distinguish between free and premium courses.
-   `status` (enum: 'draft', 'published', 'archived', default: 'draft')
-   `created_at`, `updated_at` (timestamps)

### `course_sections` table
Organizes lessons within a course.

-   `id` (PK)
-   `course_id` (FK to `courses.id`)
-   `title` (string)
-   `order` (integer) - To define the sequence of sections.
-   `created_at`, `updated_at` (timestamps)

### `lessons` table
Represents an individual lesson, which will primarily be a video.

-   `id` (PK)
-   `course_section_id` (FK to `course_sections.id`)
-   `title` (string)
-   `slug` (string, unique)
-   `video_path` (string) - **Path to the locally stored video file.**
-   `content` (text, nullable) - For additional notes or text content for the lesson.
-   `order` (integer) - To define the sequence of lessons within a section.
-   `created_at`, `updated_at` (timestamps)

**Note on Video Storage:** Videos will be stored locally within the application's storage. This implies the need for a robust file upload mechanism in the admin panel and consideration for streaming/serving these videos securely to users.

### `course_enrollments` table
Tracks which users are enrolled in which courses.

-   `id` (PK)
-   `user_id` (FK to `users.id`)
-   `course_id` (FK to `courses.id`)
-   `created_at`, `updated_at` (timestamps)
-   Unique constraint on (`user_id`, `course_id`)

### `lesson_user` table (for progress tracking)
A pivot table to track completed lessons for each user.

-   `id` (PK)
-   `user_id` (FK to `users.id`)
-   `lesson_id` (FK to `lessons.id`)
-   `completed_at` (timestamp, nullable)
-   `created_at`, `updated_at` (timestamps)

## 3. Models & Relationships

-   **`Course`**
    -   `hasMany(CourseSection::class)`
    -   `hasManyThrough(Lesson::class, CourseSection::class)`
    -   `hasMany(CourseEnrollment::class)`
    -   `belongsToMany(User::class, 'course_enrollments')`

-   **`CourseSection`**
    -   `belongsTo(Course::class)`
    -   `hasMany(Lesson::class)`

-   **`Lesson`**
    -   `belongsTo(CourseSection::class)`
    -   `hasOne(Course::class)->through('courseSection')`
    -   `belongsToMany(User::class, 'lesson_user')->withTimestamps()->withPivot('completed_at')`

-   **`User`**
    -   `belongsToMany(Course::class, 'course_enrollments')`
    -   `belongsToMany(Lesson::class, 'lesson_user')`

## 4. Admin Panel Implementation

The admin panel for LMS management will be located under `/admin/lms` and will follow the pattern established by `ChannelManagement.php` and `ChannelTable.php`.

### Migrations
1.  `php artisan make:migration create_courses_table`
2.  `php artisan make:migration create_course_sections_table`
3.  `php artisan make:migration create_lessons_table`
4.  `php artisan make:migration create_course_enrollments_table`
5.  `php artisan make:migration create_lesson_user_table`

### Livewire Components & Views

-   **`app/Livewire/AdminPanel/Lms/CourseManagement.php`**
    -   A container component similar to `ChannelManagement`.
    -   Renders the view: `admin_panel.lms.course-management`.

-   **`app/Livewire/AdminPanel/Lms/CourseTable.php`**
    -   This component will list all courses with search and filtering capabilities.
    -   It will include a modal for creating a new course and for basic edits (title, status, premium flag).
    -   Each row will have a link to a dedicated "Edit Course" page for managing content.
    -   View: `admin_panel.lms.livewire.course-table`.

-   **`app/Livewire/AdminPanel/Lms/EditCourse.php`**
    -   This will be a full-page component for managing a single course's content.
    -   **Functionality**:
        -   Edit course details (`title`, `description`, `image_url`, etc.). The `description` field will use a WYSIWYG editor like Summernote.
        -   Manage sections: Add, edit, reorder, and delete sections for the course.
        -   Manage lessons within each section: Add, edit, reorder, and delete lessons. **This will include functionality for uploading video files to local storage, updating the `video_path` field, and handling potential file replacements or deletions.**
    -   View: `admin_panel.lms.edit-course`.

## 5. User-Facing Implementation

This will consist of two main pages: the course catalog and the course detail/player page.

### Livewire Components & Views

-   **`app/Livewire/UserPanel/Courses.php`**
    -   This will replace the current static view at `resources/views/user_panel/courses.blade.php`.
    -   It will fetch all `published` courses from the database.
    -   It will render the grid of course cards as seen in the design.
    -   The component will handle logic for what the "Empezar Curso" / "Acceder al Curso" buttons do (e.g., redirect to the course page if enrolled, or to a premium purchase page if required).
    -   **It must also correctly display premium course indicators (e.g., "Premium" badge, rocket icon, and "Acceso Premium" button with distinct styling) as per the `courses.blade.php` template.**
    -   View: `user_panel.courses` (will be converted into a Livewire view).

-   **`app/Livewire/UserPanel/ShowCourse.php`**
    -   This component is for the course detail page, where users watch lessons.
    -   **Functionality**:
        -   Receives a `Course` model instance based on the URL slug.
        -   Displays the course title, description, and main video.
        -   Renders the sidebar with expandable sections and a list of lessons.
        -   Tracks user progress, marking lessons as complete.
        -   Handles navigation between lessons.
        -   Checks for premium access before allowing video playback on premium courses.
    -   View: A new `user_panel.lms.show-course` view.
