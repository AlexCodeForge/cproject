# Views & Templates Directory Structure

This document provides an overview of the directory structure for views and templates within the project.

## `resources/views`

This is the primary directory for all Laravel Blade views that are rendered by the application's controllers and Livewire components.

### Core Directories

-   **`auth/`**: Contains all views related to user authentication (login, registration, password reset, etc.).
-   **`components/`**: Holds reusable Blade components, such as modals, buttons, and form inputs. These are used across the application to maintain a consistent UI.
-   **`layouts/`**: Defines the main application layouts.
    -   `app.blade.php`: The main layout for the authenticated user-facing panel.
    -   `admin.blade.php`: The main layout for the admin panel.
    -   `guest.blade.php`: The layout for pages accessible to unauthenticated users.
-   **`livewire/`**: This directory is intended for Livewire component views that are not full pages but are included within other views. The structure often mirrors the `app/Livewire` namespace.
-   **`admin_panel/`**: Contains views specific to the admin dashboard and its various sections (users, posts, etc.). These are typically full-page views rendered by the `AdminPanel` Livewire components.
-   **`user_panel/`**: Contains views for the main user-facing dashboard and its features (feed, chat, courses, etc.). These are the full-page views rendered by the `UserPanel` Livewire components.
-   **`emails/`**: Blade templates for all outgoing emails.

### Naming Conventions

-   Views for full-page Livewire components are typically placed in `admin_panel/` or `user_panel/`.
-   The view name matches the component name (e.g., `UserManagement.php` renders `user_management.blade.php`).
-   Reusable, non-page components have their views in `resources/views/components/`.

## `templates/`

This root directory contains the original static HTML templates that the application's design is based on.

-   **Purpose**: It serves as a design reference and a source for UI components that are being converted into dynamic Blade and Livewire components.
-   **`Template/`**: Contains the core HTML files for different pages of the original design.
-   **`ui-components/`**: Contains HTML snippets for smaller UI elements like alerts, buttons, and modals.
-   **Usage**: When building a new feature, you can refer to these templates for the required HTML structure and CSS classes. This directory should not be directly served by the web server in production.

This separation allows developers to easily find the original static assets while working with the dynamic Blade views in the standard `resources/views` location. 
