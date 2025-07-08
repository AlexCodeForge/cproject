<x-app-layout>
    <div class="p-4 sm:p-8 main-content-scrollable">
        <div class="max-w-6xl mx-auto">
            <!-- Profile Header -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm p-8 mb-8">
                <div class="flex flex-col sm:flex-row items-center gap-8">
                    <div class="flex-shrink-0 relative">
                        <img src="{{ auth()->user()->profile->avatar_url ?? 'https://randomuser.me/api/portraits/women/11.jpg' }}" class="w-32 h-32 rounded-full border-4 border-slate-200 dark:border-gray-600" alt="User Avatar">
                        <button class="absolute bottom-2 right-2 bg-slate-700 dark:bg-gray-600 text-white p-2 rounded-full hover:bg-slate-800 dark:hover:bg-gray-500 transition-colors">
                            <ion-icon name="camera" class="text-sm"></ion-icon>
                        </button>
                    </div>
                    <div class="flex-grow text-center sm:text-left">
                        <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 dark:text-white">{{ auth()->user()->name }}</h1>
                        <p class="text-slate-500 dark:text-gray-400 mt-1">{{ auth()->user()->email }}</p>
                        <div class="mt-4 flex justify-center sm:justify-start gap-4 flex-wrap">
                            @if(auth()->user()->subscribed('default'))
                                <span class="bg-amber-100 dark:bg-amber-900/50 text-amber-800 dark:text-amber-300 text-xs font-semibold px-3 py-1 rounded-full flex items-center gap-1">
                                    <ion-icon name="diamond"></ion-icon>Premium
                                </span>
                            @endif
                            <span class="bg-stone-100 dark:bg-gray-700 text-stone-800 dark:text-gray-300 text-xs font-semibold px-3 py-1 rounded-full">Member since: {{ auth()->user()->created_at->format('M Y') }}</span>
                             @if(auth()->user()->hasVerifiedEmail())
                                <span class="bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300 text-xs font-semibold px-3 py-1 rounded-full flex items-center gap-1">
                                    <ion-icon name="checkmark-circle"></ion-icon>Verified
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="flex-shrink-0 flex gap-3">
                        <button class="bg-slate-700 dark:bg-gray-600 text-white font-semibold py-2 px-6 rounded-lg hover:bg-slate-800 dark:hover:bg-gray-500 transition-colors flex items-center gap-2">
                            <ion-icon name="create-outline"></ion-icon>
                            Edit Profile
                        </button>
                    </div>
                </div>
            </div>

            <!-- Profile Content Grid -->
            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Left Column - Account Settings -->
                <div class="lg:col-span-1 space-y-6">
                    {{--
                    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <div class="max-w-xl">
                            <livewire:profile.update-profile-information-form />
                        </div>
                    </div>

                    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <div class="max-w-xl">
                            <livewire:profile.update-password-form />
                        </div>
                    </div>
                    --}}

                    <!-- Subscription Management -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm p-6">
                         <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                            <ion-icon name="card-outline"></ion-icon>
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
                                <ion-icon name="diamond-outline"></ion-icon>
                                View Pricing Plans
                            </a>
                        @endif
                    </div>

                     <!-- Delete Account -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm p-6">
                         <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
                            <ion-icon name="trash-outline"></ion-icon>
                            Delete Account
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.
                        </p>
                        <div class="mt-6">
                             <livewire:profile.delete-user-form />
                        </div>
                    </div>

                </div>

                <!-- Right Column - Billing & Stats -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Billing Information -->
                    @if(auth()->user()->subscribed('default'))
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-stone-200 dark:border-gray-700 shadow-sm p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-slate-900 dark:text-white flex items-center gap-2">
                                <ion-icon name="card-outline"></ion-icon>
                                Billing Information
                            </h3>
                            {{-- <a href="#" class="text-blue-600 dark:text-blue-400 text-sm font-medium hover:underline">Billing Portal</a> --}}
                        </div>

                        <!-- Current Plan -->
                        <div class="bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 rounded-lg p-4 mb-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-semibold text-slate-900 dark:text-white flex items-center gap-2">
                                        <ion-icon name="diamond" class="text-amber-600 dark:text-amber-400"></ion-icon>
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
                            <ion-icon name="stats-chart-outline"></ion-icon>
                            Account Statistics
                        </h3>
                        <div class="grid grid-cols-2 gap-6">
                            <div class="text-center">
                                <div class="bg-blue-100 dark:bg-blue-900/50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <ion-icon name="chatbubbles-outline" class="text-blue-600 dark:text-blue-400 text-2xl"></ion-icon>
                                </div>
                                <p class="text-2xl font-bold text-slate-900 dark:text-white">127</p>
                                <p class="text-slate-500 dark:text-gray-400 text-sm">Messages Sent</p>
                            </div>
                            <div class="text-center">
                                <div class="bg-green-100 dark:bg-green-900/50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <ion-icon name="trending-up-outline" class="text-green-600 dark:text-green-400 text-2xl"></ion-icon>
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
</x-app-layout>
