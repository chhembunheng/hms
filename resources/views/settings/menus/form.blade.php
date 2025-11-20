<x-app-layout>
    <x-form.layout :form="$form">

        <div class="row">
            <div class="col-md-6">
                <x-form.icon :label="__('form.icon')" name="icon" value="{{ old('icon', $form?->icon) }}" :text="false" />
            </div>

            <div class="col-md-6">
                <x-form.input :label="__('form.route')" name="route" value="{{ old('route', $form?->route) }}"
                    placeholder="dashboard.index" />
            </div>

            <div class="col-md-6">
                <x-form.input :label="__('form.order')" name="sort" type="number"
                    value="{{ old('sort', $form?->sort ?? 0) }}" min="0" />
            </div>

            <div class="col-md-6">
                <x-form.select :label="__('form.parent_menu')" name="parent_id" :options="$menus" :selected="$form?->parent_id" />
            </div>
        </div>

        <hr class="my-4">

        <div class="mb-3">
            <ul class="nav nav-tabs">
                @foreach ($locales as $locale => $language)
                    <li class="nav-item">
                        <button type="button" class="nav-link @if ($locale === app()->getLocale()) active @endif"
                            data-bs-toggle="tab" data-bs-target="#locale-{{ $locale }}">
                            <img src="{{ asset($language['flag']) }}" class="lang-flag me-2">
                            {{ $language['name'] }}
                        </button>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="tab-content">
            @foreach ($locales as $locale => $lng)
                <div id="locale-{{ $locale }}"
                    class="tab-pane fade @if ($locale === app()->getLocale()) show active @endif">

                    <div class="col-md-8 offset-md-2 my-2">
                        <x-form.input :label="__('form.name')" name="name[{{ $locale }}]"
                            value="{{ old('name.' . $locale, $translations[$locale]['name'] ?? '') }}" required />
                    </div>

                    <div class="col-md-8 offset-md-2 my-2">
                        <x-form.textarea :label="__('form.description')" name="description[{{ $locale }}]"
                            value="{{ old('description.' . $locale, $translations[$locale]['description'] ?? '') }}"
                            rows="3" />
                    </div>

                </div>
            @endforeach
        </div>

        <hr class="my-4">

        <div class="col-md-8 offset-md-2">
            <h4>{{ __('form.permissions') }}</h4>

            <div id="menu-permissions-list">

                {{-- EXISTING PERMISSIONS --}}
                @foreach ($form->permissions ?? [] as $perm)
                    @php
                        $action_route = $perm->action_route ?? '';
                        $parts = explode('.', $action_route);
                        $suffix = array_pop($parts);
                        $prefix = implode('.', $parts);
                    @endphp

                    <div class="permission-row d-flex align-items-start gap-2 my-2" draggable="true">

                        <input type="hidden" name="permissions_existing[{{ $perm->id }}][id]"
                            value="{{ $perm->id }}">
                        <input type="hidden" name="permissions_existing[{{ $perm->id }}][sort]"
                            class="perm-sort-input" value="{{ $perm->sort }}">

                        <span class="drag-handle btn btn-light btn-sm">
                            <i class="fa-solid fa-grip-lines"></i>
                        </span>

                        <div class="flex-grow-1">

                            <div class="row g-2">

                                <div class="col-auto">
                                    <x-form.icon name="permissions_existing[{{ $perm->id }}][icon]"
                                        :text="false" value="{{ $perm->icon }}" />
                                </div>

                                <div class="col">
                                    <div class="input-group input-group-sm">
                                        <span
                                            class="input-group-text perm-action-prefix">{{ $prefix ? $prefix . '.' : '' }}</span>

                                        <input type="text" class="form-control perm-action-visible"
                                            value="{{ $suffix }}" placeholder="edit">

                                        <input type="hidden"
                                            name="permissions_existing[{{ $perm->id }}][action_route]"
                                            class="perm-action-hidden" value="{{ $action_route }}">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <input type="text" name="permissions_existing[{{ $perm->id }}][slug]"
                                        class="form-control form-control-sm" value="{{ $perm->slug }}"
                                        placeholder="slug-auto" />
                                </div>

                            </div>

                            {{-- Translation Tabs --}}
                            <ul class="nav nav-tabs my-2">
                                @foreach ($locales as $lc => $lng)
                                    <li class="nav-item">
                                        <button type="button"
                                            class="nav-link @if ($loop->first) active @endif"
                                            data-bs-toggle="tab"
                                            data-bs-target="#p-{{ $perm->id }}-{{ $lc }}">
                                            {{ $lng['name'] }}
                                        </button>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="tab-content border rounded p-2">
                                @foreach ($locales as $lc => $lng)
                                    @php
                                        $t = $perm->translations->where('locale', $lc)->first();
                                    @endphp
                                    <div id="p-{{ $perm->id }}-{{ $lc }}"
                                        class="tab-pane fade @if ($loop->first) show active @endif">
                                        <input type="text" class="form-control form-control-sm"
                                            name="permissions_existing[{{ $perm->id }}][translations][{{ $lc }}]"
                                            value="{{ $t?->name }}">
                                    </div>
                                @endforeach
                            </div>

                        </div>

                        <button type="button" class="btn btn-danger btn-sm btn-perm-remove">&times;</button>

                    </div>
                @endforeach

            </div>

            <button type="button" class="btn btn-outline-primary btn-sm mt-2" id="add-permission-btn">
                + {{ __('form.add_permission') }}
            </button>

        </div>

        <div class="col-md-8 offset-md-2 text-end mt-3">
            <button class="btn btn-primary">{{ __('form.save') }}</button>
        </div>

    </x-form.layout>

    @pushOnce('scripts')
        <script>
            jQuery(function($) {

                const locales = @json($locales);

                function routePrefix() {
                    let r = $('input[name="route"]').val() || '';
                    let parts = r.split('.');
                    if (parts.length > 1) {
                        parts.pop();
                        return parts.join('.');
                    }
                    return r;
                }

                function initRow($row) {
                    let prefix = routePrefix();
                    let $hidden = $row.find('.perm-action-hidden');
                    let $visible = $row.find('.perm-action-visible');
                    let $prefix = $row.find('.perm-action-prefix');

                    $prefix.text(prefix ? prefix + '.' : '');

                    let full = $hidden.val().trim();

                    if (!full && prefix) {
                        $hidden.val(prefix + '.');
                    }

                    if (full.startsWith(prefix + '.')) {
                        $visible.val(full.substring(prefix.length + 1));
                    }

                    $visible.off('input').on('input', function() {
                        let s = $(this).val().trim();
                        let full = prefix ? prefix + '.' + s : s;
                        $hidden.val(full);
                    });
                }

                function createRow() {
                    let prefix = routePrefix();
                    let uid = 'n_' + Date.now();

                    let html = `
                <div class="permission-row d-flex align-items-start gap-2 my-2" draggable="true">

                    <input type="hidden" name="permissions_new[${uid}][sort]" class="perm-sort-input" value="0">

                    <span class="drag-handle btn btn-light btn-sm">
                        <i class="fa-solid fa-grip-lines"></i>
                    </span>

                    <div class="flex-grow-1">

                        <div class="row g-2">

                            <div class="col-auto">
                                <div class="input-group icon-picker-box input-group-sm" style="min-width:160px;">
                                    <select name="permissions_new[${uid}][icon]" class="form-select form-select-sm icon-select2" style="min-width:160px;"></select>
                                </div>
                            </div>

                            <div class="col">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text perm-action-prefix">${prefix ? prefix + '.' : ''}</span>
                                    <input class="form-control perm-action-visible" placeholder="edit">
                                    <input type="hidden" class="perm-action-hidden"
                                           name="permissions_new[${uid}][action_route]"
                                           value="${prefix ? prefix + '.' : ''}">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <input type="text" name="permissions_new[${uid}][slug]"
                                       class="form-control form-control-sm" placeholder="auto">
                            </div>

                        </div>

                        <ul class="nav nav-tabs my-2">`;

                    let first = true;
                    Object.keys(locales).forEach(lc => {
                        html += `
                    <li class="nav-item">
                        <button type="button" class="nav-link ${first ? 'active' : ''}"
                                data-bs-toggle="tab"
                                data-bs-target="#new-${uid}-${lc}">
                            ${locales[lc].name}
                        </button>
                    </li>
                `;
                        first = false;
                    });

                    html += `</ul>
                     <div class="tab-content border rounded p-2">`;

                    first = true;
                    Object.keys(locales).forEach(lc => {
                        html += `
                    <div id="new-${uid}-${lc}" class="tab-pane fade ${first ? 'show active' : ''}">
                        <input type="text" name="permissions_new[${uid}][translations][${lc}]"
                               class="form-control form-control-sm">
                    </div>
                `;
                        first = false;
                    });

                    html += `</div></div>

                    <button type="button" class="btn btn-danger btn-sm btn-perm-remove">&times;</button>

                </div>
            `;

                    let $row = $(html);
                    $('#menu-permissions-list').append($row);
                    initRow($row);

                    if (typeof window.initIconPicker === 'function') {
                        window.initIconPicker();
                    }

                    return $row;
                }

                $('#add-permission-btn').on('click', function() {
                    let $row = createRow();
                    $row.find('.perm-action-visible').focus();
                    updateSort();
                });

                $(document).on('click', '.btn-perm-remove', function() {
                    $(this).closest('.permission-row').remove();
                    updateSort();
                });

                function updateSort() {
                    $('#menu-permissions-list .permission-row').each(function(i) {
                        $(this).find('.perm-sort-input').val(i);
                    });
                }

                $('#menu-permissions-list .permission-row').each(function() {
                    initRow($(this));
                });

                $(document).on('input', 'input[name="route"]', function() {
                    $('#menu-permissions-list .permission-row').each(function() {
                        initRow($(this));
                    });
                });

            });
        </script>
    @endpushOnce
</x-app-layout>
