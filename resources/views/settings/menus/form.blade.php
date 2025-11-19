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
                <x-form.select :label="__('form.parent_menu')" name="parent_id">
                    <option value="">{{ __('form.select') }}</option>
                    @foreach ($menus as $menu)
                        @if (isset($form) && $form->id === $menu->id)
                            @continue
                        @endif
                        @php
                            $menuName =
                                $menu->translations->where('locale', app()->getLocale())->first()?->name ??
                                ($menu->translations->where('locale', 'en')->first()?->name ?? 'N/A');
                        @endphp
                        <option value="{{ $menu->id }}" @selected(old('parent_id', $form?->parent_id) == $menu->id)>
                            {{ $menuName }}
                        </option>
                    @endforeach
                </x-form.select>
            </div>
        </div>

        <hr class="my-4">

        <div class="mb-3">
            <ul class="nav nav-tabs" role="tablist">
                @foreach ($locales as $locale => $language)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link @if ($locale === config('app.locale')) active @endif"
                            id="locale-{{ $locale }}-tab" data-bs-toggle="tab"
                            data-bs-target="#locale-{{ $locale }}" type="button" role="tab"
                            aria-controls="locale-{{ $locale }}"
                            aria-selected="@if ($locale === config('app.locale')) true @else false @endif">
                            <img src="{{ asset($language['flag']) }}" class="lang-flag me-2"><span
                                class="fs-lg">{{ $language['name'] }}</span>
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
                ]) id="locale-{{ $locale }}" role="tabpanel"
                    aria-labelledby="locale-{{ $locale }}-tab">
                    <div class="row">
                        <div class="col-md-8 offset-md-2">
                            <x-form.input :label="__('form.name')" name="name[{{ $locale }}]"
                                value="{{ old('name.' . $locale, $translations[$locale]['name'] ?? '') }}" required />
                        </div>
                        <div class="col-md-8 offset-md-2">
                            <x-form.textarea :label="__('form.description')" name="description[{{ $locale }}]"
                                value="{{ old('description.' . $locale, $translations[$locale]['description'] ?? '') }}"
                                rows="3" />
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Permissions for this menu (add/edit) --}}
        <div class="mt-4 col-md-8 offset-md-2">
            <h5 class="mb-3">{{ __('form.permissions') }}</h5>

            <div id="menu-permissions-list">
                @if (isset($form) && $form->permissions->count())
                    @foreach ($form->permissions as $perm)
                        @php
                            $permName =
                                $perm->translations->where('locale', app()->getLocale())->first()?->name ??
                                ($perm->translations->where('locale', 'en')->first()?->name ??
                                    ($perm->action ?? ($perm->slug ?? 'Permission')));
                            $action_route = explode('.', $perm->action_route ?? '');
                            $action_suffix = count($action_route) > 1 ? $action_route[count($action_route) - 1] : '';
                            $action_prefix =
                                count($action_route) > 1 ? implode('.', array_slice($action_route, 0, -1)) : '';
                        @endphp
                        <div class="permission-row mb-2 d-flex gap-2 align-items-start" draggable="true">
                            <input type="hidden" name="permissions_existing[][id]" value="{{ $perm->id }}">
                            <input type="hidden" name="permissions_existing[][sort]" class="perm-sort-input"
                                value="{{ $perm->sort ?? 0 }}">
                            <span class="drag-handle btn btn-sm btn-light me-2 py-2" title="Move">
                                <i class="fa-regular fa-arrow-down-1-9"></i>
                            </span>
                            <div class="flex-grow-1">
                                <div class="row w-100 g-2 align-items-start">
                                    <div class="col-auto d-flex align-items-start">
                                        <div class="me-1">
                                            <x-form.icon name="permissions_existing[][icon]" class="text-muted"
                                                :text="false" value="{{ $perm->icon ?? '' }}" />
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="input-group input-group-sm">
                                            <span
                                                class="input-group-text perm-action-prefix">{{ $action_prefix ?? '' }}</span>
                                            <input type="text"
                                                class="form-control form-control-sm perm-action-visible"
                                                value="{{ $action_suffix }}" placeholder="Permission Route Action">
                                            <input type="hidden" name="permissions_existing[][action]"
                                                class="perm-action-hidden" value="{{ $action_suffix ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="permissions_existing[][slug]"
                                            class="form-control form-control-sm" value="{{ $perm->slug ?? '' }}"
                                            placeholder="slug/action">
                                    </div>
                                </div>
                                <div class="mt-1">
                                    <ul class="nav nav-tabs" role="tablist">
                                        @foreach ($locales as $lkey => $ll)
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link @if ($loop->first) active @endif"
                                                    id="perm-{{ $perm->id }}-tab-{{ $lkey }}"
                                                    data-bs-toggle="tab"
                                                    data-bs-target="#perm-{{ $perm->id }}-panel-{{ $lkey }}"
                                                    type="button" role="tab"
                                                    aria-controls="perm-{{ $perm->id }}-panel-{{ $lkey }}"
                                                    aria-selected="@if ($loop->first) true @else false @endif">{{ $ll['name'] }}</button>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="tab-content p-2 border rounded-bottom">
                                        @foreach ($locales as $lkey => $ll)
                                            @php $t = $perm->translations->where('locale', $lkey)->first(); @endphp
                                            <div class="tab-pane fade @if ($loop->first) show active @endif"
                                                id="perm-{{ $perm->id }}-panel-{{ $lkey }}"
                                                role="tabpanel"
                                                aria-labelledby="perm-{{ $perm->id }}-tab-{{ $lkey }}">
                                                <input type="text"
                                                    name="permissions_existing[][translations][{{ $lkey }}]"
                                                    class="form-control form-control-sm"
                                                    value="{{ $t?->name ?? '' }}"
                                                    placeholder="{{ $ll['name'] }} name">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-danger btn-perm-remove">&times;</button>
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="mt-2">
                <button type="button" class="btn btn-sm btn-outline-primary"
                    id="add-permission-btn">{{ __('form.add_permission') ?? 'Add permission' }}</button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 offset-md-2 text-end mt-3">
                <button type="submit" class="btn btn-primary">{{ __('form.save') }}</button>
            </div>
        </div>
    </x-form.layout>
    @pushOnce('scripts')
        <script>
            jQuery(function($) {
                var $list = $('#menu-permissions-list');
                var $addBtn = $('#add-permission-btn');

                if (!$list.length) {
                    console.warn('Menu permissions list container not found');
                    return;
                }

                // locales data from server: { "en": { name: "English", flag: "..." }, "km": { ... } }
                var localesData = {!! json_encode($locales->toArray()) !!};

                // helper to compute a prefix from the route input.
                function getRoutePrefix() {
                    var route = $('input[name="route"]').val() || '';
                    route = route.trim();
                    if (!route) return '';
                    var parts = route.split('.');
                    if (parts.length > 1) {
                        parts.pop(); // remove last segment (e.g. '.index')
                        return parts.join('.');
                    }
                    return route;
                }

                // Prefill existing permission action inputs that are empty with the route prefix
                function prefillExistingActions() {
                    var prefix = getRoutePrefix();
                    $('#menu-permissions-list').find('.permission-row').each(function() {
                        initActionRow($(this));
                    });
                }

                function initActionRow($row) {
                    var prefix = getRoutePrefix();
                    var $hidden = $row.find('.perm-action-hidden');
                    var $visible = $row.find('.perm-action-visible');
                    var $prefixSpan = $row.find('.perm-action-prefix');

                    $prefixSpan.text(prefix ? (prefix + '.') : '');

                    var hiddenVal = ($hidden.val() || '').trim();
                    if (!hiddenVal) {
                        // if empty, set hidden to prefix + '.' to guide users, leave visible as-is (usually server-rendered label or empty)
                        if (prefix) {
                            $hidden.val(prefix + '.');
                        }
                    } else {
                        if (prefix && hiddenVal.indexOf(prefix + '.') === 0) {
                            $visible.val(hiddenVal.substring((prefix + '.').length));
                        } else {
                            // value doesn't start with prefix - keep the current visible value (often a translation shown by server)
                        }
                    }

                    // sync visible -> hidden on input
                    $visible.off('input.perm').on('input.perm', function() {
                        var v = $(this).val().trim();
                        var full = prefix ? (prefix + (v ? '.' + v : '.')) : v;
                        $hidden.val(full);
                    });
                }

                function createRow(action, slug, id, icon) {
                    action = action || '';
                    slug = slug || '';
                    // if no explicit action provided, try prefilling from route prefix
                    if (!action) {
                        var rp = getRoutePrefix();
                        if (rp) {
                            action = rp + '.';
                        }
                    }
                    var uid = 'perm-' + Date.now() + '-' + Math.floor(Math.random() * 1000);
                    var $row = $('<div>').addClass('permission-row mb-2 d-flex gap-2 align-items-start').attr(
                        'draggable', 'true');

                    if (typeof id !== 'undefined' && id !== null) {
                        $('<input>', {
                            type: 'hidden',
                            name: 'permissions_existing[][id]',
                            value: id
                        }).appendTo($row);
                        $('<input>', {
                            type: 'hidden',
                            name: 'permissions_existing[][sort]',
                            class: 'perm-sort-input',
                            value: 0
                        }).appendTo($row);
                        $('<span>').addClass('drag-handle btn btn-sm btn-light me-2 py-2').attr('title', 'Move').html(
                            '<i class="fa-regular fa-arrow-down-1-9"></i>').appendTo($row);
                    } else {
                        // new row: add new sort input and drag handle
                        $('<input>', {
                            type: 'hidden',
                            name: 'permissions_new[][sort]',
                            class: 'perm-sort-input',
                            value: 0
                        }).appendTo($row);
                        $('<span>').addClass('drag-handle btn btn-sm btn-light me-2 py-2').attr('title', 'Move').html(
                            '<i class="fa-regular fa-arrow-down-1-9"></i>').appendTo($row);
                    }

                    var isExisting = (typeof id !== 'undefined' && id !== null);
                    var actionName = isExisting ? 'permissions_existing[][action]' : 'permissions_new[][action]';
                    var slugName = isExisting ? 'permissions_existing[][slug]' : 'permissions_new[][slug]';

                    // build row columns: icon (col-auto), action (col), slug (col-md-3)
                    var $rowBody = $('<div>').addClass('row w-100 g-2 align-items-start');

                    var $colIcon = $('<div>').addClass('col-auto d-flex align-items-start');

                    // build icon picker box similar to the x-form.icon component so new rows support Select2 icon picker
                    var $iconPickerBox = $('<div>').addClass('input-group icon-picker-box input-group-sm me-1').css(
                        'min-width', '160px');
                    var selectName = isExisting ? 'permissions_existing[][icon]' : 'permissions_new[][icon]';
                    var $select = $('<select>', {
                        'class': 'form-select form-select-sm icon-select2',
                        'name': selectName,
                        'data-initial-value': icon || ''
                    }).css('min-width', '160px');

                    $iconPickerBox.append($select);
                    $colIcon.append($iconPickerBox);

                    var $colAction = $('<div>').addClass('col');
                    var $actionGroup = $('<div>').addClass('input-group input-group-sm');
                    var $prefix = $('<span>').addClass('input-group-text perm-action-prefix').text('');
                    var $visible = $('<input>', {
                        type: 'text',
                        class: 'form-control form-control-sm perm-action-visible',
                        placeholder: 'Permission Route Action',
                        value: action
                    });
                    var $hidden = $('<input>', {
                        type: 'hidden',
                        name: actionName,
                        class: 'perm-action-hidden',
                        value: action
                    });
                    $actionGroup.append($prefix).append($visible).append($hidden);
                    $colAction.append($actionGroup);

                    var $colSlug = $('<div>').addClass('col-md-3');
                    $('<input>', {
                        type: 'text',
                        name: slugName,
                        placeholder: 'slug/action',
                        value: slug
                    }).addClass('form-control form-control-sm').appendTo($colSlug);

                    $rowBody.append($colIcon).append($colAction).append($colSlug);
                    var $flex = $('<div>').addClass('flex-grow-1').append($rowBody);

                    // build language tabs
                    var $tabsWrap = $('<div>').addClass('mt-1');
                    var $nav = $('<ul>').addClass('nav nav-tabs').attr('role', 'tablist');
                    var $tabContent = $('<div>').addClass('tab-content p-2 border rounded-bottom');
                    var first = true;

                    Object.keys(localesData).forEach(function(lkey) {
                        var ll = localesData[lkey];
                        var tabId = uid + '-tab-' + lkey;
                        var panelId = uid + '-panel-' + lkey;

                        var $li = $('<li>').addClass('nav-item').attr('role', 'presentation');
                        var $btn = $('<button>')
                            .addClass('nav-link' + (first ? ' active' : ''))
                            .attr({
                                id: tabId,
                                'data-bs-toggle': 'tab',
                                'data-bs-target': '#' + panelId,
                                type: 'button',
                                role: 'tab',
                                'aria-controls': panelId,
                                'aria-selected': first ? 'true' : 'false'
                            })
                            .text(ll.name);

                        $li.append($btn);
                        $nav.append($li);

                        var inputName = isExisting ? ('permissions_existing[][translations][' + lkey + ']') : (
                            'permissions_new[][translations][' + lkey + ']');
                        var $pane = $('<div>')
                            .addClass('tab-pane fade' + (first ? ' show active' : ''))
                            .attr({
                                id: panelId,
                                role: 'tabpanel',
                                'aria-labelledby': tabId
                            });

                        $('<input>', {
                            type: 'text',
                            name: inputName,
                            class: 'form-control form-control-sm',
                            placeholder: ll.name + ' name'
                        }).appendTo($pane);

                        $tabContent.append($pane);
                        first = false;
                    });

                    $tabsWrap.append($nav).append($tabContent);
                    $flex.append($tabsWrap);
                    $row.append($flex);

                    $('<button>', {
                        type: 'button'
                    }).addClass('btn btn-sm btn-danger btn-perm-remove').html('&times;').appendTo($row);

                    return $row;
                }

                $(document).on('click', '#add-permission-btn', function(e) {
                    e.preventDefault();
                    var $newRow = createRow('', '', null, '');
                    $list.append($newRow);
                    // initialize action prefix UI for the new row
                    initActionRow($newRow);
                    // bind drag handlers for the new row
                    bindDragHandlers($newRow);
                    // initialize icon picker for the new row (Select2)
                    if (typeof window.initIconPicker === 'function') {
                        window.initIconPicker();
                    }
                    // focus first input (action/name)
                    $newRow.find('.perm-action-visible').first().focus();
                });

                // Note: preview UI removed; no preview update logic required

                // Open icon picker if available; otherwise focus input
                $(document).on('click', '#open-icon-picker', function() {
                    var $input = $('#icon-input');
                    if (typeof window.openIconPicker === 'function') {
                        try {
                            window.openIconPicker($input);
                        } catch (e) {
                            $input.focus();
                        }
                    } else {
                        $input.focus();
                    }
                });

                // run once on load to prefill any existing empty action fields
                prefillExistingActions();

                // DRAG & DROP ordering (native HTML5)
                var draggedRow = null;

                function onDragStart(e) {
                    draggedRow = this;
                    e.originalEvent.dataTransfer.effectAllowed = 'move';
                    e.originalEvent.dataTransfer.setData('text/plain', 'drag');
                    $(this).addClass('dragging');
                }

                function onDragEnd(e) {
                    $(this).removeClass('dragging');
                    draggedRow = null;
                    updateOrderInputs();
                }

                function onDragOver(e) {
                    e.preventDefault();
                    e.originalEvent.dataTransfer.dropEffect = 'move';
                    var $target = $(this);
                    if (!draggedRow || this === draggedRow) return;
                    var rect = this.getBoundingClientRect();
                    var offset = e.originalEvent.clientY - rect.top;
                    if (offset > rect.height / 2) {
                        $target.after(draggedRow);
                    } else {
                        $target.before(draggedRow);
                    }
                }

                function updateOrderInputs() {
                    $('#menu-permissions-list').find('.permission-row').each(function(index) {
                        var $row = $(this);
                        $row.find('.perm-sort-input').val(index);
                    });
                }

                function bindDragHandlers($container) {
                    $container.find('.permission-row').each(function() {
                        var el = this;
                        $(el).off('dragstart.drag drop.drag dragover.drag dragend.drag');
                        $(el).on('dragstart.drag', onDragStart);
                        $(el).on('dragend.drag', onDragEnd);
                        $(el).on('dragover.drag', onDragOver);
                    });
                    // set initial order
                    updateOrderInputs();
                }

                // bind handlers initially and whenever rows are added/removed
                bindDragHandlers($(document));

                // initialize icon picker for any existing permission rows (if component rendered selects)
                if (typeof window.initIconPicker === 'function') {
                    window.initIconPicker();
                }

                // update empty action inputs when route changes
                $(document).on('input', 'input[name="route"]', function() {
                    prefillExistingActions();
                });

                $(document).on('click', '.btn-perm-remove', function(e) {
                    e.preventDefault();
                    $(this).closest('.permission-row').remove();
                });
            });
        </script>
    @endpushOnce
</x-app-layout>