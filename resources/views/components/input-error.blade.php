@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'text-sm text-red-500 dark:text-red-400 space-y-1']) }}>
        @foreach ((array) $messages as $message)
            <li class="flex items-center gap-1">
                <ion-icon name="alert-circle-outline" class="text-red-500 dark:text-red-400"></ion-icon>
                {{ $message }}
            </li>
        @endforeach
    </ul>
@endif
