@props(['label', 'required' => $attributes->has('required') && $attributes->get('required') === 'required', 'value' => null, 'id' => 'icon-select-' . \Illuminate\Support\Str::uuid()])

<div class="mb-3">
    <label class="form-label @if ($required) required @endif">{{ $label ?? '' }}</label>
    <div class="input-group icon-picker-box">
        <select id="{{ $id }}" {{ $attributes->merge(['class' => 'form-select form-select-sm icon-select2']) }} @if ($required) required @endif data-initial-value="{{ $value }}">
            @if ($value)
                <option value="{{ $value }}" selected>{{ $value }}</option>
            @endif
        </select>
    </div>
</div>

@pushOnce('scripts')
    <script>
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

                    // Limit how many icons are kept for the picker to avoid huge dropdowns.
                    const ICON_LIMIT = 50;

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

                    // Keep only the first ICON_LIMIT icons to limit displayed options.
                    window.__iconPickerData.icons = Array.isArray(mapped) ? mapped.slice(0, ICON_LIMIT) : [];
                    window.__iconPickerData.loaded = true;
                    return mapped;
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

                    $select.select2({
                        placeholder: 'Select an icon...',
                        allowClear: !$select.prop('required'),
                        data: allIcons,
                        templateResult: function(icon) {
                            if (!icon.id) return icon.text;
                            return '<i class="' + icon.id + ' fa-fw me-2"></i>' + icon.text;
                        },
                        templateSelection: function(icon) {
                            if (!icon.id) return icon.text || '';
                            return '<i class="' + icon.id + ' fa-fw me-2"></i>' + icon.text;
                        },
                        escapeMarkup: function(m) {
                            return m;
                        },
                        matcher: function(params, data) {
                            if ($.trim(params.term) === '') {
                                return data;
                            }

                            const term = params.term.toLowerCase();
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
