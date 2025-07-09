<div>
    <form wire:submit.prevent="saveChannel">
        <div>
            <label for="name">Channel Name</label>
            <input type="text" id="name" wire:model="name">
            @error('name') <span>{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="description">Description</label>
            <textarea id="description" wire:model="description"></textarea>
            @error('description') <span>{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="type">Channel Type</label>
            <select id="type" wire:model="type">
                <option value="public">Public</option>
                <option value="premium">Premium</option>
                <option value="private">Private</option>
                <option value="direct">Direct</option>
            </select>
            @error('type') <span>{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="requires_premium">
                <input type="checkbox" id="requires_premium" wire:model="requires_premium">
                Requires Premium Subscription
            </label>
            @error('requires_premium') <span>{{ $message }}</span> @enderror
        </div>

        <button type="submit">Save Channel</button>

        @if (session()->has('message'))
            <div>{{ session('message') }}</div>
        @endif
    </form>
</div>
