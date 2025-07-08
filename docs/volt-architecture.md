# OptionRocket - Volt Architecture

## Project Structure with Livewire Volt

```
resources/views/
├── layouts/
│   ├── app.blade.php           # Main app layout
│   ├── auth.blade.php          # Authentication layout
│   └── guest.blade.php         # Guest layout
│
├── livewire/
│   ├── pages/                  # Page-level Volt components
│   │   ├── auth/
│   │   │   ├── login.blade.php
│   │   │   ├── register.blade.php
│   │   │   ├── forgot-password.blade.php
│   │   │   └── reset-password.blade.php
│   │   │
│   │   ├── dashboard.blade.php
│   │   ├── profile.blade.php
│   │   │
│   │   ├── trading/
│   │   │   ├── alerts.blade.php
│   │   │   ├── portfolio.blade.php
│   │   │   └── pnl.blade.php
│   │   │
│   │   ├── community/
│   │   │   ├── feed.blade.php
│   │   │   ├── chat.blade.php
│   │   │   └── events.blade.php
│   │   │
│   │   ├── education/
│   │   │   ├── courses.blade.php
│   │   │   └── videos.blade.php
│   │   │
│   │   └── admin/
│   │       ├── dashboard.blade.php
│   │       ├── users.blade.php
│   │       ├── posts.blade.php
│   │       └── settings.blade.php
│   │
│   └── components/              # Reusable Volt components
│       ├── navigation/
│       │   ├── navbar.blade.php
│       │   ├── sidebar.blade.php
│       │   └── mobile-menu.blade.php
│       │
│       ├── cards/
│       │   ├── trading-card.blade.php
│       │   ├── alert-card.blade.php
│       │   └── course-card.blade.php
│       │
│       ├── forms/
│       │   ├── contact-form.blade.php
│       │   ├── subscription-form.blade.php
│       │   └── profile-form.blade.php
│       │
│       └── modals/
│           ├── alert-modal.blade.php
│           ├── confirm-modal.blade.php
│           └── payment-modal.blade.php
```

## Benefits of Volt Architecture

### ✅ **Single File Components**
- HTML, PHP logic, and component behavior in one file
- Faster development and easier maintenance
- No need to switch between multiple files

### ✅ **Less Boilerplate**
- No separate PHP class files needed
- Cleaner project structure
- Follows modern component patterns

### ✅ **Perfect for SPA-like Experience**
- Reactive components with wire:navigate
- Smooth transitions between pages
- Real-time updates without page refreshes

### ✅ **Template Integration**
- Easy to convert existing HTML templates
- Direct integration with Tailwind CSS
- Maintains design consistency

## Example Volt Component Structure

```php
<?php
// resources/views/livewire/pages/trading/alerts.blade.php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use App\Models\TradingAlert;

new #[Layout('layouts.app')] class extends Component
{
    public $alerts = [];
    public $filter = 'all';
    
    public function mount()
    {
        $this->loadAlerts();
    }
    
    public function loadAlerts()
    {
        $this->alerts = TradingAlert::when($this->filter !== 'all', function($query) {
            $query->where('type', $this->filter);
        })->latest()->get();
    }
    
    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->loadAlerts();
    }
}; ?>

<div class="container mx-auto px-4 py-8">
    <!-- Filter Buttons -->
    <div class="flex gap-2 mb-6">
        <button wire:click="setFilter('all')" 
                class="px-4 py-2 rounded {{ $filter === 'all' ? 'bg-amber-500 text-white' : 'bg-gray-200' }}">
            Todas
        </button>
        <button wire:click="setFilter('buy')" 
                class="px-4 py-2 rounded {{ $filter === 'buy' ? 'bg-green-500 text-white' : 'bg-gray-200' }}">
            Compra
        </button>
        <button wire:click="setFilter('sell')" 
                class="px-4 py-2 rounded {{ $filter === 'sell' ? 'bg-red-500 text-white' : 'bg-gray-200' }}">
            Venta
        </button>
    </div>
    
    <!-- Alerts Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($alerts as $alert)
            <livewire:components.cards.alert-card :alert="$alert" :key="$alert->id" />
        @endforeach
    </div>
</div>
```

## Migration Strategy

### Phase 1: Convert Templates to Volt Components
1. Take existing HTML templates from `/templates/Template/`
2. Convert to Volt components with proper PHP logic
3. Maintain exact design while adding interactivity

### Phase 2: Add Reactive Features
1. Real-time updates with Livewire
2. Form handling without page refreshes
3. Dynamic content loading

### Phase 3: Optimize Performance
1. Lazy loading for components
2. Efficient database queries
3. Caching strategies

## Best Practices

### 🎯 **Component Organization**
- **Pages**: Full-page components with layouts
- **Components**: Reusable UI elements
- **Forms**: Dedicated form components with validation

### 🎯 **State Management**
- Use Volt's built-in state management
- Leverage Livewire's reactivity
- Minimize JavaScript when possible

### 🎯 **Performance**
- Use lazy loading for heavy components
- Implement proper caching
- Optimize database queries

### 🎯 **Testing**
- Test Volt components with Livewire testing utilities
- Browser testing for interactive features
- Unit tests for business logic

## Integration with Existing Templates

All templates in `/templates/Template/` can be converted to Volt components:

- `dashboard.html` → `livewire/pages/dashboard.blade.php`
- `alerts.html` → `livewire/pages/trading/alerts.blade.php`
- `chat.html` → `livewire/pages/community/chat.blade.php`
- `courses.html` → `livewire/pages/education/courses.blade.php`
- `profile.html` → `livewire/pages/profile.blade.php`

Each maintains the beautiful design while adding Laravel's power and Livewire's reactivity. 
