@php
    use App\Models\Settings\Menu;
    $locale = config('app.locale');
    $selected = isset($form) ? $form->permissions->pluck('id')->toArray() ?? [] : [];

    $menus = Menu::with(['translations', 'permissions.translations', 'children.translations', 'children.permissions.translations'])
        ->whereNull('parent_id')
        ->orderBy('sort')
        ->get();

    // Build fancytree-compatible node structure
    $treeNodes = [];
    foreach ($menus as $menu) {
        $menuTitle = optional($menu->translations->where('locale', $locale)->first())->name ?? ($menu->route ?? 'Menu');
        $menuNode = [
            'title' => $menuTitle,
            'key' => 'menu-' . $menu->id,
            'folder' => true,
            'children' => [],
        ];

        // Add permissions under menu
        foreach ($menu->permissions as $perm) {
            $permTitle = optional($perm->translations->where('locale', $locale)->first())->name ?? ($perm->action ?? $perm->slug);
            $menuNode['children'][] = [
                'title' => $permTitle,
                'key' => 'perm-' . $perm->id,
                'folder' => false,
                'select' => in_array($perm->id, $selected),
            ];
        }

        // Add one level of children menus
        foreach ($menu->children as $child) {
            $childTitle = optional($child->translations->where('locale', $locale)->first())->name ?? ($child->route ?? 'Menu');
            $childNode = [
                'title' => $childTitle,
                'key' => 'menu-' . $child->id,
                'folder' => true,
                'children' => [],
            ];

            foreach ($child->permissions as $perm) {
                $permTitle = optional($perm->translations->where('locale', $locale)->first())->name ?? ($perm->action ?? $perm->slug);
                $childNode['children'][] = [
                    'title' => $permTitle,
                    'key' => 'perm-' . $perm->id,
                    'folder' => false,
                    'select' => in_array($perm->id, $selected),
                ];
            }

            $menuNode['children'][] = $childNode;
        }

        $treeNodes[] = $menuNode;
    }

@endphp
<div id="permissions-block">
    <div class="mb-3">
        <label class="form-label">{{ __('form.permissions') }}</label>
        <div class="mb-2">
            <button type="button" class="btn btn-sm btn-outline-secondary" id="perm-select-all">{{ __('form.select_all') }}</button>
            <button type="button" class="btn btn-sm btn-outline-secondary" id="perm-deselect-all">{{ __('form.deselect_all') }}</button>
            <button type="button" class="btn btn-sm btn-outline-secondary" id="perm-toggle-expand">{{ __('form.expand_all') ?? 'Expand all' }}</button>
        </div>
        <div id="permissions-tree" class="fancytree-default"></div>
        <div id="permissions-hidden-inputs"></div>
    </div>
</div>

@pushOnce('scripts')
    {{-- Load Fancytree from vendor trees directory --}}
    <script src="{{ asset('assets/js/vendor/trees/fancytree_all.min.js') }}"></script>
    <script src="{{ asset('assets/js/vendor/trees/fancytree_childcounter.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof $ === 'undefined' || typeof $.ui === 'undefined' && typeof $.fn.fancytree === 'undefined') {
                console.warn('jQuery or Fancytree not loaded; permissions tree cannot initialize.');
                return;
            }

            const nodes = {!! json_encode($treeNodes) !!};

            // Initialize fancytree
            const tree = $('#permissions-tree').fancytree({
                checkbox: true,
                selectMode: 3,
                icons: false,
                source: nodes,
                init: function(event, data) {
                },
            }).fancytree('getTree');

            function syncSelectedPermissions() {
                const container = document.getElementById('permissions-hidden-inputs');
                container.innerHTML = '';
                const selected = tree.getSelectedNodes();
                selected.forEach(function(node) {
                    if (!node.key || !node.key.startsWith('perm-')) return;
                    const id = node.key.replace('perm-', '');
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'permissions[]';
                    input.value = id;
                    container.appendChild(input);
                });
            }

            document.getElementById('perm-select-all').addEventListener('click', function() {
                tree.visit(function(node) {
                    node.setSelected(true);
                });
                syncSelectedPermissions();
            });
            document.getElementById('perm-deselect-all').addEventListener('click', function() {
                tree.visit(function(node) {
                    node.setSelected(false);
                });
                syncSelectedPermissions();
            });
            
            const expandToggle = document.getElementById('perm-toggle-expand');
            if (expandToggle) {
                let expanded = false;
                expandToggle.addEventListener('click', function() {
                    expanded = !expanded;
                    tree.expandAll(expanded);
                    expandToggle.textContent = expanded ? ("{{ __('form.collapse_all') ?? 'Collapse all' }}") : ("{{ __('form.expand_all') ?? 'Expand all' }}");
                });
            }
            $('#permissions-tree').on('fancytreeselect', function(event, data) {
                syncSelectedPermissions();
            });
            
            // Initial sync
            syncSelectedPermissions();
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function() {
                    syncSelectedPermissions();
                });
            }
            const adminCheckbox = document.getElementById('administrator');
            const permissionsBlock = document.getElementById('permissions-block');

            function updatePermissionsVisibility() {
                if (!adminCheckbox || !permissionsBlock) return;
                if (adminCheckbox.checked) {
                    permissionsBlock.classList.add('d-none');
                } else {
                    permissionsBlock.classList.remove('d-none');
                }
            }

            updatePermissionsVisibility();
            if (adminCheckbox) {
                adminCheckbox.addEventListener('change', function() {
                    updatePermissionsVisibility();
                });
            }
        });
    </script>
@endpushOnce
