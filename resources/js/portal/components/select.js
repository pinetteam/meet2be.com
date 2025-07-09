// Meet2Be: Searchable Select Component
// Author: Meet2Be Development Team
// Reusable searchable select component with Alpine.js

export default function searchableSelect(config) {
    return {
        // Configuration
        name: config.name || '',
        placeholder: config.placeholder || 'Select...',
        grouped: config.grouped || false,
        disabled: config.disabled || false,
        size: config.size || 'md',
        ajax: config.ajax || false,
        ajaxUrl: config.ajaxUrl || null,
        xModel: config.xModel || null,
        
        // State
        showDropdown: false,
        search: '',
        selectedValue: config.value || '',
        selectedDisplay: '',
        loading: false,
        options: [],
        errors: false,
        focusedIndex: -1,
        
        // Initialization
        init() {
            // Load initial options
            this.loadOptions();
            
            // Set initial display
            this.updateSelectedDisplay();
            
            // Handle x-model binding
            if (this.xModel) {
                this.$watch(this.xModel, (value) => {
                    this.selectedValue = value;
                    this.updateSelectedDisplay();
                });
            }
            
            // Handle clicks outside
            document.addEventListener('click', (e) => {
                if (!this.$el.contains(e.target)) {
                    this.closeDropdown();
                }
            });
        },
        
        // Load options
        async loadOptions() {
            if (this.ajax && this.ajaxUrl) {
                this.loading = true;
                try {
                    const response = await fetch(this.ajaxUrl);
                    const data = await response.json();
                    this.options = data;
                } catch (error) {
                    console.error('Failed to load options:', error);
                    this.errors = true;
                } finally {
                    this.loading = false;
                }
            } else {
                // Options are passed via HTML
                const selectEl = this.$el.querySelector('select.hidden');
                if (selectEl) {
                    this.options = this.parseSelectOptions(selectEl);
                }
            }
        },
        
        // Parse options from hidden select element
        parseSelectOptions(selectEl) {
            const options = [];
            
            if (this.grouped) {
                selectEl.querySelectorAll('optgroup').forEach(group => {
                    const groupOptions = [];
                    group.querySelectorAll('option').forEach(option => {
                        if (option.value) {
                            groupOptions.push({
                                value: option.value,
                                label: option.textContent.trim(),
                                selected: option.selected
                            });
                        }
                    });
                    
                    if (groupOptions.length > 0) {
                        options.push({
                            label: group.label,
                            options: groupOptions
                        });
                    }
                });
            } else {
                selectEl.querySelectorAll('option').forEach(option => {
                    if (option.value) {
                        options.push({
                            value: option.value,
                            label: option.textContent.trim(),
                            selected: option.selected
                        });
                    }
                });
            }
            
            return options;
        },
        
        // Update selected display text
        updateSelectedDisplay() {
            if (!this.selectedValue) {
                this.selectedDisplay = '';
                return;
            }
            
            if (this.grouped) {
                for (const group of this.options) {
                    const option = group.options.find(o => o.value === this.selectedValue);
                    if (option) {
                        this.selectedDisplay = option.label;
                        break;
                    }
                }
            } else {
                const option = this.options.find(o => o.value === this.selectedValue);
                if (option) {
                    this.selectedDisplay = option.label;
                }
            }
        },
        
        // Toggle dropdown
        toggleDropdown() {
            if (this.disabled) return;
            this.showDropdown = !this.showDropdown;
            
            if (this.showDropdown) {
                this.$nextTick(() => {
                    const searchInput = this.$el.querySelector('input[type="search"]');
                    if (searchInput) searchInput.focus();
                });
            }
        },
        
        // Close dropdown
        closeDropdown() {
            this.showDropdown = false;
            this.search = '';
            this.focusedIndex = -1;
        },
        
        // Select option
        selectOption(value) {
            this.selectedValue = value;
            this.updateSelectedDisplay();
            this.closeDropdown();
            
            // Trigger change event
            const event = new Event('change', { bubbles: true });
            this.$el.querySelector('input[type="hidden"]').dispatchEvent(event);
        },
        
        // Get filtered options HTML
        get filteredOptionsHtml() {
            let html = '';
            const searchLower = this.search.toLowerCase();
            
            if (this.grouped) {
                this.options.forEach(group => {
                    const filteredOptions = group.options.filter(option => 
                        option.label.toLowerCase().includes(searchLower)
                    );
                    
                    if (filteredOptions.length > 0) {
                        html += `<li class="text-xs font-semibold text-gray-400 dark:text-gray-500 px-3 py-2 uppercase tracking-wider">${group.label}</li>`;
                        
                        filteredOptions.forEach((option, index) => {
                            const isSelected = option.value === this.selectedValue;
                            const isFocused = this.focusedIndex === index;
                            
                            html += `
                                <li>
                                    <button type="button" 
                                        @click="selectOption('${option.value}')"
                                        class="w-full text-left px-3 py-2 hover:bg-gray-50 dark:hover:bg-gray-700 focus:bg-gray-50 dark:focus:bg-gray-700 focus:outline-none transition-colors duration-150 ${isSelected ? 'bg-blue-50 dark:bg-blue-900/50' : ''} ${isFocused ? 'bg-gray-50 dark:bg-gray-700' : ''}"
                                        role="option"
                                        aria-selected="${isSelected}">
                                        <span class="block truncate ${isSelected ? 'font-semibold' : ''}">${option.label}</span>
                                    </button>
                                </li>
                            `;
                        });
                    }
                });
            } else {
                const filteredOptions = this.options.filter(option => 
                    option.label.toLowerCase().includes(searchLower)
                );
                
                filteredOptions.forEach((option, index) => {
                    const isSelected = option.value === this.selectedValue;
                    const isFocused = this.focusedIndex === index;
                    
                    html += `
                        <li>
                            <button type="button" 
                                @click="selectOption('${option.value}')"
                                class="w-full text-left px-3 py-2 hover:bg-gray-50 dark:hover:bg-gray-700 focus:bg-gray-50 dark:focus:bg-gray-700 focus:outline-none transition-colors duration-150 ${isSelected ? 'bg-blue-50 dark:bg-blue-900/50' : ''} ${isFocused ? 'bg-gray-50 dark:bg-gray-700' : ''}"
                                role="option"
                                aria-selected="${isSelected}">
                                <span class="block truncate ${isSelected ? 'font-semibold' : ''}">${option.label}</span>
                            </button>
                        </li>
                    `;
                });
                
                if (filteredOptions.length === 0) {
                    html = '<li class="px-3 py-2 text-sm text-gray-500 dark:text-gray-400 text-center">No results found</li>';
                }
            }
            
            return html;
        },
        
        // Keyboard navigation
        handleKeydown(event) {
            if (!this.showDropdown) {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    this.toggleDropdown();
                }
                return;
            }
            
            switch (event.key) {
                case 'Escape':
                    this.closeDropdown();
                    break;
                case 'ArrowDown':
                    event.preventDefault();
                    this.focusNext();
                    break;
                case 'ArrowUp':
                    event.preventDefault();
                    this.focusPrevious();
                    break;
                case 'Enter':
                    event.preventDefault();
                    if (this.focusedIndex >= 0) {
                        const options = this.grouped ? this.options.flatMap(g => g.options) : this.options;
                        const filteredOptions = options.filter(o => o.label.toLowerCase().includes(this.search.toLowerCase()));
                        if (filteredOptions[this.focusedIndex]) {
                            this.selectOption(filteredOptions[this.focusedIndex].value);
                        }
                    }
                    break;
            }
        },
        
        focusNext() {
            const options = this.grouped ? this.options.flatMap(g => g.options) : this.options;
            const filteredOptions = options.filter(o => o.label.toLowerCase().includes(this.search.toLowerCase()));
            
            if (this.focusedIndex < filteredOptions.length - 1) {
                this.focusedIndex++;
            } else {
                this.focusedIndex = 0;
            }
        },
        
        focusPrevious() {
            const options = this.grouped ? this.options.flatMap(g => g.options) : this.options;
            const filteredOptions = options.filter(o => o.label.toLowerCase().includes(this.search.toLowerCase()));
            
            if (this.focusedIndex > 0) {
                this.focusedIndex--;
            } else {
                this.focusedIndex = filteredOptions.length - 1;
            }
        }
    };
} 