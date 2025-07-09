@extends('layouts.portal')

@section('title', __('settings.title'))

@section('content')
<div class="max-w-7xl mx-auto" x-data="settingsForm()">
    {{-- Page Header --}}
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">
            {{ __('settings.title') }}
        </h1>
        <p class="mt-2 text-gray-600 dark:text-gray-400">
            {{ __('settings.subtitle') }}
        </p>
    </div>

    {{-- Tab Navigation - Atlassian Style --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
        {{-- Desktop Tabs --}}
        <div class="hidden sm:block border-b border-gray-200 dark:border-gray-700">
            <nav class="-mb-px flex" aria-label="Tabs">
                <button @click="activeTab = 'general'" 
                        :class="activeTab === 'general' 
                            ? 'border-blue-500 text-blue-600 dark:text-blue-400' 
                            : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors duration-150 focus:outline-none">
                    <i class="fa-solid fa-circle-info mr-2"></i>
                    {{ __('settings.tabs.general') }}
                </button>
                
                <button @click="activeTab = 'contact'" 
                        :class="activeTab === 'contact' 
                            ? 'border-blue-500 text-blue-600 dark:text-blue-400' 
                            : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors duration-150 focus:outline-none">
                    <i class="fa-solid fa-address-book mr-2"></i>
                    {{ __('settings.tabs.contact') }}
                </button>
                
                <button @click="activeTab = 'localization'" 
                        :class="activeTab === 'localization' 
                            ? 'border-blue-500 text-blue-600 dark:text-blue-400' 
                            : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors duration-150 focus:outline-none">
                    <i class="fa-solid fa-globe mr-2"></i>
                    {{ __('settings.tabs.localization') }}
                </button>
                
                <button @click="activeTab = 'subscription'" 
                        :class="activeTab === 'subscription' 
                            ? 'border-blue-500 text-blue-600 dark:text-blue-400' 
                            : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors duration-150 focus:outline-none">
                    <i class="fa-solid fa-crown mr-2"></i>
                    {{ __('settings.tabs.subscription') }}
                </button>
            </nav>
        </div>

        {{-- Mobile Tab Selector --}}
        <div class="sm:hidden p-4 border-b border-gray-200 dark:border-gray-700">
            <label for="mobile-tabs" class="sr-only">{{ __('settings.select_tab') }}</label>
            <select id="mobile-tabs" 
                    x-model="activeTab"
                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 py-2 pl-3 pr-10 text-base focus:border-blue-500 focus:outline-none focus:ring-blue-500 sm:text-sm dark:bg-gray-700 dark:text-white">
                <option value="general">{{ __('settings.tabs.general') }}</option>
                <option value="contact">{{ __('settings.tabs.contact') }}</option>
                <option value="localization">{{ __('settings.tabs.localization') }}</option>
                <option value="subscription">{{ __('settings.tabs.subscription') }}</option>
            </select>
        </div>

        {{-- Tab Content --}}
        <form @submit.prevent="submitForm" class="p-6">
            @csrf
            @method('PUT')

            {{-- General Tab --}}
            <div x-show="activeTab === 'general'" x-cloak>
                <div class="space-y-6">
                    {{-- Organization Information Section --}}
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                            {{ __('settings.sections.organization_info') }}
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Organization Name --}}
                            <div>
                                <x-form.input 
                                    name="name"
                                    :label="__('settings.fields.organization_name')"
                                    :value="$tenant->name"
                                    :placeholder="__('settings.placeholders.organization_name')"
                                    :hint="__('settings.hints.organization_name')"
                                    x-model="form.name"
                                    required />
                            </div>

                            {{-- Legal Name --}}
                            <div>
                                <x-form.input 
                                    name="legal_name"
                                    :label="__('settings.fields.legal_name')"
                                    :value="$tenant->legal_name"
                                    :placeholder="__('settings.placeholders.legal_name')"
                                    :hint="__('settings.hints.legal_name')"
                                    x-model="form.legal_name" />
                            </div>

                            {{-- Organization Code --}}
                            <div>
                                <x-form.input 
                                    name="code"
                                    :label="__('settings.fields.organization_code')"
                                    :value="$tenant->code"
                                    :hint="__('settings.hints.organization_code')"
                                    disabled />
                            </div>

                            {{-- Organization ID --}}
                            <div>
                                <x-form.input 
                                    name="tenant_id"
                                    :label="__('settings.fields.organization_id')"
                                    :value="$tenant->id"
                                    :hint="__('settings.hints.organization_id')"
                                    disabled />
                            </div>


                        </div>
                    </div>
                </div>
            </div>

            {{-- Contact Tab --}}
            <div x-show="activeTab === 'contact'" x-cloak>
                <div class="space-y-6">
                    {{-- Contact Information Section --}}
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                            {{ __('settings.sections.contact_info') }}
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Email --}}
                            <div>
                                <x-form.input.email
                                    name="email"
                                    :label="__('settings.fields.email')"
                                    :value="$tenant->email"
                                    :placeholder="__('settings.placeholders.email')"
                                    :hint="__('settings.hints.email')"
                                    x-model="form.email"
                                    required />
                            </div>

                            {{-- Phone --}}
                            <div>
                                <x-form.specialized.phone-input 
                                    name="phone" 
                                    :label="__('settings.fields.phone')"
                                    :value="$tenant->phone"
                                    :hint="__('settings.hints.phone')"
                                    x-model="form.phone"
                                    :countries="$countries" />
                            </div>

                            {{-- Website --}}
                            <div class="md:col-span-2">
                                <x-form.input.url
                                    name="website"
                                    :label="__('settings.fields.website')"
                                    :value="$tenant->website"
                                    :placeholder="__('settings.placeholders.website')"
                                    :hint="__('settings.hints.website')"
                                    x-model="form.website" />
                            </div>
                        </div>
                    </div>

                    {{-- Address Section --}}
                    <div class="mt-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                            {{ __('settings.sections.address') }}
                        </h3>
                        <div class="space-y-4">
                            {{-- Address Line 1 --}}
                            <div>
                                <x-form.input 
                                    name="address_line_1"
                                    :label="__('settings.fields.address_line_1')"
                                    :value="$tenant->address_line_1"
                                    :placeholder="__('settings.placeholders.address_line_1')"
                                    :hint="__('settings.hints.address_line_1')"
                                    x-model="form.address_line_1" />
                            </div>

                            {{-- Address Line 2 --}}
                            <div>
                                <x-form.input 
                                    name="address_line_2"
                                    :label="__('settings.fields.address_line_2')"
                                    :value="$tenant->address_line_2"
                                    :placeholder="__('settings.placeholders.address_line_2')"
                                    :hint="__('settings.hints.address_line_2')"
                                    x-model="form.address_line_2" />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                {{-- City --}}
                                <div>
                                    <x-form.input 
                                        name="city"
                                        :label="__('settings.fields.city')"
                                        :value="$tenant->city"
                                        :placeholder="__('settings.placeholders.city')"
                                        :hint="__('settings.hints.city')"
                                        x-model="form.city" />
                                </div>

                                {{-- State/Province --}}
                                <div>
                                    <x-form.input 
                                        name="state"
                                        :label="__('settings.fields.state')"
                                        :value="$tenant->state"
                                        :placeholder="__('settings.placeholders.state')"
                                        :hint="__('settings.hints.state')"
                                        x-model="form.state" />
                                </div>

                                {{-- Postal Code --}}
                                <div>
                                    <x-form.input 
                                        name="postal_code"
                                        :label="__('settings.fields.postal_code')"
                                        :value="$tenant->postal_code"
                                        :placeholder="__('settings.placeholders.postal_code')"
                                        :hint="__('settings.hints.postal_code')"
                                        x-model="form.postal_code" />
                                </div>

                                {{-- Country --}}
                                <div>
                                    <x-form.specialized.country-select
                                        name="country_id"
                                        :label="__('settings.fields.country')"
                                        :value="$tenant->country_id"
                                        :placeholder="__('settings.placeholders.select_country')"
                                        :hint="__('settings.hints.country')"
                                        :countries="$countries"
                                        model="form.country_id" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Localization Tab --}}
            <div x-show="activeTab === 'localization'" x-cloak>
                <div class="space-y-6">
                    {{-- Regional Settings Section --}}
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                            {{ __('settings.sections.regional_settings') }}
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Language --}}
                            <div>
                                <x-form.specialized.language-select
                                    name="language_id"
                                    :label="__('settings.fields.language')"
                                    :value="$tenant->language_id"
                                    :placeholder="__('settings.placeholders.select_language')"
                                    :hint="__('settings.hints.language')"
                                    :languages="$languages"
                                    model="form.language_id" />
                            </div>



                            {{-- Timezone --}}
                            <div>
                                <x-form.specialized.timezone-select
                                    name="timezone_id"
                                    :label="__('settings.fields.timezone')"
                                    :value="$tenant->timezone_id"
                                    :placeholder="__('settings.placeholders.select_timezone')"
                                    :hint="__('settings.hints.timezone')"
                                    :timezones="$timezones"
                                    model="form.timezone_id" />
                            </div>
                        </div>
                    </div>

                    {{-- Date & Time Formats Section --}}
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                            {{ __('settings.sections.datetime_formats') }}
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Date Format --}}
                            <div>
                                <x-form.specialized.date-format-select
                                    name="date_format"
                                    :label="__('settings.fields.date_format')"
                                    :value="$tenant->date_format"
                                    :hint="__('settings.hints.date_format')"
                                    model="form.date_format" />
                            </div>

                            {{-- Time Format --}}
                            <div>
                                <x-form.specialized.time-format-select
                                    name="time_format"
                                    :label="__('settings.fields.time_format')"
                                    :value="$tenant->time_format"
                                    :hint="__('settings.hints.time_format')"
                                    model="form.time_format" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Subscription Tab --}}
            <div x-show="activeTab === 'subscription'" x-cloak>
                <div class="space-y-6">
                    {{-- Current Plan Info --}}
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fa-solid fa-circle-info text-blue-500 dark:text-blue-400 text-xl"></i>
                            </div>
                            <div class="ml-3 flex-1">
                                <h3 class="text-lg font-medium text-blue-900 dark:text-blue-100">
                                    {{ __('settings.subscription.current_plan') }}
                                </h3>
                                <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                                    <dl class="space-y-1">
                                        <div class="flex items-center">
                                            <dt class="font-medium">{{ __('settings.subscription.plan') }}:</dt>
                                            <dd class="ml-2">{{ $tenant->getPlanName() }}</dd>
                                        </div>
                                        <div class="flex items-center">
                                            <dt class="font-medium">{{ __('settings.subscription.status') }}:</dt>
                                            <dd class="ml-2">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                                    {{ $tenant->getStatusName() }}
                                                </span>
                                            </dd>
                                        </div>
                                        @if($tenant->subscription_ends_at)
                                            <div class="flex items-center">
                                                <dt class="font-medium">{{ __('settings.subscription.expires') }}:</dt>
                                                <dd class="ml-2">@dt($tenant->subscription_ends_at)</dd>
                                            </div>
                                        @endif
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Usage Statistics --}}
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                            {{ __('settings.subscription.usage_statistics') }}
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            {{-- Users Usage --}}
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        {{ __('settings.subscription.users') }}
                                    </h4>
                                    <i class="fa-solid fa-users text-gray-400"></i>
                                </div>
                                <div class="space-y-2">
                                    <div class="flex items-baseline justify-between">
                                        <span class="text-2xl font-semibold text-gray-900 dark:text-white">
                                            {{ $tenant->current_users }}
                                        </span>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                            / {{ $tenant->max_users }}
                                        </span>
                                    </div>
                                    <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2 overflow-hidden">
                                        <div class="bg-blue-500 h-2 rounded-full transition-all duration-300" 
                                             style="width: {{ min($tenant->getUserUsagePercentage(), 100) }}%"></div>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $tenant->getUserUsagePercentage() }}% {{ __('settings.subscription.used') }}
                                    </p>
                                </div>
                            </div>

                            {{-- Storage Usage --}}
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        {{ __('settings.subscription.storage') }}
                                    </h4>
                                    <i class="fa-solid fa-hard-drive text-gray-400"></i>
                                </div>
                                <div class="space-y-2">
                                    <div class="flex items-baseline justify-between">
                                        <span class="text-2xl font-semibold text-gray-900 dark:text-white">
                                            {{ number_format($tenant->current_storage_mb / 1024, 1) }}
                                        </span>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                            / {{ number_format($tenant->max_storage_mb / 1024, 1) }} GB
                                        </span>
                                    </div>
                                    <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2 overflow-hidden">
                                        <div class="bg-green-500 h-2 rounded-full transition-all duration-300" 
                                             style="width: {{ min($tenant->getStorageUsagePercentage(), 100) }}%"></div>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $tenant->getStorageUsagePercentage() }}% {{ __('settings.subscription.used') }}
                                    </p>
                                </div>
                            </div>

                            {{-- Events Usage --}}
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        {{ __('settings.subscription.events') }}
                                    </h4>
                                    <i class="fa-solid fa-calendar-days text-gray-400"></i>
                                </div>
                                <div class="space-y-2">
                                    <div class="flex items-baseline justify-between">
                                        <span class="text-2xl font-semibold text-gray-900 dark:text-white">
                                            {{ $tenant->current_events }}
                                        </span>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                            / {{ $tenant->max_events }}
                                        </span>
                                    </div>
                                    <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2 overflow-hidden">
                                        <div class="bg-purple-500 h-2 rounded-full transition-all duration-300" 
                                             style="width: {{ min($tenant->getEventUsagePercentage(), 100) }}%"></div>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $tenant->getEventUsagePercentage() }}% {{ __('settings.subscription.used') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Actions --}}
            <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    <i class="fa-solid fa-circle-info mr-1"></i>
                    <span x-show="!hasChanges">{{ __('settings.hints.no_changes') }}</span>
                    <span x-show="hasChanges">{{ __('settings.hints.save_changes') }}</span>
                </p>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-150"
                        :disabled="loading || !hasChanges"
                        :class="{ 'opacity-50 cursor-not-allowed': !hasChanges }">
                    <span x-show="!loading" class="inline-flex items-center">
                        <i class="fa-solid fa-save mr-2"></i>
                        {{ __('common.actions.save_changes') }}
                    </span>
                    <span x-show="loading" x-cloak class="inline-flex items-center">
                        <i class="fa-solid fa-spinner fa-spin mr-2"></i>
                        {{ __('common.actions.saving') }}
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function settingsForm() {
    return {
        activeTab: 'general',
        loading: false,
        hasChanges: false,
        originalForm: null,
        form: {
            // Basic Information
            name: @json($tenant->name),
            legal_name: @json($tenant->legal_name),
            
            // Contact Information
            email: @json($tenant->email),
            phone: @json($tenant->phone),
            website: @json($tenant->website),
            
            // Address Information
            address_line_1: @json($tenant->address_line_1),
            address_line_2: @json($tenant->address_line_2),
            city: @json($tenant->city),
            state: @json($tenant->state),
            postal_code: @json($tenant->postal_code),
            country_id: @json($tenant->country_id),
            
            // Localization Settings
            language_id: @json($tenant->language_id),
            timezone_id: @json($tenant->timezone_id),
            date_format: @json($tenant->date_format),
            time_format: @json($tenant->time_format),
        },
        
        init() {
            // Meet2Be: Store original form data
            this.originalForm = JSON.stringify(this.form);
            
            // Watch for form changes
            this.$watch('form', () => {
                this.hasChanges = JSON.stringify(this.form) !== this.originalForm;
            }, { deep: true });
        },
        
        async submitForm() {
            if (!this.hasChanges) return;
            
            this.loading = true;
            
            try {
                const response = await fetch('{{ route('portal.setting.update', $tenant) }}', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(this.form)
                });
                
                const data = await response.json();
                
                if (response.ok && data.success) {
                    // Update original form data
                    this.originalForm = JSON.stringify(this.form);
                    this.hasChanges = false;
                    
                    // Show success notification
                    if (window.notify) {
                        window.notify('{{ __('common.success') }}', data.message || '{{ __('settings.messages.saved_successfully') }}', 'success');
                    }
                    
                    // If datetime settings changed, reload page to update all datetime displays
                    if (data.datetime_updated) {
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    }
                } else {
                    // Show error notification
                    if (window.notify) {
                        window.notify('{{ __('common.error') }}', data.message || '{{ __('settings.messages.save_failed') }}', 'error');
                    }
                    
                    // Handle validation errors
                    if (data.errors) {
                        console.error('Validation errors:', data.errors);
                    }
                }
            } catch (error) {
                console.error('Settings update error:', error);
                if (window.notify) {
                    window.notify('{{ __('common.error') }}', '{{ __('settings.messages.save_failed') }}', 'error');
                }
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>
@endsection 