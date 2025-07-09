# Admin Panel Development Guide

This document outlines the conventions and architecture for building modules within the admin panel of our application. Following these guidelines ensures consistency, maintainability, and reusability.

## Core Philosophy

Our admin panel is built using a modular approach with Livewire. Each "feature" or "resource" (e.g., Users, Chat Channels, Posts) is treated as a self-contained module. The core idea is to separate page-level concerns from the complex logic of interactive components like tables and forms.

## 1. Directory Structure

A consistent directory structure is crucial. All admin-panel-related Livewire components must be placed as follows:

-   **PHP Component Classes**: `app/Livewire/AdminPanel/{ModuleName}/`
    -   Example: `app/Livewire/AdminPanel/Chat/ChannelManagement.php`
    -   Example: `app/Livewire/AdminPanel/Chat/ChannelTable.php`

-   **Component Blade Views**: `resources/views/admin_panel/{module-name}/`
    -   The main "page" view: `resources/views/admin_panel/chat/channel-management.blade.php`
    -   Views for sub-components are placed in a `livewire` subdirectory: `resources/views/admin_panel/chat/livewire/channel-table.blade.php`

## 2. Component Architecture

We use a two-level component architecture for most modules.

### a. Page Component (The "Manager")

This is the main entry point for an admin section. It's a simple container component with minimal logic.

-   **Purpose**: To set the page layout and load the primary functional component.
-   **Naming Convention**: `{ModuleName}Management.php` (e.g., `ChannelManagement.php`).
-   **Example (`ChannelManagement.php`)**:
    ```php
    <?php
    namespace App\Livewire\AdminPanel\Chat;

    use Livewire\Component;
    use Livewire\Attributes\Layout;

    #[Layout('layouts.admin')] // Sets the main admin layout
    class ChannelManagement extends Component
    {
        public function render()
        {
            // Just renders the corresponding view.
            return view('admin_panel.chat.channel-management');
        }
    }
    ```
-   **Associated View (`channel-management.blade.php`)**: This view does nothing but include the functional component.
    ```blade
    <div>
        <livewire:admin-panel.chat.channel-table />
    </div>
    ```

### b. Functional Component (The "Table", "Form", etc.)

This component contains all the business logic, state, and interactivity for the module.

-   **Purpose**: To handle data fetching, user actions (create, edit, delete), search, filtering, and interaction with modals.
-   **Naming Convention**: Descriptive of its function (e.g., `ChannelTable.php`, `UserForm.php`).
-   **Example**: `ChannelTable.php` is a perfect example, containing all logic for pagination, searching, and CRUD operations on chat channels.

## 3. Reusable Global Modals

We have a set of global, event-driven modals to provide a consistent user experience for common interactions. These modals live in `app/Livewire/Components/Modals/` and are included in the main `layouts/admin.blade.php` file, making them accessible from anywhere in the admin panel.

**Do not create local, single-use modals within your components.** Always use the global ones.

### a. `ConfirmationModal`

-   **Purpose**: To ask the user for confirmation before performing a destructive action.
-   **How to Trigger**: Dispatch the `showConfirmationModal` event.
    ```php
    // In any Livewire component
    $this->dispatch('showConfirmationModal',
        title: 'Delete User',
        message: 'Are you sure you want to delete this user?',
        confirmAction: 'delete-user', // The event to dispatch upon confirmation
        params: ['userId' => $userId] // Parameters for the confirmation event
    );
    ```
-   **How to Handle Confirmation**: Create a listener for the `confirmAction` event.
    ```php
    #[On('delete-user')]
    public function deleteUser(int $userId)
    {
        User::find($userId)->delete();
        // ...
    }
    ```

### b. `SuccessModal`

-   **Purpose**: To inform the user that an action was completed successfully.
-   **How to Trigger**: Dispatch the `showSuccessModal` event.
    ```php
    // In any Livewire component
    $this->dispatch('showSuccessModal', 'The user was updated successfully.');

    // With a custom title
    $this->dispatch('showSuccessModal', 'User Updated!', 'The user was updated successfully.');
    ```

### c. `ErrorModal`

-   **Purpose**: To inform the user that an error has occurred.
-   **How to Trigger**: Dispatch the `showErrorModal` event.
    ```php
    // In any Livewire component
    $this->dispatch('showErrorModal', 'The user could not be found.');

    // With a custom title
    $this->dispatch('showErrorModal', 'An Error Occurred', 'The user could not be found.');
    ```

## 4. Routing

Routes for admin panel modules should point directly to the **Page Component** class.

-   **File**: `routes/web.php`
-   **Example**:
    ```php
    use App\Livewire\AdminPanel\Chat\ChannelManagement;

    Route::prefix('admin')->middleware(['auth', 'role:admin'])->name('admin.')->group(function () {
        // ... other admin routes
        Route::get('/chat/channels', ChannelManagement::class)->name('chat.channels');
    });
    ```
This setup ensures that the request goes through the simple page component, which then correctly sets up the layout and loads the main functional component. 
