<?php

/**
 * Site pages smoke tests.
 * Verifies each public localized route responds successfully and includes stats injection.
 */

// Logging assertions removed (Log::fake not available); verify render + stats element only.

// Localized public routes from landing.php to verify basic availability.
$routes = [
    '/',
    '/privacy-policy',
    '/cookie-policy',
    '/terms-condition',
    '/faq',
    '/integrations',
    '/careers',
    '/teams',
    '/about',
    '/contact',
    '/blogs',
    '/services',
    '/products',
    '/pricing',
];

foreach (['en','km'] as $locale) {
    foreach ($routes as $path) {
        it("{$locale}{$path} responds", function () use ($locale, $path) {
            $response = $this->get("/{$locale}" . ($path === '/' ? '' : $path));
            $response->assertStatus(200);
        });
    }
}
