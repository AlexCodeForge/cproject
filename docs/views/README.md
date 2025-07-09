# Option Rocket Views & Frontend Architecture

This document outlines the structure and conventions for the views and frontend assets in the Option Rocket application.

## Blade Layouts

The application uses two primary layout files for its main panels:

-   `layouts/app.blade.php`: The main layout for the **user-facing panel**. This includes the user dashboard, feed, profile, etc.
-   `layouts/admin.blade.php`: The main layout for the **admin panel**.

Both layouts provide a consistent structure with a responsive, expandable sidebar and a main content area.

## Shared Frontend Assets

To promote code reuse and maintainability, common CSS and JavaScript for the layouts have been centralized into shared files.

-   **`resources/css/shared-layout.css`**: Contains all the CSS rules for the sidebar's collapsed/expanded states, logo animation, and other shared layout styles.
-   **`resources/js/shared-layout.js`**: Contains all the JavaScript logic for managing:
    -   Sidebar expand/collapse on hover.
    -   Light/Dark theme toggling and persistence in `localStorage`.
    -   The notifications panel overlay.

### Key JavaScript Functions

The `shared-layout.js` script handles event listeners for both initial page load (`DOMContentLoaded`) and Livewire navigation (`livewire:navigated`) to ensure functionality works seamlessly in our single-page application (SPA) style environment.

-   `initializeLayoutFunctionality()`: The main function that sets up all event listeners.
-   `setupSidebarEvents()`: Manages the `mouseenter` and `mouseleave` events for the sidebar.
-   `setupThemeToggle()`: Handles theme switching.
-   `setupNotifications()`: Controls the notification panel visibility.

## Asset Compilation with Vite

The shared assets are included in the application build process via Vite. They are registered in the `input` array within `vite.config.js`:

```javascript
// vite.config.js
// ...
input: [
    'resources/css/app.css',
    'resources/js/app.js',
    'resources/js/admin_dashboard.js',
    'resources/css/shared-layout.css',
    'resources/js/shared-layout.js',
],
// ...
```

Both `layouts/app.blade.php` and `layouts/admin.blade.php` include these compiled assets using the `@vite` Blade directive:

```blade
@vite([
    'resources/css/app.css',
    'resources/js/app.js',
    'resources/css/shared-layout.css',
    'resources/js/shared-layout.js'
])
```

*(Note: `admin.blade.php` also includes `admin_dashboard.js` for its specific functionality.)*

This centralized approach ensures that any updates to shared layout components are reflected in both the user and admin panels simultaneously, reducing code duplication and simplifying maintenance.
