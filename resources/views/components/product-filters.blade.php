@props([
    'filters' => [],
    'sortOptions' => [],
    'selectedFilters' => [],
    'selectedSort' => null
])

<section class="collection-filter">
  <div class="container">
    <div class="filter-row">
      <div class="filter-left">
        @foreach($filters as $filter)
            @if(($filter['type'] ?? null) === 'text' || ($filter['type'] ?? null) === 'number')
                <div class="filter-input mb-2">
                    <label>{{ $filter['label'] }}</label>
                    <input type="{{ $filter['type'] ?? 'text' }}" class="form-control filter-input-field" name="{{ $filter['name'] }}" placeholder="{{ $filter['placeholder'] ?? '' }}">
                </div>
            @elseif(!empty($filter['options']))
                <div class="filter-dropdown">
                    <button class="filter-btn">{{ $filter['label'] }}</button>
                    <ul class="filter-menu">
                        @foreach($filter['options'] as $option)
                            <li data-filter="{{ $filter['name'] }}" data-value="{{ $option['value'] }}" class="{{ in_array($option['value'], $selectedFilters[$filter['name']] ?? []) ? 'active' : '' }}">{{ $option['label'] }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        @endforeach
      </div>
      <div class="filter-right">
        <label>Sort by:</label>
        <select id="sortSelect">
          @foreach($sortOptions as $sort)
            <option value="{{ $sort['value'] }}" {{ $selectedSort === $sort['value'] ? 'selected' : '' }}>{{ $sort['label'] }}</option>
          @endforeach
        </select>
      </div>
    </div>
  </div>
</section>
