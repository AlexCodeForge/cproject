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
        <!-- Profile Header -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm p-8 mb-8">
            <div class="flex flex-col sm:flex-row items-center gap-8">
                <div class="flex-shrink-0 relative">
                    <img src="{{ auth()->user()->profile?->avatar_url ?? auth()->user()->avatar_url }}" class="w-32 h-32 rounded-full border-4 border-slate-200 dark:border-gray-600" alt="User Avatar">
                    <button class="absolute bottom-2 right-2 bg-slate-700 dark:bg-gray-600 text-white p-2 rounded-full hover:bg-slate-800 dark:hover:bg-gray-500 transition-colors">
                        <x-ionicon-camera class="w-6 h-6"></x-ionicon-camera>
                    </button>
                </div>
                <div class="flex-grow text-center sm:text-left">
                    <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 dark:text-white">{{ auth()->user()->name }}</h1>
                    <p class="text-slate-500 dark:text-gray-400 mt-1">{{ auth()->user()->email }}</p>
                    <div class="mt-4 flex justify-center sm:justify-start gap-4 flex-wrap">
                        @if(auth()->user()->subscribed('default'))
                            <span class="bg-amber-100 dark:bg-amber-900/50 text-amber-800 dark:text-amber-300 text-xs font-semibold px-3 py-1 rounded-full flex items-center gap-1">
                                <x-ionicon-diamond class="w-6 h-6"></x-ionicon-diamond>Premium
                            </span>
                        @endif
                        <span class="bg-stone-100 dark:bg-gray-700 text-stone-800 dark:text-gray-300 text-xs font-semibold px-3 py-1 rounded-full">Member since: {{ auth()->user()->created_at->format('M Y') }}</span>
                         @if(auth()->user()->hasVerifiedEmail())
                            <span class="bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300 text-xs font-semibold px-3 py-1 rounded-full flex items-center gap-1">
                                <x-ionicon-checkmark-circle class="w-6 h-6"></x-ionicon-checkmark-circle>Verified
                            </span>
                        @endif
                    </div>
                </div>
                <div class="flex-shrink-0 flex gap-3">

                </div>
            </div>
        </div>

        <!-- Profile Content Grid -->
        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Left Column - Account Settings -->
            <div class="lg:col-span-2 space-y-6">

                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm">
                    <div>
                        <livewire:user-panel.profile.update-profile-information-form />
                    </div>
                </div>

                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm">
                    <div class="max-w-xl">
                        <livewire:profile.update_password_form />
                    </div>
                </div>


                <!-- Subscription Management -->
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm p-6">
                     <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                        <x-ionicon-card-outline class="w-6 h-6"></x-ionicon-card-outline>
                        Subscription
                    </h3>
                    @if(auth()->user()->subscribed('default'))
                        <p class="text-sm text-gray-600 dark:text-gray-400">You are subscribed to the Premium plan.</p>
                        <form method="POST" action="{{ route('billing.cancel') }}" class="mt-6">
                            @csrf
                            <x-danger-button>
                                {{ __('Cancel Subscription') }}
                            </x-danger-button>
                        </form>
                    @else
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            You are not subscribed to any plan.
                        </p>
                        <a href="{{ route('pricing') }}" class="w-full mt-4 text-center p-3 rounded-lg bg-amber-500 hover:bg-amber-600 text-white font-bold transition-colors flex items-center justify-center gap-2">
                            <x-ionicon-diamond-outline class="w-6 h-6"></x-ionicon-diamond-outline>
                            View Pricing Plans
                        </a>
                    @endif
                </div>

                 <!-- Delete Account -->
                {{-- <div class="bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm p-6">
                     <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                        <x-ionicon-trash-outline class="w-6 h-6"></x-ionicon-trash-outline>
                        Delete Account
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.
                    </p>
                    <div class="mt-6">
                         <livewire:profile.delete_user_form />
                    </div>
                </div> --}}

            </div>

            <!-- Right Column - Billing & Stats -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Billing Information -->
                @if(auth()->user()->subscribed('default'))
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white flex items-center gap-2">
                            <x-ionicon-card-outline class="w-6 h-6"></x-ionicon-card-outline>
                            Billing Information
                        </h3>
                        {{-- <a href="#" class="text-blue-600 dark:text-blue-400 text-sm font-medium hover:underline">Billing Portal</a> --}}
                    </div>

                    <!-- Current Plan -->
                    <div class="bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 rounded-lg p-4 mb-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="font-semibold text-slate-900 dark:text-white flex items-center gap-2">
                                    <x-ionicon-diamond class="w-6 h-6 text-amber-600 dark:text-amber-400"></x-ionicon-diamond>
                                    Premium Plan
                                </h4>
                                {{-- <p class="text-slate-600 dark:text-gray-400 text-sm">Next payment: ...</p> --}}
                            </div>
                            <div class="text-right">
                                {{-- <p class="text-2xl font-bold text-slate-900 dark:text-white">$29</p>
                                <p class="text-slate-500 dark:text-gray-400 text-sm">per month</p> --}}
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Account Statistics -->
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-6 flex items-center gap-2">
                        <x-ionicon-stats-chart-outline class="w-6 h-6"></x-ionicon-stats-chart-outline>
                        Account Statistics
                    </h3>
                    <div class="grid grid-cols-2 gap-6">
                        <div class="text-center">
                            <div class="bg-blue-100 dark:bg-blue-900/50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-3">
                                <x-ionicon-chatbubbles-outline class="w-6 h-6 text-blue-600 dark:text-blue-400 text-2xl"></x-ionicon-chatbubbles-outline>
                            </div>
                            <p class="text-2xl font-bold text-slate-900 dark:text-white">127</p>
                            <p class="text-slate-500 dark:text-gray-400 text-sm">Messages Sent</p>
                        </div>
                        <div class="text-center">
                            <div class="bg-green-100 dark:bg-green-900/50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-3">
                                <x-ionicon-trending-up-outline class="w-6 h-6 text-green-600 dark:text-green-400 text-2xl"></x-ionicon-trending-up-outline>
                            </div>
                            <p class="text-2xl font-bold text-slate-900 dark:text-white">89%</p>
                            <p class="text-slate-500 dark:text-gray-400 text-sm">Successful Trades</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
