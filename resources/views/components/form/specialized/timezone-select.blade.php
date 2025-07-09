{{-- Meet2Be: Timezone select component --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Searchable timezone selector with grouping --}}

@props([
    'name' => 'timezone_id',
    'label' => null,
    'value' => null,
    'placeholder' => null,
    'hint' => null,
    'required' => false,
    'disabled' => false,
    'timezones' => null,
    'wrapperClass' => ''
])

@php
    $label = $label ?? __('settings.fields.timezone');
    $placeholder = $placeholder ?? __('common.select');
    $selectedValue = old($name, $value);
    
    // Get all timezones if not provided
    $timezones = $timezones ?? App\Models\System\Timezone::orderBy('name')->get();
    
    // Generate unique ID
    $fieldId = $name . '_' . uniqid();
@endphp

<x-form.base.field-wrapper 
    :name="$name" 
    :label="$label" 
    :required="$required" 
    :hint="$hint"
    :wrapper-class="$wrapperClass"
    :field-id="$fieldId">
    
    <div 
        x-data="{
            open: false,
            search: '',
            selectedId: '{{ $selectedValue }}',
            timezones: {{ Js::from($timezones) }},
            
            get filteredTimezones() {
                if (!this.search) return this.timezones;
                
                const searchLower = this.search.toLowerCase();
                return this.timezones.filter(tz => 
                    tz.name.toLowerCase().includes(searchLower) ||
                    tz.offset.toString().includes(searchLower)
                );
            },
            
            get groupedTimezones() {
                const groups = {};
                this.filteredTimezones.forEach(tz => {
                    const parts = tz.name.split('/');
                    const region = parts[0] || 'Other';
                    
                    if (!groups[region]) {
                        groups[region] = [];
                    }
                    groups[region].push(tz);
                });
                return groups;
            },
            
            get selectedTimezone() {
                return this.timezones.find(tz => tz.id === this.selectedId);
            },
            
            selectTimezone(timezoneId) {
                this.selectedId = timezoneId;
                this.open = false;
                this.search = '';
            },
            
            toggleDropdown() {
                this.open = !this.open;
                if (this.open) {
                    this.$nextTick(() => {
                        this.$refs.searchInput?.focus();
                    });
                }
            },
            
            closeDropdown() {
                this.open = false;
                this.search = '';
            },
            
            formatTimezone(tz) {
                const offset = tz.offset >= 0 ? '+' + tz.offset.toFixed(1) : tz.offset.toFixed(1);
                return tz.name + ' (UTC' + offset + ')';
            }
        }"
        x-modelable="selectedId"
        {{ $attributes->whereStartsWith('x-model') }}
        @click.away="closeDropdown()"
        class="relative"
    >
        {{-- Select Button --}}
        <button
            type="button"
            id="{{ $fieldId }}"
            @click="toggleDropdown()"
            {{ $disabled ? 'disabled' : '' }}
            class="relative w-full px-3 py-2 text-left {{ $disabled ? 'bg-gray-50 dark:bg-gray-800' : 'bg-white dark:bg-gray-700' }} border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-colors duration-150"
            :class="{ 'cursor-not-allowed opacity-60': {{ $disabled ? 'true' : 'false' }} }"
        >
            <span x-show="!selectedTimezone" class="block truncate text-gray-400 dark:text-gray-500">
                {{ $placeholder }}
            </span>
            
            <span x-show="selectedTimezone" class="block truncate text-gray-900 dark:text-white" x-text="selectedTimezone ? formatTimezone(selectedTimezone) : ''"></span>
            
            <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                <i class="fas fa-chevron-down text-gray-400"></i>
            </span>
        </button>
        
        {{-- Hidden input --}}
        <input 
            type="hidden" 
            name="{{ $name }}" 
            id="{{ $fieldId }}_hidden"
            :value="selectedId"
            @if($required) required @endif
        />
        
        {{-- Dropdown --}}
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="transform opacity-0 scale-95"
            x-transition:enter-end="transform opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="transform opacity-100 scale-100"
            x-transition:leave-end="transform opacity-0 scale-95"
            class="absolute z-50 mt-1 w-full bg-white dark:bg-gray-700 shadow-lg rounded-md border border-gray-200 dark:border-gray-600"
            style="display: none;"
        >
            {{-- Search Input --}}
            <div class="p-2 border-b border-gray-200 dark:border-gray-600">
                <input
                    type="text"
                    id="{{ $fieldId }}_search"
                    name="{{ $name }}_search"
                    x-model="search"
                    x-ref="searchInput"
                    @click.stop
                    placeholder="{{ __('common.search') }}"
                    autocomplete="off"
                    class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:text-white"
                />
            </div>
            
            {{-- Timezone List --}}
            <ul class="max-h-60 overflow-auto py-1">
                <template x-for="(timezones, region) in groupedTimezones" :key="region">
                    <li>
                        <div class="px-3 py-1 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider bg-gray-50 dark:bg-gray-800">
                            <span x-text="region"></span>
                        </div>
                        <template x-for="timezone in timezones" :key="timezone.id">
                            <button
                                type="button"
                                @click="selectTimezone(timezone.id)"
                                class="w-full px-3 py-2 text-left hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-600 transition-colors duration-150 flex items-center justify-between"
                                :class="{ 'bg-blue-50 dark:bg-blue-900/50': selectedId === timezone.id }"
                            >
                                <span class="text-sm text-gray-900 dark:text-white" x-text="formatTimezone(timezone)"></span>
                                <i x-show="selectedId === timezone.id" class="fas fa-check ml-2 text-blue-600 dark:text-blue-400"></i>
                            </button>
                        </template>
                    </li>
                </template>
                
                <li x-show="Object.keys(groupedTimezones).length === 0" class="px-3 py-2 text-sm text-gray-500 dark:text-gray-400 text-center">
                    {{ __('common.no_results') }}
                </li>
            </ul>
        </div>
    </div>
    
</x-form.base.field-wrapper> 