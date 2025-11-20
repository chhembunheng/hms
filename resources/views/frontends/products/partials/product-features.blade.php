<x-app-layout>
    <x-form.layout :form="$form">

        <h4 class="mt-4">{{ __('Product Features') }}</h4>
        <hr>

        <div class="accordion" id="featuresAccordion">
            @foreach(old('features', $form->features ?? []) as $fIndex => $feature)
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
        <button class="btn btn-primary">{{ __('form.save') }}</button>

    </x-form.layout>

    @push('scripts')
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

    @include('frontends.products.partials.templates.feature-template')
    @include('frontends.products.partials.templates.detail-template')

    <script>
        let featureIndex = {{ count(old('features', $form->features ?? [])) }};
        const defaultLocale = "{{ config('app.locale') }}";

        $('#addFeatureBtn').on('click', function () {
            let tpl = $('#featureTemplate').html();
            tpl = tpl.replace(/__FEATURE_INDEX__/g, featureIndex);
            $('#featuresAccordion').append(tpl);
            initDetailSortable(featureIndex);
            if (window.initIconPicker) window.initIconPicker();
            featureIndex++;
            refreshFeatureAccordion();
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
