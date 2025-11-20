<div class="accordion-item feature-item" data-feature-index="{{ $index }}">
    <h2 class="accordion-header" id="headingFeature{{ $index }}">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
            data-bs-target="#collapseFeature{{ $index }}">
            <span class="drag-handle-feature me-2" style="cursor: move;">
                <i class="fa-solid fa-grip-vertical"></i>
            </span>
            <span class="feature-title">Feature #{{ (int) $index + 1 }}</span>
        </button>
    </h2>
    <div id="collapseFeature{{ $index }}" class="accordion-collapse collapse"
        data-bs-parent="#featuresAccordion">
        <div class="accordion-body">
            <div class="row">
                <div class="col-md-4">
                    <x-form.icon label="{{ __('Icon') }}" name="features[{{ $index }}][icon]"
                        :value="$feature['icon'] ?? ''" :text="false"/>
                </div>
                <div class="col-md-4">
                    <x-form.input label="{{ __('Sort') }}" type="number" min="0"
                        name="features[{{ $index }}][sort]" value="{{ $feature['sort'] ?? 0 }}" />
                </div>
            </div>

            <h6 class="mt-3 mb-2">Translations</h6>

            <div class="mb-3">
                <ul class="nav nav-tabs" role="tablist">
                    @foreach ($locales as $locale => $language)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link @if ($locale === config('app.locale')) active @endif"
                                id="feature-{{ $index }}-locale-{{ $locale }}-tab" data-bs-toggle="tab"
                                data-bs-target="#feature-{{ $index }}-locale-{{ $locale }}"
                                type="button" role="tab"
                                aria-controls="feature-{{ $index }}-locale-{{ $locale }}"
                                aria-selected="@if ($locale === config('app.locale')) true @else false @endif">
                                <img src="{{ asset($language['flag']) }}" class="lang-flag me-2"><span
                                    class="fs-lg">{{ $language['name'] }}</span>
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="tab-content">
                @foreach ($locales->keys() as $locale)
                    @php
                        $translation = collect($feature['translations'] ?? [])->firstWhere('locale', $locale) ?? [];
                    @endphp
                    <div @class([
                        'tab-pane fade',
                        'show active' => $locale === config('app.locale'),
                    ]) id="feature-{{ $index }}-locale-{{ $locale }}"
                        role="tabpanel" aria-labelledby="feature-{{ $index }}-locale-{{ $locale }}-tab">
                        <div class="mb-3 mt-2">
                            <x-form.input label="Title"
                                name="features[{{ $index }}][translations][{{ $locale }}][title]"
                                value="{{ $translation->title ?? '' }}" />
                            <x-form.textarea label="Description"
                                name="features[{{ $index }}][translations][{{ $locale }}][description]"
                                value="{{ $translation->description ?? '' }}" rows="3" />
                        </div>
                    </div>
                @endforeach
            </div>

            <h6 class="mt-3 mb-2">Feature Details</h6>

            <div class="accordion mt-2" id="detailsAccordion_{{ $index }}">
                @foreach ($feature['details'] ?? [] as $dIndex => $detail)
                    @include('frontends.products.partials.detail', [
                        'featureIndex' => $index,
                        'detailIndex' => $dIndex,
                        'detail' => $detail,
                        'locales' => $locales,
                    ])
                @endforeach
            </div>

            <button type="button" class="btn btn-outline-success mt-2 add-detail" data-feature="{{ $index }}">
                + Add Detail
            </button>

            <button type="button" class="btn btn-outline-danger mt-2 remove-feature float-end">
                Remove Feature
            </button>

        </div>
    </div>
</div>
