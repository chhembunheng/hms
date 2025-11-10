<x-app-layout>
    <x-form.layout :form="$form">
        <div class="row">
            <div class="col-md-6">
                <x-form.icon :label="__('form.icon')" name="icon" value="{{ old('icon', $form?->icon) }}" />
            </div>
            <div class="col-md-6">
                <x-form.select multiple data-searchable="false" :label="__('form.category')" name="category_id[]" :selected="$form?->categories?->pluck('id')->toArray()" data-placeholder="Select a category" :options="$categories" required />
            </div>
            <div class="col-md-6">
                <x-form.select multiple data-searchable="false" :label="__('form.tags')" name="tag_id[]" :selected="$form?->tags?->pluck('id')->toArray()" data-placeholder="Select tags" :options="$categories" required />
            </div>
            <div class="col-md-6">
                <x-form.input :label="__('form.sort')" type="number" name="sort" value="{{ $form?->sort ?? 0 }}" min="0" />
            </div>
        </div>
        <hr class="my-4">
        <div class="row">
            <div class="col-12">
                <x-form.input :label="__('form.image')" type="file" name="image" accept="image/*" :initialPreview="$form?->image ? asset($form->image) : null" :initialCaption="$form?->image ? basename($form->image) : null" :initialSize="$form?->image ? getFileSize($form->image) : null" />
            </div>
        </div>
        <hr class="my-4">
        <div class="mb-3">
            <ul class="nav nav-tabs" role="tablist">
                @foreach ($locales as $locale => $language)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link @if ($locale === config('app.locale')) active @endif" id="locale-{{ $locale }}-tab" data-bs-toggle="tab" data-bs-target="#locale-{{ $locale }}" type="button" role="tab" aria-controls="locale-{{ $locale }}"
                            aria-selected="@if ($locale === config('app.locale')) true @else false @endif">
                            <img src="{{ asset($language['flag']) }}" class="lang-flag me-2"><span class="fs-lg">{{ $language['name'] }}</span>
                        </button>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="tab-content" id="localeTabContent">
            @foreach ($locales as $locale => $language)
                <div @class([
                    'tab-pane fade',
                    'show active' => $locale === config('app.locale'),
                ]) id="locale-{{ $locale }}" role="tabpanel" aria-labelledby="locale-{{ $locale }}-tab">
                    <div class="row">
                        <div class="col-md-8 offset-md-2">
                            <x-form.input @class(['content-seo' => $locale === config('app.locale')]) :label="__('form.name')" name="translations[{{ $locale }}][name]" :value="old('translations.' . $locale . '.name', $translations[$locale]['name'] ?? '')" required />
                        </div>
                        <div class="col-md-8 offset-md-2">
                            <x-form.textarea :label="__('form.short_description')" name="translations[{{ $locale }}][short_description]" :value="old('translations.' . $locale . '.short_description', $translations[$locale]['short_description'] ?? '')" rows="2" />
                        </div>
                        <div class="col-md-8 offset-md-2">
                            <x-form.textarea :label="__('form.description')" name="translations[{{ $locale }}][description]" :value="old('translations.' . $locale . '.description', $translations[$locale]['description'] ?? '')" rows="5" class="editor" />
                        </div>
                        <div class="col-md-8 offset-md-2">
                            <x-form.textarea :label="__('form.content')" name="translations[{{ $locale }}][content]" :value="old('translations.' . $locale . '.content', $translations[$locale]['content'] ?? '')" rows="6" class="editor content-{{ $locale }}" />
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <hr class="my-4">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <h4 class="mt-4">{{ __('Product Features') }}</h4>
                <hr>
                <div id="product-features-container">
                    @php
                        $existingFeatures = old('features', $form?->features ?? []);
                        $featureIndex = 0;
                    @endphp
                    @if (count($existingFeatures) > 0)
                        @foreach ($existingFeatures as $feature)
                            <div class="feature-item card mb-3" data-index="{{ $featureIndex }}">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="mb-0">Feature #<span class="feature-number">{{ $featureIndex + 1 }}</span></h5>
                                        <button type="button" class="btn btn-sm btn-outline-danger remove-feature">
                                            <i class="fa-solid fa-trash-alt fa-fw"></i> &nbsp;Remove
                                        </button>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <x-form.icon :label="__('form.icon')" name="features[{{ $featureIndex }}][icon]" value="{{ old('features.' . $featureIndex . '.icon', $feature->icon ?? '') }}" />
                                        </div>
                                        <div class="col-md-6">
                                            <x-form.input :label="__('form.sort')" type="number" name="features[{{ $featureIndex }}][sort]" value="{{ old('features.' . $featureIndex . '.sort', $feature->sort ?? 0) }}" min="0" />
                                        </div>
                                    </div>
                                    <div class="mb-3 mt-3">
                                        <ul class="nav nav-tabs" role="tablist">
                                            @foreach ($locales as $locale => $language)
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link @if ($locale === config('app.locale')) active @endif" id="feature-{{ $featureIndex }}-locale-{{ $locale }}-tab" data-bs-toggle="tab" data-bs-target="#feature-{{ $featureIndex }}-locale-{{ $locale }}"
                                                        type="button" role="tab" aria-controls="feature-{{ $featureIndex }}-locale-{{ $locale }}" aria-selected="@if ($locale === config('app.locale')) true @else false @endif">
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
                                            ]) id="feature-{{ $featureIndex }}-locale-{{ $locale }}" role="tabpanel" aria-labelledby="feature-{{ $featureIndex }}-locale-{{ $locale }}-tab">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <x-form.input :label="__('form.title')" name="features[{{ $featureIndex }}][translations][{{ $locale }}][title]"
                                                            value="{{ old('features.' . $featureIndex . '.translations.' . $locale . '.title', optional($feature->translations->where('locale', $locale)->first())->title ?? '') }}" />
                                                    </div>
                                                    <div class="col-md-12">
                                                        <x-form.textarea :label="__('form.description')" name="features[{{ $featureIndex }}][translations][{{ $locale }}][description]" :value="old('features.' . $featureIndex . '.translations.' . $locale . '.description', optional($feature->translations->where('locale', $locale)->first())->description ?? '')" rows="3" />
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <hr class="my-3">
                                    <h6>Feature Details</h6>
                                    <div class="feature-details-container" data-feature-index="{{ $featureIndex }}">
                                        @php
                                            $existingDetails = old('features.' . $featureIndex . '.details', $feature->details ?? []);
                                            $detailIndex = 0;
                                        @endphp
                                        @if (count($existingDetails) > 0)
                                            @foreach ($existingDetails as $detail)
                                                <div class="detail-item border rounded p-3 mb-2" data-detail-index="{{ $detailIndex }}">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <strong>Detail #<span class="detail-number">{{ $detailIndex + 1 }}</span></strong>
                                                        <button type="button" class="btn btn-sm btn-outline-danger remove-detail">
                                                            &nbsp;<i class="fa-solid fa-times fa-fw"></i>&nbsp;
                                                        </button>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <x-form.icon :label="__('form.icon')" name="features[{{ $featureIndex }}][details][{{ $detailIndex }}][icon]" value="{{ $detail->icon ?? '' }}" />
                                                        </div>
                                                        <div class="col-md-6">
                                                            <x-form.input :label="__('form.sort')" type="number" name="features[{{ $featureIndex }}][details][{{ $detailIndex }}][sort]"
                                                                value="{{ old('features.' . $featureIndex . '.details.' . $detailIndex . '.sort', $detail->sort ?? 0) }}" min="0" />
                                                        </div>
                                                    </div>
                                                    <div class="mb-3 mt-3">
                                                        <ul class="nav nav-tabs" role="tablist">
                                                            @foreach ($locales as $locale => $language)
                                                                <li class="nav-item" role="presentation">
                                                                    <button class="nav-link @if ($locale === config('app.locale')) active @endif" id="detail-{{ $featureIndex }}-{{ $detailIndex }}-locale-{{ $locale }}-tab" data-bs-toggle="tab"
                                                                        data-bs-target="#detail-{{ $featureIndex }}-{{ $detailIndex }}-locale-{{ $locale }}" type="button" role="tab"
                                                                        aria-controls="detail-{{ $featureIndex }}-{{ $detailIndex }}-locale-{{ $locale }}" aria-selected="@if ($locale === config('app.locale')) true @else false @endif">
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
                                                            ]) id="detail-{{ $featureIndex }}-{{ $detailIndex }}-locale-{{ $locale }}" role="tabpanel"
                                                                aria-labelledby="detail-{{ $featureIndex }}-{{ $detailIndex }}-locale-{{ $locale }}-tab">
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <x-form.input :label="__('form.title')" name="features[{{ $featureIndex }}][details][{{ $detailIndex }}][translations][{{ $locale }}][title]"
                                                                            value="{{ old('features.' . $featureIndex . '.details.' . $detailIndex . '.translations.' . $locale . '.title', optional($detail->translations->where('locale', $locale)->first())->title ?? '') }}" />
                                                                    </div>
                                                                    <div class="col-md-12">
                                                                        <x-form.textarea :label="__('form.description')" name="features[{{ $featureIndex }}][details][{{ $detailIndex }}][translations][{{ $locale }}][description]" :value="old('features.' . $featureIndex . '.details.' . $detailIndex . '.translations.' . $locale . '.description', optional($detail->translations->where('locale', $locale)->first())->description ?? '')" rows="3" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                @php $detailIndex++; @endphp
                                            @endforeach
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-outline-success rounded-pill p-2 add-detail">
                                        <i class="fa-solid fa-plus-circle"></i>
                                    </button>
                                </div>
                            </div>
                            @php $featureIndex++; @endphp
                        @endforeach
                    @endif
                </div>
                <button type="button" class="btn btn-primary" id="add-feature">
                    <i class="fa-solid fa-plus-circle me-2"></i>{{ __('form.add_feature') }}
                </button>
            </div>
        </div>
        <hr class="my-4">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <x-form.input :label="__('form.slug')" name="slug" value="{{ old('slug', $form?->slug) }}" />
            </div>
            <div class="col-md-8 offset-md-2">
                <h4 class="mt-4">{{ __('form.meta.meta_information') }} <i class="ms-2 fa-solid fa-wand-magic-sparkles cursor-pointer text-success meta-generator"></i></h4>
                <hr>
            </div>
            <div class="col-md-8 offset-md-2">
                <x-form.input :label="__('form.meta.title')" name="meta[title]" :value="old('meta.title', $form?->meta?->title)" />
            </div>
            <div class="col-md-8 offset-md-2">
                <x-form.input :label="__('form.meta.description')" name="meta[description]" :value="old('meta.description', $form?->meta?->description)" />
            </div>
            <div class="col-md-8 offset-md-2">
                <x-form.input :label="__('form.meta.keywords')" name="meta[keywords]" :value="old('meta.keywords', $form?->meta?->keywords)" placeholder="keyword1, keyword2, keyword3" />
            </div>
            <div class="col-md-8 offset-md-2 text-end mt-3">
                <button type="submit" class="btn btn-primary">{{ __('form.save') }}</button>
            </div>
        </div>
    </x-form.layout>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                let featureIndex = {{ count($form?->features ?? []) }};
                const locales = @json($locales->keys()->toArray());
                const localeNames = @json($locales->mapWithKeys(fn($lang, $key) => [$key => $lang['name']])->toArray());
                const localeFlags = @json($locales->mapWithKeys(fn($lang, $key) => [$key => asset($lang['flag'])])->toArray());
                const defaultLocale = '{{ config('app.locale') }}';

                document.getElementById('add-feature').addEventListener('click', function() {
                    const container = document.getElementById('product-features-container');
                    const featureHtml = createFeatureHtml(featureIndex);
                    container.insertAdjacentHTML('beforeend', featureHtml);
                    featureIndex++;
                    updateFeatureNumbers();
                    initializeIconPickers();
                });

                document.addEventListener('click', function(e) {
                    if (e.target.closest('.remove-feature')) {
                        if (confirm('Are you sure you want to remove this feature?')) {
                            e.target.closest('.feature-item').remove();
                            updateFeatureNumbers();
                        }
                    }
                });

                document.addEventListener('click', function(e) {
                    if (e.target.closest('.add-detail')) {
                        const featureCard = e.target.closest('.feature-item');
                        const featureIdx = featureCard.dataset.index;
                        const detailsContainer = featureCard.querySelector('.feature-details-container');
                        const detailIndex = detailsContainer.querySelectorAll('.detail-item').length;

                        const detailHtml = createDetailHtml(featureIdx, detailIndex);
                        detailsContainer.insertAdjacentHTML('beforeend', detailHtml);
                        updateDetailNumbers(detailsContainer);
                        initializeIconPickers();
                    }
                });

                document.addEventListener('click', function(e) {
                    if (e.target.closest('.remove-detail')) {
                        const detailItem = e.target.closest('.detail-item');
                        const detailsContainer = detailItem.closest('.feature-details-container');
                        detailItem.remove();
                        updateDetailNumbers(detailsContainer);
                    }
                });

                function createFeatureHtml(index) {
                    let tabButtons = '';
                    let tabPanes = '';

                    locales.forEach((locale) => {
                        const isActive = locale === defaultLocale;

                        tabButtons += `
                        <li class="nav-item" role="presentation">
                            <button class="nav-link ${isActive ? 'active' : ''}"
                                    id="feature-${index}-locale-${locale}-tab"
                                    data-bs-toggle="tab"
                                    data-bs-target="#feature-${index}-locale-${locale}"
                                    type="button" role="tab"
                                    aria-controls="feature-${index}-locale-${locale}"
                                    aria-selected="${isActive}">
                                <img src="${localeFlags[locale]}" class="lang-flag me-2">
                                <span class="fs-lg">${localeNames[locale]}</span>
                            </button>
                        </li>
                    `;

                        tabPanes += `
                        <div class="tab-pane fade ${isActive ? 'show active' : ''}"
                             id="feature-${index}-locale-${locale}"
                             role="tabpanel"
                             aria-labelledby="feature-${index}-locale-${locale}-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label">{{ __('form.title') }}</label>
                                    <input type="text"
                                           class="form-control"
                                           name="features[${index}][translations][${locale}][title]">
                                </div>
                                <div class="col-md-12 mt-3">
                                    <label class="form-label">{{ __('form.description') }}</label>
                                    <textarea class="form-control"
                                              name="features[${index}][translations][${locale}][description]"
                                              rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                    `;
                    });

                    return `
                    <div class="feature-item card mb-3" data-index="${index}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">Feature #<span class="feature-number">${index + 1}</span></h5>
                                <button type="button" class="btn btn-sm btn-outline-danger remove-feature">
                                    <i class="fa-solid fa-trash-alt fa-fw"></i> &nbsp;Remove
                                </button>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('form.icon') }}</label>
                                        <div class="input-group icon-picker-box">
                                            <select
                                                class="form-select form-select-sm icon-select2"
                                                name="features[${index}][icon]"
                                                data-initial-value=""
                                            ></select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">{{ __('form.sort') }}</label>
                                    <input type="number"
                                           class="form-control"
                                           name="features[${index}][sort]"
                                           min="0" value="0">
                                </div>
                            </div>
                            <div class="mb-3 mt-3">
                                <ul class="nav nav-tabs" role="tablist">
                                    ${tabButtons}
                                </ul>
                            </div>
                            <div class="tab-content">
                                ${tabPanes}
                            </div>
                            <hr class="my-3">
                            <h6>Feature Details</h6>
                            <div class="feature-details-container" data-feature-index="${index}"></div>
                            <button type="button" class="btn btn-primary add-detail">
                                <i class="fa-solid fa-plus-circle"></i> &nbsp;{{ __('form.add_detail') }}
                            </button>
                        </div>
                    </div>
                `;
                }

                function createDetailHtml(featureIdx, detailIdx) {
                    let tabButtons = '';
                    let tabPanes = '';

                    locales.forEach((locale) => {
                        const isActive = locale === defaultLocale;

                        tabButtons += `
                        <li class="nav-item" role="presentation">
                            <button class="nav-link ${isActive ? 'active' : ''}"
                                    id="detail-${featureIdx}-${detailIdx}-locale-${locale}-tab"
                                    data-bs-toggle="tab"
                                    data-bs-target="#detail-${featureIdx}-${detailIdx}-locale-${locale}"
                                    type="button" role="tab"
                                    aria-controls="detail-${featureIdx}-${detailIdx}-locale-${locale}"
                                    aria-selected="${isActive}">
                                <img src="${localeFlags[locale]}" class="lang-flag me-2">
                                <span class="fs-lg">${localeNames[locale]}</span>
                            </button>
                        </li>
                    `;

                        tabPanes += `
                        <div class="tab-pane fade ${isActive ? 'show active' : ''}"
                             id="detail-${featureIdx}-${detailIdx}-locale-${locale}"
                             role="tabpanel"
                             aria-labelledby="detail-${featureIdx}-${detailIdx}-locale-${locale}-tab">
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label">{{ __('form.title') }}</label>
                                    <input type="text"
                                           class="form-control"
                                           name="features[${featureIdx}][details][${detailIdx}][translations][${locale}][title]">
                                </div>
                                <div class="col-md-12 mt-3">
                                    <label class="form-label">{{ __('form.description') }}</label>
                                    <textarea class="form-control"
                                              name="features[${featureIdx}][details][${detailIdx}][translations][${locale}][description]"
                                              rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                    `;
                    });

                    return `
                    <div class="detail-item border rounded p-3 mb-2" data-detail-index="${detailIdx}">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <strong>Detail #<span class="detail-number">${detailIdx + 1}</span></strong>
                            <button type="button" class="btn btn-sm btn-outline-danger remove-detail">
                                &nbsp;<i class="fa-solid fa-times fa-fw"></i>&nbsp;
                            </button>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">{{ __('form.icon') }}</label>
                                    <div class="input-group icon-picker-box">
                                        <select
                                            class="form-select form-select-sm icon-select2"
                                            name="features[${featureIdx}][details][${detailIdx}][icon]"
                                            data-initial-value=""
                                        ></select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">{{ __('form.sort') }}</label>
                                <input type="number"
                                       class="form-control"
                                       name="features[${featureIdx}][details][${detailIdx}][sort]"
                                       min="0" value="0">
                            </div>
                        </div>
                        <div class="mb-3 mt-3">
                            <ul class="nav nav-tabs" role="tablist">
                                ${tabButtons}
                            </ul>
                        </div>
                        <div class="tab-content">
                            ${tabPanes}
                        </div>
                    </div>
                `;
                }

                function updateFeatureNumbers() {
                    document.querySelectorAll('.feature-item').forEach((item, index) => {
                        item.dataset.index = index;
                        item.querySelector('.feature-number').textContent = index + 1;

                        item.querySelectorAll('input, textarea, select').forEach(input => {
                            const name = input.getAttribute('name');
                            if (name && name.startsWith('features[')) {
                                const newName = name.replace(/features\[\d+]/, `features[${index}]`);
                                input.setAttribute('name', newName);
                            }
                        });

                        const detailsContainer = item.querySelector('.feature-details-container');
                        if (detailsContainer) {
                            detailsContainer.dataset.featureIndex = index;
                            updateDetailNumbers(detailsContainer);
                        }
                    });
                }

                function updateDetailNumbers(container) {
                    container.querySelectorAll('.detail-item').forEach((item, index) => {
                        item.dataset.detailIndex = index;
                        item.querySelector('.detail-number').textContent = index + 1;

                        item.querySelectorAll('input, textarea, select').forEach(input => {
                            const name = input.getAttribute('name');
                            if (name && name.includes('[details][')) {
                                const newName = name.replace(/\[details]\[\d+]/, `[details][${index}]`);
                                input.setAttribute('name', newName);
                            }
                        });
                    });
                }

                function initializeIconPickers() {
                    if (typeof window.initIconPicker === 'function') {
                        window.initIconPicker();
                    }
                }

                initializeIconPickers();
            });
        </script>
    @endpush

</x-app-layout>
