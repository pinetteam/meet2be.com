@props([
    'column',
    'label',
    'sortBy' => request('sort_by'),
    'sortOrder' => request('sort_order', 'asc')
])

@php
    $isActive = $sortBy === $column;
    $nextOrder = $isActive && $sortOrder === 'asc' ? 'desc' : 'asc';
    $params = request()->except(['sort_by', 'sort_order']);
    $params['sort_by'] = $column;
    $params['sort_order'] = $nextOrder;
@endphp

<a href="{{ request()->url() . '?' . http_build_query($params) }}" 
   class="group inline-flex items-center gap-1 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider hover:text-gray-700 dark:hover:text-gray-300">
    {{ $label }}
    <span class="flex items-center">
        @if($isActive)
            @if($sortOrder === 'asc')
                <i class="fa-light fa-arrow-up text-gray-700 dark:text-gray-300"></i>
            @else
                <i class="fa-light fa-arrow-down text-gray-700 dark:text-gray-300"></i>
            @endif
        @else
            <i class="fa-light fa-arrows-up-down opacity-0 group-hover:opacity-100 transition-opacity"></i>
        @endif
    </span>
</a> 