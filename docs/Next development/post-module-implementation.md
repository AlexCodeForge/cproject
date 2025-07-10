# Post Module Implementation Plan

## 1. Overview

This document outlines the implementation plan for revamping the post management module to include a WYSIWYG editor (Trix) seamlessly integrated with Livewire. The goal is to create a robust, CMS-like user interface for managing posts, leveraging Livewire for dynamic interactions and modals for key actions.

The implementation will draw inspiration from the existing `/app/Livewire/AdminPanel/Users` module's structure, adapting it for post management with separate views for listing, creating, and editing.

## 2. Core Components

### 2.1. Livewire Components

We will create three primary Livewire components within `app/Livewire/AdminPanel/Posts`:

-   **`PostManagement.php`**: This component will serve as the main entry point for listing posts, similar to `UserManagement.php`. It will contain the logic for searching, filtering, and displaying the post table.
-   **`CreatePost.php`**: This component will handle the creation of new posts. It will include the Trix editor for the main content. This will be displayed within a modal.
-   **`EditPost.php`**: This component will handle editing existing posts. It will also utilize the Trix editor and be displayed within a modal.
    -   **Edge Case: Form Submission Failures (Validation)**
        -   **Scenario:** A user attempts to create or update a post, but one or more fields fail validation (e.g., missing title, invalid slug).
        -   **Mitigation:** Livewire's built-in validation should be used extensively.
            *   Implement `#[Validate]` attributes on public properties or use the `$this->validate()` method in the Livewire components.
            *   Display validation errors next to the relevant input fields using `<x-input-error>` (already available in the project) and `wire:model.live` or `wire:offline` for immediate feedback.
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

### 2.3. Trix Editor Component

A reusable Blade component for the Trix editor will be created:

-   **`resources/views/components/trix-editor.blade.php`**: This component will encapsulate the Trix editor HTML and JavaScript, ensuring proper `wire:model` binding for Livewire. This component will handle the dynamic syncing of Trix content with Livewire properties.

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
    -   **`content`**: This is the primary field for the rich text from the Trix editor. It will store the HTML output, including embedded Trix attachments (text/longtext). Livewire will bind to this field directly via `wire:model`.
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

-   **Trix Editor Images (Content Images)**:
    -   Images embedded *within* the Trix editor content are handled as **Trix Attachments**. These are separate entities from the `featured_image`.
    -   A dedicated `TrixAttachment` model (which will need to be created/re-introduced along with its migration and controller) will store metadata about these uploaded images (e.g., path, original name, identifier).
    -   The `Post` model will establish a `morphMany` relationship to `TrixAttachment` (`public function trixAttachments() { return $this->morphMany(TrixAttachment::class, 'attachable'); }`). This allows us to associate all Trix-uploaded images with a specific `Post` record.
    -   The `content` field in `Post.php` will store the raw HTML from the Trix editor. This HTML will contain special `data-trix-attachment` attributes referencing the `identifier` of the `TrixAttachment` records.
    -   When a post is saved (upon form submission), the Livewire component will parse the `content` HTML, extract these `data-trix-attachment` identifiers, and update the corresponding `TrixAttachment` records to link them to the newly created or updated `Post` (by setting `attachable_id` and `attachable_type`).
    -   **Edge Case: Orphaned `TrixAttachment` Records/Files**
        -   **Scenario:** A user uploads images via the Trix editor but then cancels the post creation or deletes the images from the editor without saving the post. The files remain on disk and records in `trix_attachments` table.
        -   **Mitigation:**
            *   **Temporary Storage & Cleanup:** When Trix uploads an image, initially store it in a temporary location (e.g., `storage/app/public/trix-tmp`). The `TrixAttachment` record should be created with a `pending` status or `attachable_id` being `null`. Implement a scheduled command (e.g., daily or hourly) that deletes `TrixAttachment` records and their associated files that are older than a certain age (e.g., 24 hours) and still have a `pending` status or `null` `attachable_id`.
            *   **Client-side Cleanup:** When a user removes an image from Trix, the `trix-attachment-remove` event should trigger a backend endpoint to delete the temporary file and `TrixAttachment` record immediately.
    -   **Edge Case: `TrixAttachment` not linked to `Post` on save**
        -   **Scenario:** The Livewire component fails to parse the `content` HTML or update the `TrixAttachment` records correctly, leading to images not being associated with the post.
        -   **Mitigation:**
            *   Robust parsing logic: Ensure the backend endpoint that receives the Trix content reliably extracts `data-trix-attachment` identifiers.
            *   Transaction for saving: Wrap the post saving and `TrixAttachment` linking logic in a database transaction to ensure atomicity. If one part fails, the whole operation rolls back.
    -   **Edge Case: Deleting a Post with Trix Attachments**
        -   **Scenario:** When a `Post` is deleted, its associated `TrixAttachment` records and their physical image files are not removed, leading to orphaned files and database entries.
        -   **Mitigation:**
            *   Use the `deleting` model event on the `Post` model to delete associated `TrixAttachment` records and their files before the post itself is deleted.
            *   Or, in the `TrixAttachment` model, define a `deleting` event that removes the physical file from storage.
    -   **Edge Case: Large Image Uploads in Trix**
        -   **Scenario:** Users upload very large images through Trix, consuming excessive storage or causing slow page loads.
        -   **Mitigation:**
            *   **Server-side validation:** In the `TrixAttachmentController` (or similar upload endpoint), enforce file size limits and allowed MIME types. Return appropriate error messages to the Trix editor if validation fails.
            *   Consider client-side image resizing before upload (e.g., using a JavaScript library) for better UX, though this can add complexity.

-   **Featured Image (`featured_image`)**:
    -   The `featured_image` field in `Post.php` will store the path or a JSON structure containing details (like different sizes/formats) of the main visual for the post. It is a single, primary image for the post's thumbnail/banner, distinct from any images within the content itself.
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

### 3.2. Trix Editor with Livewire

-   **`wire:model` Binding**: The `x-trix-editor` component will use `wire:model` (without `.live`, `.blur`, or `.lazy` modifiers unless specifically needed for other minor reactive elements) to bind the Trix editor's content to a public property (e.g., `$content`) in the `CreatePost` and `EditPost` Livewire components. This ensures the component property holds the latest content, but **does not trigger immediate server requests for every keystroke.** The actual saving to the database will be handled by the `save()` or `publish()` actions.
-   **JavaScript Integration**: Custom JavaScript within `trix-editor.blade.php` will be necessary to listen for `trix-change` events and update the hidden input that `wire:model` is bound to. This ensures Livewire has the correct content when the form is submitted. The JavaScript will also handle the `trix-attachment-add` and `trix-attachment-remove` events for image uploads and deletions, interacting with a dedicated backend endpoint.
    -   **Edge Case: Trix Content Not Syncing with Livewire Property**
        -   **Scenario:** The JavaScript handling `trix-change` events fails, resulting in the Livewire component receiving outdated content on submission.
        -   **Mitigation:** Ensure the JavaScript explicitly updates the value of the hidden input element that `wire:model` is bound to. Thoroughly test the JavaScript integration with Livewire to ensure the property is always up-to-date at submission.
-   **File Uploads**: The Trix editor supports image attachments. We will need a dedicated controller endpoint (e.g., `TrixAttachmentController`) to handle image uploads from Trix. This endpoint will receive the image, store it, create a `TrixAttachment` record in the database, and return a JSON response with the attachment's URL and identifier back to the Trix editor. This allows images to be properly embedded and displayed within the editor content.

### 3.3. Modals for Create/Edit

Similar to the User module, we will implement the `CreatePost` and `EditPost` Livewire components as modals.

-   The `PostManagement` component will be responsible for triggering the display of these modals (e.g., `Livewire.dispatch('openPostCreateModal')`).
-   The modal content will be loaded dynamically using Livewire's modal features.

### 3.4. UI/UX Considerations

-   **Consistent Styling**: Maintain the existing UI theme for a cohesive CMS experience.
-   **Real-time Feedback**: Livewire's reactivity will provide instant validation feedback to the user.
-   **User-Friendly Trix**: Ensure the Trix editor is configured with necessary options (e.g., placeholder, toolbar).

### 3.5. Performance Considerations

-   **Edge Case: Large Posts (Many Trix Attachments/Extensive Content)**
    -   **Scenario:** Posts with a very large amount of content or many embedded Trix images might cause performance issues on load or save.
    -   **Mitigation:** Consider lazy loading images within Trix content if needed. Ensure efficient database queries (e.g., eager loading attachments) to prevent slow loads. Optimize content rendering on the frontend.

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

resources/views/components/
└── trix-editor.blade.php   // Reusable Trix editor component
```

## 5. Next Steps

**Important:** Before creating any new files, always ensure to delete any existing files with the same names to prevent conflicts and ensure a clean implementation.

1.  **Install Trix Editor**:
    -   Install Trix via npm: `npm install trix`
    -   Include Trix CSS in your `app.css` (or equivalent): `/* In resources/css/app.css or similar */ @import 'trix/dist/trix.css';`
    -   Include Trix JavaScript in your `app.js` (or equivalent): `// In resources/js/app.js or similar import 'trix';`
    -   For detailed instructions, refer to the official Trix GitHub repository: [Trix GitHub](https://github.com/basecamp/trix)

2.  Create `PostManagement.php` and its corresponding `post-management.blade.php`.
3.  Develop the `x-trix-editor` Blade component (`resources/views/components/trix-editor.blade.php`) with proper Livewire integration.
4.  Create `CreatePost.php` and `create-post.blade.php`, incorporating the `x-trix-editor` and modal logic.
5.  Create `EditPost.php` and `edit-post.blade.php`, reusing the `x-trix-editor` and modal logic.
6.  Update `Post.php` to handle Trix content.
7.  Implement image upload handling for Trix attachments.

## 6. Debugging Strategy

To ensure we know what's wrong at any step, we must implement proper debugging throughout the development process:

-   **JavaScript Debugging (Frontend)**:
    -   Utilize `console.log()` extensively in `resources/js/app.js`, `shared-layout.js`, and especially within the `trix-editor.blade.php` JavaScript.
    -   Monitor Trix events (`trix-change`, `trix-attachment-add`, `trix-attachment-remove`) and the values being sent to Livewire.
    -   Use browser developer tools to inspect Livewire network requests and responses, checking payload data and any returned errors.
-   **Laravel Logging (Backend)**:
    -   Use Laravel's `Log` facade (`Log::info()`, `Log::error()`, `Log::debug()`) in Livewire components (`PostManagement.php`, `CreatePost.php`, `EditPost.php`), the `TrixAttachmentController`, and the `Post` model.
    -   Log validation failures, database operations (create, update, delete), file upload successes/failures, and any unexpected behavior. These logs must be checked consistently from the start of development for each feature.
    -   Ensure sensitive data is not logged.
    -   Regularly check the `storage/logs/laravel.log` file.
-   **Livewire Debugbar** (If installed): Leverage it to inspect component state, re-renders, network calls, and actions.
-   **Error Reporting**: Ensure Laravel's error reporting is configured to display errors in development and log them in production.

## 7. Testing Strategy

To ensure the module works correctly from the first go, we will implement a robust testing strategy at each step of development:

-   **Unit Tests (for Core Logic)**:
    -   Write unit tests for the `Post` model, `TrixAttachment` model, and related helper classes to verify their individual functionalities (e.g., slug generation, relationship integrity, attribute casting).
    -   Test Livewire components (`CreatePost`, `EditPost`) in isolation where possible, verifying property updates, method calls, and state changes without full browser interaction.
    -   Use Laravel's built-in testing utilities (`Pest` or `PHPUnit`) for these tests.
-   **Feature Tests (for Integration)**:
    -   Develop feature tests to simulate user interactions with Livewire components, specifically focusing on form submissions for creating and editing posts.
    -   Verify that validation rules are correctly applied and error messages are displayed.
    -   Test the end-to-end flow of Trix editor content saving, including image uploads and their association with the `Post` model.
    -   Ensure proper redirects or modal closures occur upon successful operations.
-   **Browser/End-to-End Tests (for UI/UX)**:
    -   Utilize Laravel Dusk (or a similar browser automation tool) to simulate real user interactions in the browser.
    -   Test the visual rendering of the Trix editor, interaction with the toolbar, and correct display of embedded images.
    -   Verify the behavior of modals (opening, closing, content loading) and overall UI responsiveness.
    -   Ensure seamless integration of Livewire with the Trix editor's JavaScript events.
-   **Manual Testing at Each Step**:
    -   In addition to automated tests, conduct thorough manual testing for each feature from the beginning of its development.
    -   Visually inspect the UI, interact with all elements, and attempt to trigger known edge cases identified in Section 3.
    -   **Crucially, verify database state and check logs (both application and server logs) at every significant step to ensure data integrity and identify any backend issues immediately.**

By following this layered testing approach, we aim to catch issues early and ensure the stability and correctness of the Post module. 

