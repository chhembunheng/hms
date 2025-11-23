<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => '{locale}',
    'where' => ['locale' => 'en|km'],
    'middleware' => ['setlocale', 'navigations'],
], function () {
    $controller = \App\Http\Controllers\Frontend\SitePagesController::class;
    $homeController = \App\Http\Controllers\Frontend\HomeController::class;

    // ==========================================
    // HOME PAGE
    // ==========================================
    // Content: Homepage with sliders, services, achievements, choosing section
    Route::get('/', [$homeController, 'index'])->name('welcome');

    // ==========================================
    // STATIC POLICY PAGES
    // ==========================================
    // Privacy Policy: Static HTML content about privacy
    Route::get('/privacy-policy', [$controller, 'privacyPolicy'])->name('privacy-policy');

    // Cookie Policy: Static HTML content about cookies
    Route::get('/cookie-policy', [$controller, 'cookiePolicy'])->name('cookie-policy');

    // Terms & Conditions: Static HTML content about terms
    Route::get('/terms-condition', [$controller, 'termsCondition'])->name('terms-condition');

    // ==========================================
    // INFORMATION PAGES
    // ==========================================
    // FAQ: List of FAQ categories with questions/answers from database
    Route::get('/faq', [$controller, 'faq'])->name('faq');

    // About: Static content about the company
    Route::get('/about', [$controller, 'about'])->name('about');

    // Contact: Contact form with "why choose us" sections (choosing)
    Route::get('/contact', [$controller, 'contact'])->name('contact');

    // ==========================================
    // BUSINESS CONTENT PAGES
    // ==========================================
    // Services: List of all services or single service detail by slug
    Route::get('/services/{slug?}', [$controller, 'services'])->name('services');

    // Products: List of all products or single product detail by slug
    Route::get('/products/{slug?}', [$controller, 'products'])->name('products');

    // Pricing: List of all pricing plans
    Route::get('/pricing/{slug?}', [$controller, 'pricing'])->name('pricing');

    // ==========================================
    // COMPANY / TEAM PAGES
    // ==========================================
    // Teams: List of all team members or single team member detail by slug
    Route::get('/teams/{slug?}', [$controller, 'teams'])->name('teams');

    // Careers: List of all job openings or single career detail by slug
    Route::get('/careers/{slug?}', [$controller, 'careers'])->name('careers');

    // ==========================================
    // RESOURCES / EXTERNAL PAGES
    // ==========================================
    // Blogs: List of blog articles or single blog detail by slug
    Route::get('/blogs/{slug?}', [$controller, 'blogs'])->name('blogs');

    // Integrations: List of partner integrations or single integration detail by slug
    Route::get('/integrations/{slug?}', [$controller, 'integrations'])->name('integrations');

    // ==========================================
    // FORM SUBMISSIONS & ACTIONS
    // ==========================================
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
