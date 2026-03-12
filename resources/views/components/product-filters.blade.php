@props([
    'filters' => [],
    'sortOptions' => [],
    'selectedFilters' => [],
    'selectedSort' => null
])

<section class="collection-filter">
  <div class="container">
    <div class="filter-row">
      <div class="filter-main">
        <div class="filter-left">
          <span class="filter-toolbar-label">Filters</span>
          @foreach($filters as $filter)
              @php
                  $currentValues = $selectedFilters[$filter['name']] ?? [];
              @endphp
              @if(($filter['type'] ?? null) === 'text' || ($filter['type'] ?? null) === 'number')
                <div class="filter-input mb-2">
                  <label class="visually-hidden" for="filter-{{ $filter['name'] }}">{{ $filter['label'] }}</label>
                  <input id="filter-{{ $filter['name'] }}" type="{{ $filter['type'] ?? 'text' }}" class="form-control filter-input-field" name="{{ $filter['name'] }}" value="{{ $currentValues[0] ?? '' }}" placeholder="{{ $filter['label'] }}">
                </div>
              @elseif(($filter['type'] ?? null) === 'price-range')
                @php
                    $rangeMin = (int) ($filter['min'] ?? 0);
                    $rangeMax = (int) ($filter['max'] ?? max($rangeMin + 1000, 10000));
                    $rangeStep = (int) ($filter['step'] ?? 100);
                    $selectedMin = max((int) ($filter['selected_min'] ?? $rangeMin), $rangeMin);
                    $selectedMax = min((int) ($filter['selected_max'] ?? $rangeMax), $rangeMax);
                @endphp
                <div
                  class="filter-price-range"
                  data-min-name="{{ $filter['min_name'] }}"
                  data-max-name="{{ $filter['max_name'] }}"
                  data-min-default="{{ $rangeMin }}"
                  data-max-default="{{ $rangeMax }}"
                  data-min-value="{{ min($selectedMin, $selectedMax) }}"
                  data-max-value="{{ max($selectedMin, $selectedMax) }}"
                >
                  <div class="filter-price-range-top">
                    <span class="filter-price-range-label">{{ $filter['label'] }}</span>
                    <span class="filter-price-range-values">Rs {{ number_format(min($selectedMin, $selectedMax)) }} - Rs {{ number_format(max($selectedMin, $selectedMax)) }}</span>
                  </div>
                  <div class="filter-price-range-track">
                    <span class="filter-price-range-progress"></span>
                  </div>
                  <input type="range" class="filter-range-slider filter-range-slider-min" min="{{ $rangeMin }}" max="{{ $rangeMax }}" step="{{ $rangeStep }}" value="{{ min($selectedMin, $selectedMax) }}" aria-label="Minimum {{ strtolower($filter['label']) }}">
                  <input type="range" class="filter-range-slider filter-range-slider-max" min="{{ $rangeMin }}" max="{{ $rangeMax }}" step="{{ $rangeStep }}" value="{{ max($selectedMin, $selectedMax) }}" aria-label="Maximum {{ strtolower($filter['label']) }}">
                  <input type="hidden" class="filter-price-hidden-min" name="{{ $filter['min_name'] }}" value="{{ min($selectedMin, $selectedMax) }}">
                  <input type="hidden" class="filter-price-hidden-max" name="{{ $filter['max_name'] }}" value="{{ max($selectedMin, $selectedMax) }}">
                </div>
              @elseif(($filter['type'] ?? null) === 'checkbox')
                @php
                    $checkboxOption = $filter['options'][0] ?? ['value' => '1', 'label' => $filter['label']];
                @endphp
                <label class="filter-checkbox-wrap" for="filter-{{ $filter['name'] }}">
                  <input id="filter-{{ $filter['name'] }}" type="checkbox" class="filter-checkbox-field" name="{{ $filter['name'] }}" value="{{ $checkboxOption['value'] }}" {{ in_array($checkboxOption['value'], $currentValues) ? 'checked' : '' }}>
                  <span>{{ $filter['label'] }}</span>
                </label>
              @elseif(!empty($filter['options']))
                <div class="filter-select-wrap">
                  <label class="visually-hidden" for="filter-{{ $filter['name'] }}">{{ $filter['label'] }}</label>
                  <select id="filter-{{ $filter['name'] }}" class="filter-select-field" name="{{ $filter['name'] }}">
                    <option value="">{{ $filter['label'] }}</option>
                    @foreach($filter['options'] as $option)
                      <option value="{{ $option['value'] }}" {{ ($currentValues[0] ?? null) == $option['value'] ? 'selected' : '' }}>{{ $option['label'] }}</option>
                    @endforeach
                  </select>
                </div>
              @endif
          @endforeach
        </div>
        <button type="button" class="filter-reset-link">Clear filters</button>
      </div>
      <div class="filter-right">
        <div class="filter-sort-wrap">
          <label class="visually-hidden" for="sortSelect">Sort by</label>
          <select id="sortSelect">
            <option value="" {{ empty($selectedSort) ? 'selected' : '' }}>Sort by</option>
            @foreach($sortOptions as $sort)
              <option value="{{ $sort['value'] }}" {{ $selectedSort === $sort['value'] ? 'selected' : '' }}>{{ $sort['label'] }}</option>
            @endforeach
          </select>
        </div>
      </div>
    </div>
  </div>
</section>
