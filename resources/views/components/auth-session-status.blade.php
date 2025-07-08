@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-medium text-sm text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-3 flex items-center gap-2']) }}>
        <ion-icon name="checkmark-circle-outline" class="text-green-600 dark:text-green-400"></ion-icon>
        {{ $status }}
    </div>
@endif
