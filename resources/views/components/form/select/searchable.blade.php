{{-- Meet2Be: Searchable select component with Atlassian design --}}
{{-- Author: Meet2Be Development Team --}}
{{-- Advanced select with search and grouping support --}}

@props([
    'name',
    'label' => null,
    'value' => '',
    'placeholder' => '',
    'hint' => null,
    'required' => false,
    'disabled' => false,
    'model' => null,
    'size' => 'md',
    'wrapperClass' => '',
    'grouped' => false,
    'searchPlaceholder' => null,
    'noResultsText' => null,
    'loadingText' => null,
    'ajax' => false, // For future AJAX support
    'ajaxUrl' => null,
    'autocomplete' => 'off'
])

@php
    $searchPlaceholder = $searchPlaceholder ?? __('common.search');
    $noResultsText = $noResultsText ?? __('common.no_results');
    $loadingText = $loadingText ?? __('common.loading');
    
    // Meet2Be: Extract x-model from attributes
    $xModel = $attributes->get('x-model');
    
    // Generate unique ID
    $fieldId = $name . '_' . uniqid();
@endphp

<x-form.base.field-wrapper 
    :name="$name" 
    :label="$label" 
    :required="$required" 
    :hint="$hint"
    :wrapper-class="$wrapperClass">
    
    <div x-data="searchableSelect(@js([
        'name' => $name,
        'value' => old($name, $value),
        'placeholder' => $placeholder,
        'grouped' => $grouped,
        'disabled' => $disabled,
        'size' => $size,
        'ajax' => $ajax,
        'ajaxUrl' => $ajaxUrl,
        'xModel' => $xModel
    ]))" @if($xModel) x-modelable="selectedValue" x-model="{{ $xModel }}" @endif>
        <div class="relative">
            {{-- Select Button --}}
            <button type="button"
                    @click="toggleDropdown()"
                    :disabled="disabled"
                    class="relative w-full bg-white dark:bg-gray-700 border rounded-md shadow-sm text-left cursor-default focus:outline-none focus:ring-1 transition-colors duration-150"
                    :class="{
                        'border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-blue-500': !errors,
                        'border-red-500 dark:border-red-400 focus:border-red-500 focus:ring-red-500': errors,
                        'bg-gray-50 dark:bg-gray-600 cursor-not-allowed': disabled,
                        'px-2.5 py-1.5 text-xs': size === 'sm',
                        'px-3 py-2 text-sm': size === 'md',
                        'px-4 py-2.5 text-base': size === 'lg'
                    }">
                <span class="block truncate" x-html="selectedDisplay || placeholder || '{{ __('common.select') }}'"></span>
                <span class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                    <i class="fa-solid fa-chevron-down text-gray-400 transition-transform duration-200" 
                       :class="{ 'rotate-180': showDropdown, 'text-xs': size === 'sm', 'text-sm': size !== 'sm' }"></i>
                </span>
            </button>
            
            {{-- Dropdown --}}
            <div x-show="showDropdown"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 @click.away="closeDropdown()"
                 class="absolute z-50 mt-1 w-full rounded-md bg-white dark:bg-gray-800 shadow-lg max-h-60 overflow-hidden"
                 style="display: none;">
                
                {{-- Search Input --}}
                <div class="sticky top-0 z-10 bg-white dark:bg-gray-800 px-2 py-2 border-b border-gray-200 dark:border-gray-700">
                    <input type="text"
                           x-ref="searchInput"
                           x-model="search"
                           @click.stop
                           @keydown.escape="closeDropdown()"
                           @keydown.enter.prevent="selectHighlighted()"
                           @keydown.arrow-up.prevent="highlightPrevious()"
                           @keydown.arrow-down.prevent="highlightNext()"
                           class="w-full px-3 py-1.5 text-sm border-gray-300 dark:border-gray-600 rounded-md focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"
                           placeholder="{{ $searchPlaceholder }}">
                </div>
                
                {{-- Options List --}}
                <div class="overflow-y-auto max-h-48" x-ref="optionsList">
                    <div x-show="loading" class="px-3 py-8 text-center text-gray-500 dark:text-gray-400">
                        <i class="fa-solid fa-spinner fa-spin mr-2"></i>{{ $loadingText }}
                    </div>
                    <div x-show="!loading" x-html="filteredOptionsHtml"></div>
                </div>
            </div>
            
            {{-- Hidden input --}}
            <input type="hidden" 
                   name="{{ $name }}" 
                   x-model="selectedValue"
                   @if($xModel) x-modelable="selectedValue" @endif
                   @if($required) required @endif
                   id="{{ $fieldId }}"
                   :value="selectedValue"
                   autocomplete="{{ $autocomplete }}">
        </div>
    </div>

    {{-- Options slot for server-side rendering --}}
    <div style="display: none;" data-select-options>
        {{ $slot }}
    </div>
</x-form.base.field-wrapper>

@push('scripts')
<script>
// Meet2Be: Searchable select component logic
function searchableSelect(config) {
    return {
        showDropdown: false,
        search: '',
        selectedValue: config.value || '',
        selectedLabel: '',
        options: [],
        filteredOptions: [],
        highlightedIndex: -1,
        loading: false,
        disabled: config.disabled || false,
        size: config.size || 'md',
        errors: false,
        
        init() {
            // Meet2Be: Parse options from slot content
            this.parseOptions();
            
            // Set initial value if provided
            if (config.value) {
                this.selectedValue = config.value;
            }
            
            this.updateSelectedLabel();
            
            // Watch for value changes
            this.$watch('selectedValue', () => {
                this.updateSelectedLabel();
            });
            
            // Meet2Be: Handle x-model binding
            if (config.xModel) {
                // Watch external model changes
                this.$watch('$parent.' + config.xModel, (value) => {
                    if (value !== this.selectedValue) {
                        this.selectedValue = value;
                    }
                });
                
                // Update parent model when selection changes
                this.$watch('selectedValue', (value) => {
                    this.$parent[config.xModel] = value;
                });
            }
        },
        
        parseOptions() {
            const optionsContainer = this.$el.querySelector('[data-select-options]');
            if (!optionsContainer) return;
            
            const selectEl = document.createElement('div');
            selectEl.innerHTML = optionsContainer.innerHTML;
            
            this.options = [];
            
            if (config.grouped) {
                // Handle grouped options
                selectEl.querySelectorAll('optgroup').forEach(group => {
                    const groupOptions = [];
                    group.querySelectorAll('option').forEach(option => {
                        if (option.value) {
                            groupOptions.push({
                                value: option.value,
                                label: option.textContent.trim(),
                                group: group.label,
                                disabled: option.disabled || false,
                                data: this.getDataAttributes(option)
                            });
                        }
                    });
                    this.options.push(...groupOptions);
                });
            }
            
            // Handle regular options
            selectEl.querySelectorAll('option').forEach(option => {
                if (option.value && !option.closest('optgroup')) {
                    this.options.push({
                        value: option.value,
                        label: option.textContent.trim(),
                        group: null,
                        disabled: option.disabled || false,
                        data: this.getDataAttributes(option)
                    });
                }
            });
            
            this.filterOptions();
        },
        
        getDataAttributes(element) {
            const data = {};
            Array.from(element.attributes).forEach(attr => {
                if (attr.name.startsWith('data-')) {
                    const key = attr.name.replace('data-', '');
                    data[key] = attr.value;
                }
            });
            return data;
        },
        
        filterOptions() {
            if (!this.search) {
                this.filteredOptions = this.options;
                return;
            }
            
            const searchLower = this.search.toLowerCase();
            this.filteredOptions = this.options.filter(option => 
                option.label.toLowerCase().includes(searchLower) ||
                (option.group && option.group.toLowerCase().includes(searchLower))
            );
        },
        
        get filteredOptionsHtml() {
            let html = '';
            let currentGroup = null;
            
            this.filteredOptions.forEach((option, index) => {
                if (config.grouped && option.group !== currentGroup) {
                    if (currentGroup !== null) {
                        html += '</div>';
                    }
                    currentGroup = option.group;
                    html += `<div class="pt-2">
                        <div class="px-3 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            ${option.group}
                        </div>`;
                }
                
                const isSelected = option.value === this.selectedValue;
                const isHighlighted = index === this.highlightedIndex;
                const isDisabled = option.disabled;
                
                // Meet2Be: Check if this is a country select with flag
                let optionContent = option.label;
                if (option.data && option.data.flag) {
                    const flagPath = `/assets/images/flags/32x24/${option.data.flag.toLowerCase()}.png`;
                    optionContent = `
                        <div class="flex items-center">
                            <img src="${flagPath}" 
                                 alt="${option.label}"
                                 class="w-5 h-4 mr-3 rounded-sm"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-flex';">
                            <span class="hidden items-center justify-center w-5 h-4 mr-3 text-xs font-medium bg-gray-200 dark:bg-gray-600 rounded-sm text-gray-600 dark:text-gray-300">
                                ${option.data.flag}
                            </span>
                            <span>${option.label}</span>
                        </div>`;
                }
                
                html += `
                    <button type="button"
                            @click="${!isDisabled ? `selectOption('${option.value}', '${option.label.replace(/'/g, "\\'")}')` : ''}"
                            @mouseenter="highlightedIndex = ${index}"
                            class="w-full text-left px-3 py-2 text-sm transition-colors duration-150 ${
                                isDisabled 
                                    ? 'opacity-50 cursor-not-allowed' 
                                    : 'hover:bg-gray-100 dark:hover:bg-gray-700 focus:bg-gray-100 dark:focus:bg-gray-700 focus:outline-none cursor-pointer'
                            } ${
                                isSelected 
                                    ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' 
                                    : 'text-gray-900 dark:text-white'
                            } ${
                                isHighlighted && !isDisabled
                                    ? 'bg-gray-100 dark:bg-gray-700'
                                    : ''
                            }"
                            ${isDisabled ? 'disabled' : ''}>
                        ${optionContent}
                    </button>`;
            });
            
            if (config.grouped && currentGroup !== null) {
                html += '</div>';
            }
            
            if (this.filteredOptions.length === 0) {
                html = `<div class="px-3 py-8 text-center text-gray-500 dark:text-gray-400">
                    {{ $noResultsText }}
                </div>`;
            }
            
            return html;
        },
        
        toggleDropdown() {
            if (this.disabled) return;
            
            this.showDropdown = !this.showDropdown;
            if (this.showDropdown) {
                this.$nextTick(() => {
                    this.$refs.searchInput?.focus();
                    this.highlightedIndex = -1;
                });
            }
        },
        
        closeDropdown() {
            this.showDropdown = false;
            this.search = '';
            this.filterOptions();
            this.highlightedIndex = -1;
        },
        
        selectOption(value, label) {
            this.selectedValue = value;
            this.selectedLabel = label;
            this.closeDropdown();
            
            // Trigger change event
            this.$nextTick(() => {
                const input = this.$el.querySelector('input[type="hidden"]');
                input.dispatchEvent(new Event('change', { bubbles: true }));
            });
        },
        
        updateSelectedLabel() {
            const selected = this.options.find(opt => opt.value === this.selectedValue);
            this.selectedLabel = selected ? selected.label : '';
            
            // Meet2Be: Update display with flag if available
            if (selected && selected.data && selected.data.flag) {
                const flagPath = `/assets/images/flags/32x24/${selected.data.flag.toLowerCase()}.png`;
                this.selectedDisplay = `
                    <div class="flex items-center">
                        <img src="${flagPath}" 
                             alt="${selected.label}"
                             class="w-5 h-4 mr-2 rounded-sm inline-block"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-flex';">
                        <span class="hidden items-center justify-center w-5 h-4 mr-2 text-xs font-medium bg-gray-200 dark:bg-gray-600 rounded-sm text-gray-600 dark:text-gray-300">
                            ${selected.data.flag}
                        </span>
                        <span>${selected.label}</span>
                    </div>`;
            } else {
                this.selectedDisplay = this.selectedLabel;
            }
        },
        
        get selectedDisplay() {
            return this._selectedDisplay || this.selectedLabel;
        },
        
        set selectedDisplay(value) {
            this._selectedDisplay = value;
        },
        
        highlightNext() {
            if (this.highlightedIndex < this.filteredOptions.length - 1) {
                this.highlightedIndex++;
                this.scrollToHighlighted();
            }
        },
        
        highlightPrevious() {
            if (this.highlightedIndex > 0) {
                this.highlightedIndex--;
                this.scrollToHighlighted();
            }
        },
        
        selectHighlighted() {
            if (this.highlightedIndex >= 0 && this.highlightedIndex < this.filteredOptions.length) {
                const option = this.filteredOptions[this.highlightedIndex];
                if (!option.disabled) {
                    this.selectOption(option.value, option.label);
                }
            }
        },
        
        scrollToHighlighted() {
            this.$nextTick(() => {
                const highlighted = this.$refs.optionsList?.querySelectorAll('button')[this.highlightedIndex];
                if (highlighted) {
                    highlighted.scrollIntoView({ block: 'nearest' });
                }
            });
        }
    }
}
</script>
@endpush 