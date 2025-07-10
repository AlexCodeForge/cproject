### Modal Implementation Guide

This document outlines the usage and implementation of the standard modal components available in the application: `ConfirmationModal`, `SuccessModal`, and `ErrorModal`. These components are designed to provide consistent user feedback and interaction for various scenarios.

All modals are pre-loaded in the `resources/views/layouts/admin.blade.php` layout file, meaning they are globally available and can be dispatched from any Livewire component.

#### 1. Success Modal (`SuccessModal.php`)

**Purpose:** To display a success message to the user after a successful operation.

**Usage:**
To show the success modal, dispatch the `showSuccessModal` event from any Livewire component.

**Example:**

```php
// In a Livewire component method (e.g., after saving data)
$this->dispatch('showSuccessModal', '¡Operación completada exitosamente!');
```

You can optionally provide a custom title:

```php
// In a Livewire component method
$this->dispatch('showSuccessModal', message: '¡Datos actualizados!', title: 'Actualización Exitosa');
```

#### 2. Error Modal (`ErrorModal.php`)

**Purpose:** To display an error message to the user, typically after a failed operation or validation error.

**Usage:**
To show the error modal, dispatch the `showErrorModal` event from any Livewire component.

**Example:**

```php
// In a Livewire component method (e.g., on validation failure)
try {
    $this->validate();
    // ... successful operation ...
} catch (\Illuminate\Validation\ValidationException $e) {
    // Collect all validation errors, flatten them, and pass as an array to the modal.
    // The modal's view will then iterate through this array to display each error as a list item.
    $this->dispatch('showErrorModal', message: collect($e->errors())->flatten()->toArray(), title: 'Validation Error');
    throw $e; // Re-throw the exception to ensure individual field errors are still displayed by Livewire.
}
```

**Important Note:** This error modal provides a prominent alert for general validation or operation errors. However, it is crucial to **also retain the session-based error messages that are typically displayed directly under individual input fields**. This ensures users receive specific feedback on which fields require correction.

You can optionally provide a custom title:

```php
// In a Livewire component method
$this->dispatch('showErrorModal', message: 'No se pudo conectar al servidor.', title: 'Error de Conexión');
```

#### 3. Confirmation Modal (`ConfirmationModal.php`)

**Purpose:** To ask the user for confirmation before performing a destructive or irreversible action.

**Usage:**
To show the confirmation modal, dispatch the `showConfirmationModal` event from any Livewire component. You must provide a title, a message, a `confirmAction` event name, and optional `params`.

**Example:**

```php
// In a Livewire component method (e.g., before deleting an item)
$this->dispatch('showConfirmationModal',
    title: 'Confirmar Eliminación',
    message: '¿Estás seguro de que quieres eliminar este elemento? Esta acción no se puede deshacer.',
    confirmAction: 'delete-item', // This event will be dispatched if the user confirms
    params: ['itemId' => $itemId] // Optional parameters to pass to the confirmAction listener
);

// In the same Livewire component, or another component listening for 'delete-item'
#[On('delete-item')]
public function deleteItem(int $itemId)
{
    // Logic to delete the item
    // ...
    $this->dispatch('showSuccessModal', 'Elemento eliminado correctamente.');
}
```

Remember to add an `#[On('your-confirm-action-name')]` attribute to the method that will handle the confirmed action in your Livewire component.
