<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;

new #[Layout('layouts.app')] class extends Component
{
    #[On('profile-updated')]
    public function refreshPage()
    {
        $this->js('window.location.reload()');
    }
}; ?>

<div class="p-4 sm:p-8 main-content-scrollable">
    <div class="max-w-6xl mx-auto">
        <!-- Encabezado del Perfil -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm p-8 mb-8">
            <div class="flex flex-col sm:flex-row items-center gap-8">
                <div class="flex-shrink-0 relative">
                    <img src="{{ auth()->user()->profile?->avatar_url ?? auth()->user()->avatar_url }}" class="w-32 h-32 rounded-full border-4 border-slate-200 dark:border-gray-600" alt="Avatar de Usuario">
                </div>
                <div class="flex-grow text-center sm:text-left">
                    <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 dark:text-white">{{ auth()->user()->name }}</h1>
                    <p class="text-slate-500 dark:text-gray-400 mt-1">{{ auth()->user()->email }}</p>
                    <div class="mt-4 flex justify-center sm:justify-start gap-4 flex-wrap">
                        @if(auth()->user()->subscribed('default'))
                            <span class="bg-amber-100 dark:bg-amber-900/50 text-amber-800 dark:text-amber-300 text-xs font-semibold px-3 py-1 rounded-full flex items-center gap-1">
                                <x-ionicon-rocket-outline class="w-6 h-6"></x-ionicon-rocket-outline>Premium
                            </span>
                        @endif
                        <span class="bg-stone-100 dark:bg-gray-700 text-stone-800 dark:text-gray-300 text-xs font-semibold px-3 py-1 rounded-full">Miembro desde: {{ auth()->user()->created_at->format('d F, Y') }}</span>
                         @if(auth()->user()->hasVerifiedEmail())
                            <span class="bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300 text-xs font-semibold px-3 py-1 rounded-full flex items-center gap-1">
                                <x-ionicon-checkmark-circle class="w-6 h-6"></x-ionicon-checkmark-circle>Verificado
                            </span>
                        @endif
                    </div>
                </div>
                <div class="flex-shrink-0 flex gap-3">

                </div>
            </div>
        </div>

        <!-- Contenido del Perfil -->
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Columna Izquierda - Ajustes de Cuenta -->
            <div class="lg:col-span-2 space-y-6">

                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm">
                    <div>
                        <livewire:user-panel.profile.update-profile-information-form />
                    </div>
                </div>

                <!-- Gestión de Suscripciones -->
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm p-6">
                     <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                        <x-ionicon-card-outline class="w-6 h-6"></x-ionicon-card-outline>
                        Suscripción
                    </h3>
                    @if(auth()->user()->subscribed('default'))
                        <p class="text-sm text-gray-600 dark:text-gray-400">Estás suscrito al plan Premium.</p>

                        @if (auth()->user()->subscription('default')->onGracePeriod())
                            <p class="mt-2 text-sm text-amber-600 dark:text-amber-400">
                                Tu suscripción ha sido cancelada y expirará el: <strong>{{ auth()->user()->subscription_ends_at->format('d F, Y') }}</strong>.
                            </p>
                        @else
                            @if (auth()->user()->subscription_ends_at)
                                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                    Tu plan se renueva el: <strong>{{ auth()->user()->subscription_ends_at->format('d F, Y') }}</strong>.
                                </p>
                            @endif
                        <form id="cancel-subscription-form" method="POST" action="{{ route('billing.cancel') }}" class="mt-6">
                            @csrf
                            <x-danger-button type="button" x-data=""
                                x-on:click.prevent="$dispatch('showConfirmationModal', {
                                    title: 'Confirmar Cancelación',
                                    message: '¿Estás seguro de que quieres cancelar tu suscripción? Esta acción es irreversible.',
                                    confirmAction: 'confirm-cancel-subscription'
                                })">
                                {{ __('Cancelar Suscripción') }}
                            </x-danger-button>
                        </form>
                        @endif
                    @else
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            No estás suscrito a ningún plan.
                        </p>
                        <a href="{{ route('pricing') }}" class="w-full mt-4 text-center p-3 rounded-lg bg-amber-500 hover:bg-amber-600 text-white font-bold transition-colors flex items-center justify-center gap-2">
                            <x-ionicon-rocket-outline class="w-6 h-6"></x-ionicon-rocket-outline>
                            Ver Planes de Precios
                        </a>
                    @endif
                </div>


            </div>

            <!-- Columna Derecha - Facturación y Estadísticas -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Información de Facturación -->
                @if(auth()->user()->subscribed('default'))
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white flex items-center gap-2">
                            <x-ionicon-card-outline class="w-6 h-6"></x-ionicon-card-outline>
                            Información de Facturación
                        </h3>

                    </div>

                    <!-- Plan Actual -->
                    <div class="bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 rounded-lg p-4 mb-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="font-semibold text-slate-900 dark:text-white flex items-center gap-2">
                                    <x-ionicon-rocket-outline class="w-6 h-6 text-amber-600 dark:text-amber-400"></x-ionicon-rocket-outline>
                                    Plan Premium
                                </h4>
                                @if (auth()->user()->subscription_ends_at)
                                    @if (auth()->user()->subscription('default')->onGracePeriod())
                                        <p class="text-slate-600 dark:text-gray-400 text-sm">Expira el: {{ auth()->user()->subscription_ends_at->format('d F, Y') }}</p>
                                    @else
                                        <p class="text-slate-600 dark:text-gray-400 text-sm">Se renueva el: {{ auth()->user()->subscription_ends_at->format('d F, Y') }}</p>
                                    @endif
                                @endif
                            </div>
                            <div class="text-right">

                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <script>
                    document.addEventListener('livewire:initialized', () => {
                        Livewire.on('confirm-cancel-subscription', () => {
                            document.getElementById('cancel-subscription-form').submit();
                        });
                    });
                </script>

                <!-- Estadísticas de Cuenta -->
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-6 flex items-center gap-2">
                        <x-ionicon-stats-chart-outline class="w-6 h-6"></x-ionicon-stats-chart-outline>
                        Estadísticas de Cuenta
                    </h3>
                    <div class="grid grid-cols-2 gap-6">
                        <div class="text-center">
                            <div class="bg-blue-100 dark:bg-blue-900/50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-3">
                                <x-ionicon-chatbubbles-outline class="w-6 h-6 text-blue-600 dark:text-blue-400 text-2xl"></x-ionicon-chatbubbles-outline>
                            </div>
                            <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ auth()->user()->sent_messages_count }}</p>
                            <p class="text-slate-500 dark:text-gray-400 text-sm">Mensajes Enviados</p>
                        </div>
                        <div class="text-center">
                            <div class="bg-orange-100 dark:bg-orange-900/50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-3">
                                <x-ionicon-notifications-outline class="w-6 h-6 text-orange-600 dark:text-orange-400 text-2xl"></x-ionicon-notifications-outline>
                            </div>
                            <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ auth()->user()->pending_notifications_count }}</p>
                            <p class="text-slate-500 dark:text-gray-400 text-sm">Notificaciones Pendientes</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
