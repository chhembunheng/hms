<?php

$json_content = file_get_contents('database/seeders/data/menus.json');
$menus = json_decode($json_content, true);

if ($menus === null) {
    echo "❌ JSON is INVALID!" . PHP_EOL;
    echo "Error: " . json_last_error_msg() . PHP_EOL;
    exit(1);
}

echo "✅ JSON is VALID!" . PHP_EOL . PHP_EOL;
echo "=== MENU STRUCTURE WITH PERMISSIONS ===" . PHP_EOL . PHP_EOL;

$issues = [];

foreach ($menus as $idx => $menu) {
    echo "Menu #{$idx}: " . ($menu['name'] ?? 'NO NAME') . " (order: " . ($menu['order'] ?? '?') . ")" . PHP_EOL;
    echo "  Route: " . ($menu['route'] ?? 'null (parent group)') . PHP_EOL;
    echo "  Permissions: " . count($menu['permissions'] ?? []) . PHP_EOL;
    
    // Check required fields
    if (empty($menu['name'])) $issues[] = "Menu #{$idx}: Missing 'name'";
    if (!isset($menu['order'])) $issues[] = "Menu #{$idx}: Missing 'order'";
    if (!is_array($menu['permissions'])) $issues[] = "Menu #{$idx}: Missing 'permissions' array";
    if (!isset($menu['name_en'])) $issues[] = "Menu #{$idx}: Missing 'name_en'";
    if (!isset($menu['name_km'])) $issues[] = "Menu #{$idx}: Missing 'name_km'";
    // route can be null for parent groups
    
    // Check children
    if (!empty($menu['children'])) {
        echo "  Children: " . count($menu['children']) . PHP_EOL;
        foreach ($menu['children'] as $c_idx => $child) {
            echo "    ├─ " . ($child['name'] ?? 'NO NAME') . " → " . ($child['route'] ?? 'null') . PHP_EOL;
            
            $perm_count = count($child['permissions'] ?? []);
            echo "       Permissions: " . $perm_count;
            
            if (!empty($child['permissions'])) {
                $perm_names = array_map(function($p) {
                    return $p['name'] . "(" . $p['action'] . ")";
                }, $child['permissions']);
                echo " [" . implode(", ", $perm_names) . "]";
            }
            echo PHP_EOL;
            
            // Check child required fields
            if (empty($child['name'])) $issues[] = "Menu #{$idx}, Child #{$c_idx}: Missing 'name'";
            if (!isset($child['order'])) $issues[] = "Menu #{$idx}, Child #{$c_idx}: Missing 'order'";
            if (!isset($child['route'])) $issues[] = "Menu #{$idx}, Child #{$c_idx}: Missing 'route' (required for menu items)";
            if (!is_array($child['permissions'])) $issues[] = "Menu #{$idx}, Child #{$c_idx}: Missing 'permissions' array";
        }
    }
    echo PHP_EOL;
}

if (!empty($issues)) {
    echo "❌ VALIDATION ISSUES FOUND:" . PHP_EOL;
    foreach ($issues as $issue) {
        echo "  - " . $issue . PHP_EOL;
    }
    exit(1);
} else {
    echo "✅ All validations passed!" . PHP_EOL;
}
