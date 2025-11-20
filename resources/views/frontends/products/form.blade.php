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

        <h4 class="mt-4">{{ __('Product Features') }}</h4>
        <hr>

        <div class="accordion" id="featuresAccordion">
            @foreach(old('features', $features ?? []) as $fIndex => $feature)
                @include('frontends.products.partials.feature', [
                    'index' => $fIndex,
                    'feature' => $feature,
                    'locales' => $locales
                ])
            @endforeach
        </div>

        <button type="button" class="btn btn-outline-primary mt-3" id="addFeatureBtn">
            + {{ __('Add Feature') }}
        </button>

        <hr>

        <x-generate-seo :form="$form" />
    </x-form.layout>

    @push('scripts')
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

    @include('frontends.products.partials.templates.feature-template')
    @include('frontends.products.partials.templates.detail-template')

    <script>
        let featureIndex = {{ count(old('features', $features ?? [])) }};
        const defaultLocale = "{{ config('app.locale') }}";

        $('#addFeatureBtn').on('click', function () {
            let tpl = $('#featureTemplate').html();
            tpl = tpl.replace(/__FEATURE_INDEX__/g, featureIndex);
            $('#featuresAccordion').append(tpl);
            initDetailSortable(featureIndex);
            if (window.initIconPicker) window.initIconPicker();
            featureIndex++;
            reindexFeatures();
        });

        $(document).on('click', '.remove-feature', function () {
            $(this).closest('.feature-item').remove();
            reindexFeatures();
        });

        $(document).on('click', '.add-detail', function () {
            let featureIdx = $(this).data('feature');
            let container = $('#detailsAccordion_' + featureIdx);
            let detailIndex = container.children('.detail-item').length;
            let tpl = $('#detailTemplate').html();
            tpl = tpl.replace(/__FEATURE_INDEX__/g, featureIdx)
                     .replace(/__DETAIL_INDEX__/g, detailIndex);
            container.append(tpl);
            // initSingleDetailAccordion(featureIdx, detailIndex);
            initDetailSortable(featureIdx);
            if (window.initIconPicker) window.initIconPicker();
            reindexDetails(featureIdx);
        });

        $(document).on('click', '.remove-detail', function () {
            let featureIdx = $(this).data('feature');
            $(this).closest('.detail-item').remove();
            reindexDetails(featureIdx);
        });

        $("#featuresAccordion").sortable({
            handle: ".drag-handle-feature",
            update: function () {
                reindexFeatures();
            }
        });

        function initDetailSortable(featureIdx) {
            $("#detailsAccordion_" + featureIdx).sortable({
                handle: ".drag-handle-detail",
                update: function () {
                    reindexDetails(featureIdx);
                }
            });
        }

        function refreshFeatureAccordion() {}

        function reindexFeatures() {
            $('#featuresAccordion .feature-item').each(function (newIndex) {
                $(this).attr('data-feature-index', newIndex);
                $(this).find('.feature-title').text("Feature #" + (newIndex + 1));

                $(this).find('input, textarea, select').each(function () {
                    let name = $(this).attr('name');
                    if (!name) return;
                    name = name.replace(/features\[\d+]/, 'features['+newIndex+']');
                    $(this).attr('name', name);
                });

                reindexDetails(newIndex);
            });
        }

        function reindexDetails(featureIdx) {
            const container = $('#detailsAccordion_'+featureIdx);

            container.children('.detail-item').each(function (dIndex) {
                $(this).find('.detail-title').text("Detail #" + (dIndex + 1));

                $(this).find('input, textarea, select').each(function () {
                    let name = $(this).attr('name');
                    if (!name) return;
                    name = name.replace(/details\]\[\d+]/, 'details]['+dIndex+']');
                    $(this).attr('name', name);
                });
            });
        }
    </script>
    @endpush
</x-app-layout>
