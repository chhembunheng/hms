@props(['label', 'required' => $attributes->has('required') && $attributes->get('required') === 'required', 'value' => null, 'text' => true, 'id' => 'icon-select-' . \Illuminate\Support\Str::uuid()])
@php
    $faRootClasses = [
        'fa-solid',
        'fa-regular',
        'fa-light',
        'fa-thin',
        'fa-duotone',
        'fa-brands',
        'fa-sharp fa-solid',
        'fa-sharp fa-regular',
        'fa-sharp fa-light',
        'fa-sharp fa-thin',
    ];
    if ($value) {
        $isValid = false;
        foreach ($faRootClasses as $rootClass) {
            if (str_starts_with($value, $rootClass . ' ')) {
                $isValid = true;
                break;
            }
        }
        if (!$isValid) {
            $value = 'fa-solid ' . $value;
        }
    }
@endphp
<div class="mb-3">
    @isset($label)
        <label class="form-label @if ($required) required @endif">{{ $label }}</label>
    @endisset
    <div class="input-group icon-picker-box input-group-sm" style="min-width:160px;">
        <select id="{{ $id }}" style="min-width:160px;"
            {{ $attributes->merge(['class' => 'form-select form-select-sm icon-select2']) }}
            @if ($required) required @endif data-initial-value="{{ $value }}">
            @if ($value)
                <option value="{{ $value }}" selected>{{ $value }}</option>
            @endif
        </select>
    </div>
</div>

@pushOnce('scripts')
    <script>
        const showText = @json($text);
        window.__iconPickerData = window.__iconPickerData || {
            loaded: false,
            loadingPromise: null,
            icons: []
        };

        function loadIconJsonOnce() {
            if (window.__iconPickerData.loaded) {
                return Promise.resolve(window.__iconPickerData.icons);
            }
            if (window.__iconPickerData.loadingPromise) {
                return window.__iconPickerData.loadingPromise;
            }

            const url = "{{ asset('assets/js/fontawesome-icons-merged.json') }}";

            window.__iconPickerData.loadingPromise = fetch(url)
                .then(function(res) {
                    return res.json();
                })
                .then(function(data) {
                    const rawIcons = Array.isArray(data.icons) ? data.icons : [];

                    const mapped = rawIcons.map(function(item) {
                        const cls = item.class || '';
                        const label = item.label || '';
                        const keywords = Array.isArray(item.keywords) ? item.keywords : [];

                        const text = (label ? label : cls) + (cls ? ' (' + cls + ')' : '');

                        return {
                            id: cls,
                            text: text,
                            class: cls,
                            label: label,
                            keywords: keywords
                        };
                    });

                    // store and return the full list to Select2 so search works across all icons
                    window.__iconPickerData.icons = Array.isArray(mapped) ? mapped : [];
                    window.__iconPickerData.loaded = true;
                    return window.__iconPickerData.icons;
                })
                .catch(function(err) {
                    console.error('Error loading fontawesome-icons-merged.json:', err);
                    window.__iconPickerData.icons = [];
                    window.__iconPickerData.loaded = true;
                    return [];
                });

            return window.__iconPickerData.loadingPromise;
        }

        window.initIconPicker = function() {
            if (typeof $ === 'undefined' || typeof $.fn.select2 === 'undefined') {
                console.warn('jQuery or Select2 not loaded, cannot init icon picker.');
                return;
            }

            loadIconJsonOnce().then(function(allIcons) {
                $('.icon-select2').each(function() {
                    const $select = $(this);

                    if ($select.data('select2-initialized')) {
                        return;
                    }

                    const initialValue = $select.data('initial-value');

                    let matchCount = 0;
                    let lastTerm = null;
                    const RESULT_LIMIT = 50;

                    $select.select2({
                        placeholder: 'Select an icon...',
                        allowClear: !$select.prop('required'),
                        data: allIcons,
                        templateResult: function(icon) {
                            if (!icon.id) return icon.text;
                            if (showText) return '<i class="' + icon.id +
                                ' fa-fw me-2"></i>' + icon.text;
                            return '<i class="' + icon.id + ' fa-fw"></i>';
                        },
                        templateSelection: function(icon) {
                            if (!icon.id) return icon.text || '';
                            if (showText) return '<i class="' + icon.id +
                                ' fa-fw me-2"></i>' + icon.text;
                            return '<i class="' + icon.id + ' fa-fw"></i>';
                        },
                        escapeMarkup: function(m) {
                            return m;
                        },
                        matcher: function(params, data) {
                            const term = $.trim(params.term).toLowerCase();

                            if (lastTerm !== term) {
                                matchCount = 0;
                                lastTerm = term;
                            }

                            if (matchCount >= RESULT_LIMIT) {
                                return null;
                            }

                            if (term === '') {
                                matchCount++;
                                return data;
                            }

                            const text = (data.text || '').toLowerCase();
                            const id = (data.id || '').toLowerCase();
                            const label = (data.label || '').toLowerCase();
                            const keywords = Array.isArray(data.keywords) ?
                                data.keywords.join(' ').toLowerCase() :
                                '';

                            if (
                                text.indexOf(term) > -1 ||
                                id.indexOf(term) > -1 ||
                                label.indexOf(term) > -1 ||
                                keywords.indexOf(term) > -1
                            ) {
                                matchCount++;
                                return data;
                            }
                            return null;
                        }
                    });

                    $select.data('select2-initialized', true);

                    if (initialValue) {
                        $select.val(initialValue).trigger('change');
                    }

                    $select.on('change', function() {
                        const iconClass = this.value || '';
                        const $box = $select.closest('.icon-picker-box');
                        const $preview = $box.find('.icon-preview');

                        if (iconClass) {
                            $preview.attr('class', 'icon-preview ' + iconClass + ' fa-fw');
                        } else {
                            $preview.attr('class', 'icon-preview');
                        }
                    });
                });
            });
        };

        document.addEventListener('DOMContentLoaded', function() {
            window.initIconPicker();
        });
    </script>
@endpushOnce
