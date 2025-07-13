<?php

use function Livewire\Volt\layout;

layout('layouts.app');

?>

<div>
    <section id="premium" class="section active">
        <div class="max-w-7xl mx-auto">
            <!-- Premium Header with enhanced styling -->
            <div class="text-center mb-12 relative">
                @if (session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">Error!</strong>
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif
                <!-- Decorative background elements -->
                <div class="absolute -top-20 left-1/2 transform -translate-x-1/2 w-96 h-96 bg-gradient-to-r from-amber-200/30 to-orange-200/30 dark:from-amber-600/10 dark:to-orange-600/10 rounded-full blur-3xl"></div>

                <div class="relative z-10">
                    <h1 class="text-4xl sm:text-5xl font-extrabold text-slate-900 dark:text-white mb-4">
                        Lanza tu Trading a la
                        <span class="bg-gradient-to-r from-amber-600 to-orange-600 bg-clip-text text-transparent">Estratosfera</span>.
                    </h1>
                    <p class="text-lg text-slate-600 dark:text-gray-300 max-w-2xl mx-auto">
                        Conviértete en miembro de OptionRocket Premium y accede a análisis exclusivos, señales de trading y herramientas profesionales.
                    </p>
                </div>
            </div>

            <!-- Enhanced Billing Toggle -->
            <div class="flex justify-center items-center space-x-4 mb-10">
                <span class="font-medium text-slate-700 dark:text-gray-300">Mensual</span>
                <label for="billing-toggle" class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="billing-toggle" class="sr-only peer">
                    <div class="w-14 h-8 bg-stone-200 dark:bg-gray-600 rounded-full peer peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-amber-300/50 dark:peer-focus:ring-amber-600/50 peer-checked:bg-gradient-to-r peer-checked:from-amber-500 peer-checked:to-orange-500 transition-all duration-300"></div>
                    <div class="absolute top-1 left-1 bg-white dark:bg-gray-200 border-stone-300 dark:border-gray-500 border rounded-full h-6 w-6 transition-transform peer-checked:translate-x-6 shadow-md"></div>
                </label>
                <span class="font-medium text-slate-700 dark:text-gray-300">
                    Anual
                    <span class="text-green-600 dark:text-green-400 font-semibold">(Ahorra 20%)</span>
                </span>
            </div>

            <!-- Enhanced Premium Cards -->
            <div class="grid lg:grid-cols-2 gap-8">
                <!-- Regular Plan Card -->
                <div class="bg-white dark:bg-gray-800 p-8 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-all duration-200">
                    <h3 class="text-2xl font-bold text-slate-900 dark:text-white">Regular (Gratis)</h3>
                    <p class="text-slate-600 dark:text-gray-400 mt-2">Para empezar a explorar.</p>
                    <ul class="space-y-4 mt-6 text-slate-700 dark:text-gray-300">
                        <li class="flex items-center gap-3">
                            <ion-icon name="checkmark-circle" class="text-green-500 text-2xl"></ion-icon>
                            <span>Acceso a Foros Públicos</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <ion-icon name="checkmark-circle" class="text-green-500 text-2xl"></ion-icon>
                            <span>Chat General</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <ion-icon name="close-circle" class="text-red-400 text-2xl"></ion-icon>
                            <span>Alertas y Señales de Trading</span>
                        </li>
                    </ul>
                </div>

                <!-- Premium Plan Card with enhanced styling -->
                <div class="relative bg-gradient-to-br from-amber-50 via-orange-50 to-amber-100 dark:from-amber-900/20 dark:via-amber-800/30 dark:to-orange-900/20 p-8 rounded-xl border-2 border-amber-400/50 dark:border-amber-600/50 shadow-lg hover:shadow-xl transition-all duration-300">
                    <!-- Decorative background elements -->
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-amber-300/20 dark:bg-amber-600/10 rounded-full blur-2xl"></div>
                    <div class="absolute -bottom-5 -left-5 w-24 h-24 bg-orange-300/20 dark:bg-orange-600/10 rounded-full blur-xl"></div>

                    <!-- Premium Badge -->
                    <div class="absolute -top-4 right-8 bg-gradient-to-r from-amber-500 to-orange-500 text-white text-xs font-bold px-4 py-1 rounded-full shadow-lg flex items-center gap-1 z-10">
                        <ion-icon name="diamond" class="text-xs"></ion-icon>
                        MÁS POPULAR
                    </div>

                    <div class="relative z-10">
                        <div class="flex items-center gap-2 mb-2">
                            <h3 class="text-2xl font-bold text-slate-900 dark:text-white">Premium</h3>
                            <ion-icon name="diamond" class="text-amber-600 dark:text-amber-400 text-lg"></ion-icon>
                        </div>
                        <p class="text-slate-600 dark:text-gray-300 mt-2">Desbloquea todo el potencial.</p>

                        <p class="my-6">
                            <span id="price-display" class="text-5xl font-extrabold text-slate-900 dark:text-white">$28</span>
                            <span id="price-term" class="text-slate-500 dark:text-gray-400"> / mes</span>
                        </p>

                        <form action="{{ route('checkout') }}" method="POST">
                            @csrf
                            <input type="hidden" name="plan_slug" value="premium">
                            <input type="hidden" name="plan" id="plan-input" value="monthly">
                            <button type="submit" class="w-full bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-bold py-3 rounded-lg shadow-lg hover:shadow-xl transition-all duration-200 transform hover:scale-[1.02]">
                                Mejorar a Premium
                            </button>
                        </form>

                        <ul class="space-y-4 mt-6 text-slate-700 dark:text-gray-300">
                            <li class="flex items-center gap-3">
                                <ion-icon name="checkmark-circle" class="text-green-500 text-2xl"></ion-icon>
                                <span>Todo lo del plan Regular, y además:</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <ion-icon name="checkmark-circle" class="text-green-500 text-2xl"></ion-icon>
                                <span>Alertas y Señales Premium</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <ion-icon name="checkmark-circle" class="text-green-500 text-2xl"></ion-icon>
                                <span>Acceso a Canales de Chat Exclusivos</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <ion-icon name="checkmark-circle" class="text-green-500 text-2xl"></ion-icon>
                                <span>Sesiones en Vivo y Grabaciones</span>
                            </li>
                            <li class="flex items-center gap-3">
                                <ion-icon name="checkmark-circle" class="text-green-500 text-2xl"></ion-icon>
                                <span>Todos los Cursos y Análisis</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </section>
</div>


@push('scripts')
<script>
    document.addEventListener('livewire:navigated', () => {
        const billingToggle = document.getElementById('billing-toggle');
        const priceDisplay = document.getElementById('price-display');
        const priceTerm = document.getElementById('price-term');
        const planInput = document.getElementById('plan-input');

        const updatePlan = () => {
            // Logic is inverted to match toggle behavior
            if (billingToggle.checked) {
                // Yearly Plan
                priceDisplay.textContent = '$280';
                priceTerm.textContent = ' / año';
                planInput.value = 'yearly';
            } else {
                // Monthly Plan
                priceDisplay.textContent = '$28';
                priceTerm.textContent = ' / mes';
                planInput.value = 'monthly';
            }
        };

        if (billingToggle) {
            updatePlan();
            billingToggle.addEventListener('change', updatePlan);
        }
    });
</script>
@endpush
