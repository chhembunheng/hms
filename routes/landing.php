<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => '{locale}',
    'where' => ['locale' => 'en|km'],
    'middleware' => ['setlocale', 'navigations'],
], function () {
    $controller = \App\Http\Controllers\Frontend\SitePagesController::class;

    Route::get('/', [$controller, 'index'])->name('index');

    Route::get('/privacy-policy', [$controller, 'privacyPolicy'])->name('privacy-policy');

    Route::get('/cookie-policy', [$controller, 'cookiePolicy'])->name('cookie-policy');

    Route::get('/terms-condition', [$controller, 'termsCondition'])->name('terms-condition');

    Route::get('/faq', [$controller, 'faq'])->name('faq');

    Route::get('/about', [$controller, 'about'])->name('about');

    Route::get('/contact', [$controller, 'contact'])->name('contact');

    Route::get('/services/{slug?}', [$controller, 'services'])->name('services');

    Route::get('/products/{slug?}', [$controller, 'products'])->name('products');

    Route::get('/pricing/{slug?}', [$controller, 'pricing'])->name('pricing');

    Route::get('/teams/{slug?}', [$controller, 'teams'])->name('teams');

    Route::get('/careers/{slug?}', [$controller, 'careers'])->name('careers');

    Route::get('/blogs/{slug?}', [$controller, 'blogs'])->name('blogs');

    Route::get('/integrations/{slug?}', [$controller, 'integrations'])->name('integrations');

    Route::post('/submit-contact', function () {
        $data = request()->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'subject' => 'required',
            'message' => 'required',
        ]);
        Log::info('Contact Form Submitted', $data);
        return back()->with('success', __('global.message_sent_successfully'));
    })->name('submit-contact');
});
