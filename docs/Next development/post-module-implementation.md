# Post Module Implementation Plan

## 1. Overview

This document outlines the implementation plan for revamping the post management module to include a WYSIWYG editor (Trix) seamlessly integrated with Livewire. The goal is to create a robust, CMS-like user interface for managing posts, leveraging Livewire for dynamic interactions and modals for key actions.

The implementation will draw inspiration from the existing `/app/Livewire/AdminPanel/Users` module's structure, adapting it for post management with separate views for listing, creating, and editing.

## 2. Core Components

### 2.1. Livewire Components

We will create three primary Livewire components within `app/Livewire/AdminPanel/Posts`:

-   **`PostManagement.php`**: This component will serve as the main entry point for listing posts, similar to `UserManagement.php`. It will contain the logic for searching, filtering, and displaying the post table.
-   **`CreatePost.php`**: This component will handle the creation of new posts. This will be displayed within a modal.
-   **`EditPost.php`**: This component will handle editing existing posts. It will also be displayed within a modal.
    -   **Edge Case: Form Submission Failures (Validation)**
        -   **Scenario:** A user attempts to create or update a post, but one or more fields fail validation (e.g., missing title, invalid slug).
        -   **Mitigation:** Livewire's built-in validation should be used extensively.
            *   Implement `#[Validate]` attributes on public properties or use the `$this->validate()` method in the Livewire components.
            *   Display validation errors next to the relevant input fields using `<x-input-error>` (already available in the project) and `wire:model` for immediate feedback.
            *   Ensure the "Save" or "Publish" button is disabled until valid input is provided, or provide clear visual feedback when validation fails.
    -   **Edge Case: Non-existent Post for Editing**
        -   **Scenario:** A user tries to edit a post that has been deleted by another user or somehow doesn't exist.
        -   **Mitigation:**
            *   In `EditPost.php`'s `mount()` method, always attempt to find the post using `Post::findOrFail($postId)`. If not found, redirect the user back to the post listing page (`PostManagement`) with an error message (e.g., using Livewire's flash messages or a temporary session variable).
            *   Alternatively, handle a `null` post gracefully in the view, perhaps by showing a "Post not found" message and preventing editing actions.

### 2.2. Blade Views

Corresponding Blade views will be created in `resources/views/admin_panel/posts/livewire`:

-   **`post-management.blade.php`**: The main view for the post listing page.
-   **`create-post.blade.php`**: The view for the post creation modal.
-   **`edit-post.blade.php`**: The view for the post editing modal.

## 3. Integration Details

### 3.1. Post Model (`app/Models/Post.php`)

The `app/Models/Post.php` model will serve as the central data structure for all post-related information, supporting the CMS-style fields. While Livewire will be used for efficient form handling and data binding to component properties, **database persistence will occur only upon explicit form submission (e.g., clicking 'Save' or 'Publish'), not on individual field updates.**

-   **CMS-Style Fields**:
    -   **`title`**: The main title of the post (string).
    -   **`slug`**: A URL-friendly identifier automatically generated from the title (already handled by the `boot` method on creation).
        -   **Edge Case: Duplicate Slugs**
            -   **Scenario:** Two posts have the same title, leading to the same generated slug, causing conflicts or overwrites.
            -   **Mitigation:**
                *   Laravel's `Str::slug()` does not guarantee uniqueness. Use a package like `spatie/laravel-sluggable` or implement custom logic to ensure unique slugs. This typically involves appending a unique identifier (like a number) if a duplicate is found (e.g., `my-post`, `my-post-1`, `my-post-2`).
                *   Add a unique constraint to the `slug` column in the `posts` migration.
    -   **`excerpt`**: A brief summary for previews (string, nullable).
    -   **`content`**: This will be a standard text/longtext field for now. Rich text editing with Trix will be integrated in a later stage. Livewire will bind to this field directly via `wire:model`.
    -   **`status`**: (`draft`, `published`, `archived`) to control post visibility (string, enum-like).
    -   **`is_premium`**: Boolean to mark premium content (boolean).
    -   **`is_featured`**: Boolean to highlight important posts (boolean).
    -   **`tags`**: JSON array for categorizing and searching posts. Stored as `json` cast in the model.
        -   **Edge Case: Malformed JSON Data for `tags`**
            -   **Scenario:** Invalid JSON strings are saved to the `tags` column, leading to deserialization errors when retrieving the model.
            -   **Mitigation:**
                *   Laravel's `json` cast handles basic array/object conversion. However, for `tags`, consider enforcing a specific structure (e.g., array of strings).
                *   Use `try-catch` blocks when decoding JSON in accessors if external inputs are possible.
    -   **`meta_title` & `meta_description`**: For SEO purposes (string, nullable).
    -   **`published_at`**: Timestamp for when the post goes live (datetime, nullable).
        -   **Edge Case: `published_at` in the Future**
            -   **Scenario:** A user sets `published_at` to a future date, but the application doesn't respect this for visibility.
            -   **Mitigation:**
                *   Implement a global scope or a query scope on the `Post` model (e.g., `published()`) that filters posts to only include those where `status` is 'published' and `published_at` is null or in the past (`published_at <= now()`).
                *   Apply this scope to all public-facing queries that retrieve posts.
    -   **`reading_time` & `difficulty_level`**: For user experience and content categorization (integer/decimal, nullable).

-   **Featured Image (`featured_image`)**:
    -   The `featured_image` field in `Post.php` will store the path or a JSON structure containing details (like different sizes/formats) of the main visual for the post. It is a single, primary image for the post's thumbnail/banner. This will be the initial focus for image uploads.
    -   This image will be managed via Livewire's `WithFileUploads` trait within the `CreatePost` and `EditPost` components.
    -   Upon upload (which will be handled *during* the form submission process or as a single, separate explicit action), the image will be processed and stored. The resulting file path or a serialized array of image data will then be saved to the `featured_image` column in the `posts` table.
    -   The `Post` model might include an accessor (e.g., `getFeaturedImageUrlAttribute()`) to easily retrieve the full URL of the featured image for display.
    -   **Edge Case: Featured Image Upload Failures**
        -   **Scenario:** The featured image upload fails due to file size, type, or server issues.
        -   **Mitigation:**
            *   Livewire's `WithFileUploads` trait handles basic validation. Configure max file size and allowed extensions in `config/livewire.php` or using `#[Validate]` attributes.
            *   Display clear error messages to the user if the upload fails.
            *   If storing paths or JSON, ensure the database column is nullable to handle cases where no featured image is provided.
    -   **Edge Case: Replacing Featured Image**
        -   **Scenario:** A user uploads a new featured image, but the old one remains on disk, leading to orphaned files.
        -   **Mitigation:**
            *   When a new `featured_image` is successfully uploaded and saved, delete the old `featured_image` file from storage if it exists. This can be handled in the Livewire component's `updatedFeaturedImage` method or in a dedicated service/trait.

### 3.2. Form Handling with Livewire

-   **`wire:model` Binding**: Standard form inputs will use `wire:model` (without `.live`, `.blur`, or `.lazy` modifiers unless specifically needed for other minor reactive elements) to bind values to public properties in the `CreatePost` and `EditPost` Livewire components. This ensures the component property holds the latest content, but **does not trigger immediate server requests for every keystroke.** The actual saving to the database will be handled by the `save()` or `publish()` actions.
-   **File Uploads**: Livewire's `WithFileUploads` trait will be used for the `featured_image` upload. We will focus on debugging and ensuring this works correctly.

### 3.3. Modals for Create/Edit

Similar to the User module, we will implement the `CreatePost` and `EditPost` Livewire components as modals.

-   The `PostManagement` component will be responsible for triggering the display of these modals (e.g., `Livewire.dispatch('openPostCreateModal')`).
-   The modal content will be loaded dynamically using Livewire's modal features.

### 3.4. UI/UX Considerations

-   **Consistent Styling**: Maintain the existing UI theme for a cohesive CMS experience.
-   **Real-time Feedback**: Livewire's reactivity will provide instant validation feedback to the user.

### 3.5. Performance Considerations

-   **Edge Case: Large Posts (Extensive Content without Trix)**
    -   **Scenario:** Posts with a very large amount of content might cause performance issues on load or save.
    -   **Mitigation:** Ensure efficient database queries. Optimize content rendering on the frontend.

## 4. Proposed File Structure

```
app/Livewire/AdminPanel/Posts/
├── PostManagement.php      // Main component for listing posts
├── CreatePost.php          // Component for creating posts (modal)
└── EditPost.php            // Component for editing posts (modal)

resources/views/admin_panel/posts/livewire/
├── post-management.blade.php
├── create-post.blade.php
└── edit-post.blade.php
```

## 6. Debugging Strategy

To ensure we know what's wrong at any step, we must implement proper debugging throughout the development process:

-   **Implement Debugging in Every New File**:
    -   **Crucially, every new PHP file (Livewire components, models, controllers, etc.) must be created with Laravel's built-in logging (`Log::info()`, `Log::error()`, `Log::debug()`) already integrated at key points (e.g., `mount`, `save`, `update` methods, or any significant logic blocks).**
    -   Similarly, **any new or modified JavaScript files must immediately include `console.log()`** statements for tracking variables, events, and component states.

-   **JavaScript Debugging (Frontend)**:
    -   Utilize `console.log()` extensively in `resources/js/app.js`, `shared-layout.js`, and within any custom JavaScript (especially once `trix-editor.blade.php` is created).
    -   Monitor Livewire network requests and responses using browser developer tools, checking payload data and any returned errors.
-   **Laravel Logging (Backend)**:
    -   Use Laravel's `Log` facade (`Log::info()`, `Log::error()`, `Log::debug()`) in Livewire components (`PostManagement.php`, `CreatePost.php`, `EditPost.php`), any attachment controllers, and the `Post` model.
    -   Log validation failures, database operations (create, update, delete), file upload successes/failures, and any unexpected behavior. These logs must be checked consistently from the start of development for each feature.
    -   Ensure sensitive data is not logged.
    -   Regularly check the `storage/logs/laravel.log` file.
-   **Livewire Debugbar** (If installed): Leverage it to inspect component state, re-renders, network calls, and actions.
-   **Error Reporting**: Ensure Laravel's error reporting is configured to display errors in development and log them in production.

## 7. Testing Strategy

To ensure the module works correctly from the first go, we will implement a robust testing strategy at each step of development:

-   **Unit Tests (for Core Logic)**:
    -   Write unit tests for the `Post` model and related helper classes to verify their individual functionalities (e.g., slug generation, relationship integrity, attribute casting).
    -   Test Livewire components (`CreatePost`, `EditPost`) in isolation where possible, verifying property updates, method calls, and state changes without full browser interaction.
    -   Use Laravel's built-in testing utilities (`Pest` or `PHPUnit`) for these tests.
-   **Feature Tests (for Integration)**:
    -   Develop feature tests to simulate user interactions with Livewire components, specifically focusing on form submissions for creating and editing posts.
    -   Verify that validation rules are correctly applied and error messages are displayed.
    -   Test the end-to-end flow of image uploads (for featured image).
    -   Ensure proper redirects or modal closures occur upon successful operations.
-   **Manual Testing at Each Step**:
    -   In addition to automated tests, conduct thorough manual testing for each feature from the beginning of its development.
    -   Visually inspect the UI, interact with all elements, and attempt to trigger known edge cases identified in Section 3.
    -   **Crucially, verify database state and check logs (both application and server logs) at every significant step to ensure data integrity and identify any backend issues immediately.**

By following this layered testing approach, we aim to catch issues early and ensure the stability and correctness of the Post module.

## 5. Next Steps (Implementation Checklist)

**Phase 1: Core Post Module (without Trix)**

1.  **Database Migration**:
    *   [ ] Verify or create a migration for the `posts` table with all necessary CMS-style fields (title, slug, excerpt, content, status, is_premium, is_featured, tags, meta_title, meta_description, published_at, reading_time, difficulty_level, and `featured_image`).
    *   [ ] Ensure the `featured_image` column is set up correctly (e.g., `string` for path, `nullable`).
    *   [ ] Add a unique constraint to the `slug` column.
    *   [ ] Run `php artisan migrate`.

2.  **`Post` Model (`app/Models/Post.php`)**:
    *   [ ] Define all fillable properties (`$fillable`).
    *   [ ] Add casts for `tags` (as `array` or `json`).
    *   [ ] Implement slug generation in the `boot` method (e.g., using `creating` event and `Str::slug`).
    *   [ ] Add an accessor for `featured_image_url` to retrieve the full URL.
    *   [ ] Implement query scopes (e.g., `published()`) for content visibility based on `status` and `published_at`.

3.  **Create `PostManagement` Component**:
    *   [ ] Create `app/Livewire/AdminPanel/Posts/PostManagement.php`.
    *   [ ] Create `resources/views/admin_panel/posts/livewire/post-management.blade.php`.
    *   [ ] Implement basic listing of posts from the database.
    *   [ ] Add search and filter functionality (if desired at this stage).
    *   [ ] Set up buttons/actions to trigger the `CreatePost` and `EditPost` modals.

4.  **Create `CreatePost` Component**:
    *   [ ] Create `app/Livewire/AdminPanel/Posts/CreatePost.php`.
    *   [ ] Create `resources/views/admin_panel/posts/livewire/create-post.blade.php`.
    *   [ ] Define public properties for all post fields (title, content, featured_image, etc.).
    *   [ ] Include `WithFileUploads` trait for `featured_image`.
    *   [ ] Implement `#[Validate]` attributes or `$this->validate()` in the `save()` method.
    *   [ ] Handle `featured_image` upload, storage, and saving the path to the database.
    *   [ ] Ensure validation errors are displayed using `<x-input-error>`.
    *   [ ] Implement the `save()` method to create a new post record.
    *   [ ] Set up modal opening/closing logic (e.g., listening for `openPostCreateModal` event, dispatching `closeModal` and `refreshPostList` events).

5.  **Create `EditPost` Component**:
    *   [ ] Create `app/Livewire/AdminPanel/Posts/EditPost.php`.
    *   [ ] Create `resources/views/admin_panel/posts/livewire/edit-post.blade.php`.
    *   [ ] Define public properties for all post fields.
    *   [ ] Implement `mount($postId)` to retrieve and populate the post data. Handle non-existent posts gracefully.
    *   [ ] Include `WithFileUploads` trait for `featured_image`.
    *   [ ] Implement `#[Validate]` attributes or `$this->validate()` in the `update()` method.
    *   [ ] Handle `featured_image` replacement and old file deletion.
    *   [ ] Implement the `update()` method to modify the existing post record.
    *   [ ] Set up modal opening/closing logic.

6.  **Route Definition**:
    *   [ ] Define a route in `routes/web.php` for the `PostManagement` component, preferably using `Volt::route()` if it's a page component, or ensure it's loaded as a Livewire component within an existing admin route.

7.  **Manual Testing (Phase 1)**:
    *   [ ] Verify post listing works correctly.
    *   [ ] Test creating a new post, including title, content, and especially the **featured image upload**. Check for validation errors and successful saving.
    *   [ ] Test editing an existing post, including changing content and **replacing the featured image**.
    *   [ ] Monitor `storage/logs/laravel.log` and browser console for errors.

**Phase 2: Trix Editor Integration (After Phase 1 is fully working)**

1.  **Install Trix Editor**:
    *   [ ] Install Trix via npm: `npm install trix`
    *   [ ] Include Trix CSS in your `app.css` (or equivalent): `/* In resources/css/app.css or similar */ @import 'trix/dist/trix.css';`
    *   [ ] Include Trix JavaScript in your `app.js` (or equivalent): `// In resources/js/app.js or similar import 'trix';`

2.  **Create `TrixAttachment` Model and Migration**:
    *   [ ] Create a migration for a `trix_attachments` table with `path`, `original_name`, `identifier`, `attachable_id`, `attachable_type`, `disk` fields.
    *   [ ] Create `app/Models/TrixAttachment.php` and define `morphTo` relationship.

3.  **Develop `x-trix-editor` Blade Component**:
    *   [ ] Create `resources/views/components/trix-editor.blade.php`.
    *   [ ] Encapsulate Trix editor HTML.
    *   [ ] Implement Alpine.js to bind Trix content to Livewire property: `<trix-editor x-on:trix-change="$wire.content = $event.target.value"></trix-editor>`.
    *   [ ] Implement JavaScript to handle `trix-attachment-add` and `trix-attachment-remove` events, interacting with a backend endpoint for image uploads/deletions.

4.  **Create `TrixAttachmentController`**:
    *   [ ] Create a controller to handle Trix attachment uploads (store temporary files, create `TrixAttachment` records, return JSON response with URL/identifier).
    *   [ ] Implement logic for deleting temporary files when attachments are removed from the editor.

5.  **Update `CreatePost` and `EditPost` Components for Trix**:
    *   [ ] Integrate the `x-trix-editor` component for the `content` field.
    *   [ ] Update `save()` and `update()` methods to parse Trix content HTML, extract `data-trix-attachment` identifiers, and link `TrixAttachment` records to the `Post` (by setting `attachable_id` and `attachable_type`).
    *   [ ] Implement scheduled cleanup for orphaned `TrixAttachment` records/files.
    *   [ ] Implement a deleting model event on `Post` to remove associated `TrixAttachment` records and files when a post is deleted.

6.  **Manual Testing (Phase 2)**:
    *   [ ] Test Trix editor functionality: typing, formatting, and **embedding images**.
    *   [ ] Verify images are uploaded, displayed correctly, and correctly associated with the post upon saving.
    *   [ ] Test deleting images from the Trix editor and ensure cleanup.
    *   [ ] Test post deletion to ensure associated Trix images are removed. 

