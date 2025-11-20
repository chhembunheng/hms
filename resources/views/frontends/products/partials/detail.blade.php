<div class="accordion-item detail-item" data-detail-index="{{ $detailIndex }}">
    <h2 class="accordion-header" id="headingDetail{{ $featureIndex }}_{{ $detailIndex }}">
        <button class="accordion-button collapsed" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#collapseDetail{{ $featureIndex }}_{{ $detailIndex }}">
            <span class="drag-handle-detail me-2" style="cursor: move;">
                <i class="fa-solid fa-grip-vertical"></i>
            </span>
            <span class="detail-title">Detail #<span class="detail-number">{{ (int) $detailIndex + 1 }}</span></span>
        </button>
    </h2>

    <div id="collapseDetail{{ $featureIndex }}_{{ $detailIndex }}"
         class="accordion-collapse collapse">
        <div class="accordion-body">

            <div class="row">
                <div class="col-md-4">
                    <x-form.icon 
                        label="{{ __('Icon') }}"
                        name="features[{{ $featureIndex }}][details][{{ $detailIndex }}][icon]"
                        :value="$detail['icon'] ?? ''" />
                </div>

                <div class="col-md-4">
                    <x-form.input 
                        label="{{ __('Sort') }}"
                        type="number" min="0"
                        name="features[{{ $featureIndex }}][details][{{ $detailIndex }}][sort]"
                        value="{{ $detail['sort'] ?? 0 }}" />
                </div>
            </div>

            <div class="mb-3 mt-3">
                <ul class="nav nav-tabs" role="tablist">
                    @foreach ($locales as $locale => $language)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link @if ($locale === config('app.locale')) active @endif" 
                                id="detail-{{ $featureIndex }}-{{ $detailIndex }}-locale-{{ $locale }}-tab" 
                                data-bs-toggle="tab" 
                                data-bs-target="#detail-{{ $featureIndex }}-{{ $detailIndex }}-locale-{{ $locale }}" 
                                type="button" role="tab" 
                                aria-controls="detail-{{ $featureIndex }}-{{ $detailIndex }}-locale-{{ $locale }}"
                                aria-selected="@if ($locale === config('app.locale')) true @else false @endif">
                                <img src="{{ asset($language['flag']) }}" class="lang-flag me-2"><span class="fs-lg">{{ $language['name'] }}</span>
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="tab-content">
                @foreach ($locales as $locale => $language)
                    <div @class([
                        'tab-pane fade',
                        'show active' => $locale === config('app.locale'),
                    ]) id="detail-{{ $featureIndex }}-{{ $detailIndex }}-locale-{{ $locale }}" role="tabpanel" aria-labelledby="detail-{{ $featureIndex }}-{{ $detailIndex }}-locale-{{ $locale }}-tab">
                        <div class="mb-3 mt-2">
                            <x-form.input 
                                label="Title"
                                name="features[{{ $featureIndex }}][details][{{ $detailIndex }}][translations][{{ $locale }}][title]"
                                value="{{ $detail['translations'][$locale]['title'] ?? '' }}" />

                            <x-form.textarea
                                label="Description"
                                name="features[{{ $featureIndex }}][details][{{ $detailIndex }}][translations][{{ $locale }}][description]"
                                value="{{ $detail['translations'][$locale]['description'] ?? '' }}" />
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="button"
                class="btn btn-sm btn-outline-danger remove-detail"
                data-feature="{{ $featureIndex }}">
                Remove Detail
            </button>

        </div>
    </div>
</div>
