<x-filament-panels::page>
    @php
        $settings = \App\Models\Setting::first();
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- General Settings Card -->
        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center gap-2">
                    <x-heroicon-o-cog class="w-5 h-5 text-primary-500" />
                    <span>General Settings</span>
                </div>
            </x-slot>

            <div class="space-y-4">
                <!-- Logo -->
                @if($settings->logo_path)
                    <div class="flex justify-center p-4">
                        <img src="{{ Storage::url($settings->logo_path) }}" alt="Logo" class="h-20 object-contain">
                    </div>
                @endif

                <!-- Settings List -->
                <dl class="grid grid-cols-1 gap-4">
                    <!-- App Name -->
                    <div class="flex justify-between py-3 border-b">
                        <dt class="text-sm font-medium text-gray-500">App Name</dt>
                        <dd class="text-sm text-gray-900">{{ $settings->app_name ?: 'Not Set' }}</dd>
                    </div>

                    <!-- Language -->
                    <div class="flex justify-between py-3 border-b">
                        <dt class="text-sm font-medium text-gray-500">Language</dt>
                        <dd class="text-sm text-gray-900">English</dd>
                    </div>

                    <!-- Timezone -->
                    <div class="flex justify-between py-3 border-b">
                        <dt class="text-sm font-medium text-gray-500">Timezone</dt>
                        <dd class="text-sm text-gray-900">Africa/Accra (GMT)</dd>
                    </div>

                    <!-- Currency -->
                    <div class="flex justify-between py-3">
                        <dt class="text-sm font-medium text-gray-500">Currency</dt>
                        <dd class="text-sm text-gray-900">Ghana Cedis (GHS)</dd>
                    </div>
                </dl>
            </div>
        </x-filament::section>

        <!-- Notification Settings Card -->
        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center gap-2">
                    <x-heroicon-o-bell class="w-5 h-5 text-primary-500" />
                    <span>Notification Settings</span>
                </div>
            </x-slot>

            <div class="space-y-6">
                <!-- Notification Toggles -->
                <div class="grid grid-cols-2 gap-6">
                    <!-- Email Notifications -->
                    <div class="bg-white p-4 rounded-lg border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <x-heroicon-o-envelope class="w-5 h-5 text-gray-400" />
                                <span class="text-sm font-medium text-gray-900">Email Notifications</span>
                            </div>
                            <span @class([
                                'px-2 py-1 text-xs rounded-full',
                                'bg-success-50 text-success-700' => $settings->email_notifications_enabled,
                                'bg-danger-50 text-danger-700' => !$settings->email_notifications_enabled,
                            ])>
                                {{ $settings->email_notifications_enabled ? 'Enabled' : 'Disabled' }}
                            </span>
                        </div>
                        @if($settings->default_email_sender)
                            <div class="mt-3 pt-3 border-t border-gray-100">
                                <span class="text-xs text-gray-500">Default Sender</span>
                                <p class="mt-1 text-sm text-gray-700">{{ $settings->default_email_sender }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- SMS Notifications -->
                    <div class="bg-white p-4 rounded-lg border border-gray-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <x-heroicon-o-device-phone-mobile class="w-5 h-5 text-gray-400" />
                                <span class="text-sm font-medium text-gray-900">SMS Notifications</span>
                            </div>
                            <span @class([
                                'px-2 py-1 text-xs rounded-full',
                                'bg-success-50 text-success-700' => $settings->sms_notifications_enabled,
                                'bg-danger-50 text-danger-700' => !$settings->sms_notifications_enabled,
                            ])>
                                {{ $settings->sms_notifications_enabled ? 'Enabled' : 'Disabled' }}
                            </span>
                        </div>
                        @if($settings->default_sms_sender)
                            <div class="mt-3 pt-3 border-t border-gray-100">
                                <span class="text-xs text-gray-500">Default Sender</span>
                                <p class="mt-1 text-sm text-gray-700">{{ $settings->default_sms_sender }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Twilio Configuration -->
                @if($settings->twilio_account_sid || $settings->twilio_phone_number)
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h4 class="text-sm font-medium text-gray-900 mb-4">Twilio Configuration</h4>
                        <div class="grid grid-cols-2 gap-4">
                            @if($settings->twilio_account_sid)
                                <div>
                                    <span class="text-xs font-medium text-gray-500">Account SID</span>
                                    <p class="mt-1 text-sm text-gray-900">•••••••••{{ substr($settings->twilio_account_sid, -4) }}</p>
                                </div>
                            @endif
                            @if($settings->twilio_phone_number)
                                <div>
                                    <span class="text-xs font-medium text-gray-500">Phone Number</span>
                                    <p class="mt-1 text-sm text-gray-900">{{ $settings->twilio_phone_number }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </x-filament::section>

        <!-- Delivery Settings Card -->
        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center gap-2">
                    <x-heroicon-o-truck class="w-5 h-5 text-primary-500" />
                    <span>Delivery Settings</span>
                </div>
            </x-slot>

            <div class="space-y-6">
                @if($settings->default_delivery_time || $settings->maximum_parcel_weight || $settings->allowed_delivery_areas)
                    <div class="grid grid-cols-2 gap-4">
                        @if($settings->default_delivery_time)
                            <div>
                                <span class="text-sm font-medium text-gray-500">Default Delivery Time</span>
                                <p class="mt-1 text-sm">{{ $settings->default_delivery_time }} hours</p>
                            </div>
                        @endif

                        @if($settings->maximum_parcel_weight)
                            <div>
                                <span class="text-sm font-medium text-gray-500">Maximum Parcel Weight</span>
                                <p class="mt-1 text-sm">{{ $settings->maximum_parcel_weight }} kg</p>
                            </div>
                        @endif
                    </div>

                    @if($settings->allowed_delivery_areas)
                        <div>
                            <span class="text-sm font-medium text-gray-500">Allowed Delivery Areas</span>
                            <div class="mt-2 flex flex-wrap gap-2">
                                @foreach($settings->allowed_delivery_areas as $area)
                                    <span class="inline-flex items-center px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded-full">
                                        {{ $area }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @else
                    <div class="text-center py-4">
                        <x-heroicon-o-information-circle class="h-8 w-8 text-gray-400 mx-auto" />
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No Information Available</h3>
                        <p class="mt-1 text-sm text-gray-500">No delivery settings have been configured yet.</p>
                    </div>
                @endif
            </div>
        </x-filament::section>

        <!-- Payment Settings Card -->
        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center gap-2">
                    <x-heroicon-o-credit-card class="w-5 h-5 text-primary-500" />
                    <span>Payment Settings</span>
                </div>
            </x-slot>

            <div class="space-y-6">
                <div class="grid grid-cols-2 gap-4">
                    @if($settings->payment_gateway)
                        <div>
                            <span class="text-sm font-medium text-gray-500">Payment Gateway</span>
                            <p class="mt-1 text-sm">{{ ucfirst($settings->payment_gateway) }}</p>
                        </div>
                    @endif

                    @if($settings->tax_rate)
                        <div>
                            <span class="text-sm font-medium text-gray-500">Tax Rate</span>
                            <p class="mt-1 text-sm">{{ $settings->tax_rate }}%</p>
                        </div>
                    @endif
                </div>

                @if($settings->refund_policy_link)
                    <div>
                        <span class="text-sm font-medium text-gray-500">Refund Policy</span>
                        <p class="mt-1">
                            <a href="{{ $settings->refund_policy_link }}" target="_blank" class="text-sm text-primary-600 hover:text-primary-500">
                                View Policy
                            </a>
                        </p>
                    </div>
                @endif
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
