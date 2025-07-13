### Modal Implementation Guide

This document outlines the usage and implementation of the standard modal components available in the application: `ConfirmationModal`, `SuccessModal`, and `ErrorModal`. These components are designed to provide consistent user feedback and interaction for various scenarios.

All modals are pre-loaded in the `resources/views/layouts/admin.blade.php` layout file, meaning they are globally available and can be dispatched from any Livewire component.

### Quick Reference

| Modal Type     | Event Name              | Required Parameters                             | Optional Parameters |
| :------------- | :---------------------- | :---------------------------------------------- | :------------------ |
| **Success**    | `showSuccessModal`      | `message` (string)                              | `title` (string)    |
| **Error**      | `showErrorModal`        | `message` (string / array)                      | `title` (string)    |
| **Confirmation** | `showConfirmationModal` | `title`, `message`, `confirmAction` (all strings) | `params` (array)    |


---

### 1. Success Modal (`SuccessModal.php`)

**Purpose:** To display a success message to the user after a successful operation.

**Usage:**
To show the success modal, dispatch the `showSuccessModal` event from a Livewire component's method.

**Example:**

```php
// In a Livewire component method (e.g., after saving data)
public function save()
{
    // ... save logic ...
    $this->dispatch('showSuccessModal', '¡Operación completada exitosamente!');
}
```

You can optionally provide a custom title:

```php
// In a Livewire component method
$this->dispatch('showSuccessModal', message: '¡Datos actualizados!', title: 'Actualización Exitosa');
```

---

### 2. Error Modal (`ErrorModal.php`)

**Purpose:** To display an error message to the user, typically after a failed operation or validation error.

**Usage:**
To show the error modal, dispatch the `showErrorModal` event from any Livewire component.

**Example (Validation):**

```php
// In a Livewire component method (e.g., on validation failure)
try {
    $this->validate();
    // ... successful operation ...
} catch (\Illuminate\Validation\ValidationException $e) {
    // Collect all validation errors, flatten them, and pass as an array to the modal.
    // The modal's view will then iterate through this array to display each error as a list item.
    $this->dispatch('showErrorModal', message: collect($e->errors())->flatten()->toArray(), title: 'Error de Validación');
    throw $e; // Re-throw the exception to ensure individual field errors are still displayed by Livewire.
}
```

**Important Note:** This error modal provides a prominent alert for general validation or operation errors. However, it is crucial to **also retain the session-based error messages that are typically displayed directly under individual input fields**. This ensures users receive specific feedback on which fields require correction.

You can optionally provide a custom title for other types of errors:

```php
// In a Livewire component method
$this->dispatch('showErrorModal', message: 'No se pudo conectar al servidor.', title: 'Error de Conexión');
```

---

### 3. Confirmation Modal (`ConfirmationModal.php`)

**Purpose:** To ask the user for confirmation before performing a destructive or irreversible action.

**Usage:**
You can trigger the confirmation modal from a Blade view (most common) or a Livewire component method.

#### From a Blade View (Recommended)

This is the quickest way to add a confirmation step to a user action, like clicking a "Delete" or "Cancel" button.

**Example (`wire:click`):**
```html
<!-- Example: A "Cancel Subscription" button inside a loop -->
<button wire:click="$dispatch('showConfirmationModal', {
    title: 'Confirmar Cancelación',
    message: '¿Seguro que quieres cancelar la suscripción de {{ $subscription->user->name }}?',
    confirmAction: 'cancelSubscription',
    params: [{{ $subscription->id }}]
})" class="text-red-500 hover:text-red-700">
    Cancelar
</button>
```

#### From a Livewire Component (PHP)

This is useful if you need to perform some logic *before* showing the confirmation dialog.

**Example:**
```php
// In a Livewire component method
public function requestItemDeletion($itemId)
{
    $item = Item::find($itemId);
    // You could add logic here, e.g., check permissions
    
    $this->dispatch('showConfirmationModal',
        title: 'Confirmar Eliminación',
        message: '¿Estás seguro de que quieres eliminar ' . $item->name . '?',
        confirmAction: 'deleteItem',
        params: [$itemId]
    );
}
```

### Handling the Confirmation

After the user clicks "Confirm" in the modal, the `confirmAction` event is dispatched. You need a listener in your Livewire component to handle this.

**Example Listener:**

```php
// In the same Livewire component
use Livewire\Attributes\On;

class YourComponent extends Component
{
    #[On('deleteItem')]
    public function deleteItem(int $itemId)
    {
        // Logic to delete the item
        Item::destroy($itemId);
        
        // Optionally, show a success modal after deletion
        $this->dispatch('showSuccessModal', 'Elemento eliminado correctamente.');
    }
    
    // ... other component methods ...
}
```

The `params` array from the dispatch call will be passed as arguments to your listener method.
