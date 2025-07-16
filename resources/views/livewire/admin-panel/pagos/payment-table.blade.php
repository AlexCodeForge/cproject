<div>
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 dark:text-white">Gestión de Pagos</h1>
        <div class="flex items-center gap-4">
            <div class="flex items-center space-x-4">
                <x-text-input wire:model.live.debounce.300ms="search" placeholder="Buscar por usuario..." class="px-4 py-2 rounded-lg" />
                <select wire:model.live="status" class="w-48 px-4 py-2 pr-10 rounded-lg border border-stone-300 dark:border-gray-600 bg-white dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-slate-400">
                    <option value="">Todos los Estados</option>
                    <option value="active">Activo</option>
                    <option value="canceled">Cancelado</option>
                    <option value="incomplete">Incompleto</option>
                    <option value="trialing">En Prueba</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Payment Management Widgets -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-600 dark:text-gray-400">Total Suscripciones</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $totalSubscriptions }}</p>
                </div>
                <div class="bg-blue-100 dark:bg-blue-900/50 p-3 rounded-full">
                    <x-ionicon-card-outline class="text-blue-600 dark:text-blue-400 text-xl h-6 w-6" />
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-600 dark:text-gray-400">Suscripciones Activas</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $activeSubscriptions }}</p>
                </div>
                <div class="bg-green-100 dark:bg-green-900/50 p-3 rounded-full">
                    <x-ionicon-checkmark-circle-outline class="text-green-600 dark:text-green-400 text-xl h-6 w-6" />
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-600 dark:text-gray-400">Suscripciones Canceladas</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ $canceledSubscriptions }}</p>
                </div>
                <div class="bg-red-100 dark:bg-red-900/50 p-3 rounded-full">
                    <x-ionicon-close-circle-outline class="text-red-600 dark:text-red-400 text-xl h-6 w-6" />
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-600 dark:text-gray-400">Total Recaudado este Mes</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white">${{ number_format($totalRevenueThisMonth, 2) }}</p>
                </div>
                <div class="bg-emerald-100 dark:bg-emerald-900/50 p-3 rounded-full">
                    <x-ionicon-cash-outline class="text-emerald-600 dark:text-emerald-400 text-xl h-6 w-6" />
                </div>
            </div>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-stone-50 dark:bg-gray-700/50 text-xs text-slate-600 dark:text-gray-400 uppercase">
                <tr>
                    <th class="p-4">Usuario</th>
                    <th class="p-4">Plan (Tipo)</th>
                    <th class="p-4">Estado (Stripe)</th>
                    <th class="p-4">Fecha de Creación</th>
                    <th class="p-4">Finaliza en</th>
                    <th class="p-4">Monto Recibido</th>
                    <th class="p-4">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($subscriptions as $subscription)
                    <tr class="border-b border-stone-200 dark:border-gray-700 hover:bg-stone-50 dark:hover:bg-gray-700/50">
                        <td class="p-4 flex items-center gap-3">
                            <img class="w-10 h-10 rounded-full" src="{{ $subscription->user->avatar_url }}" alt="">
                            <div>
                                <p class="font-semibold text-slate-900 dark:text-white">{{ $subscription->user->name }}</p>
                                <p class="text-slate-500 dark:text-gray-400 text-xs">{{ $subscription->user->email }}</p>
                            </div>
                        </td>
                        <td class="p-4">
                            <span class="text-slate-800 dark:text-gray-300 font-semibold">{{ ucfirst($subscription->type) }}</span>
                        </td>
                        <td class="p-4">
                             <span @class([
                                'text-xs font-semibold px-2 py-1 rounded-full',
                                'bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300' => $subscription->stripe_status === 'active',
                                'bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300' => $subscription->stripe_status === 'trialing',
                                'bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300' => $subscription->stripe_status === 'canceled',
                                'bg-stone-100 dark:bg-gray-700 text-stone-800 dark:text-gray-300' => !in_array($subscription->stripe_status, ['active', 'trialing', 'canceled']),
                            ])>
                                {{ $subscription->stripe_status }}
                            </span>
                        </td>
                        <td class="p-4 text-slate-800 dark:text-gray-300">
                            {{ $subscription->created_at->format('d/m/Y') }}
                        </td>
                        <td class="p-4 text-slate-800 dark:text-gray-300">
                            {{ $subscription->dynamic_ends_at ? $subscription->dynamic_ends_at->format('d/m/Y') : 'N/A' }}
                        </td>
                        <td class="p-4 text-slate-800 dark:text-gray-300 font-semibold">
                            ${{ number_format($subscription->amount_paid, 2) }}
                        </td>
                        <td class="p-4">
                            @if ($subscription->active() && !$subscription->canceled())
                                <button wire:click="$dispatch('showConfirmationModal', {
                                    title: 'Confirmar Cancelación',
                                    message: '¿Estás seguro que quieres cancelar la suscripción de {{ $subscription->user->name }}?',
                                    confirmAction: 'cancelSubscription',
                                    params: [{{ $subscription->id }}]
                                })" class="text-red-500 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 font-semibold">
                                    Cancelar
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="p-4 text-center text-gray-500 dark:text-gray-400">
                            No se encontraron suscripciones.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $subscriptions->links() }}
    </div>
</div>
