<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => '{locale}',
    'where' => ['locale' => 'en|km'],
    'middleware' => ['setlocale', 'navigations'],
], function () {
    // HOME
    Route::get('/', function () {
        $locale = app()->getLocale();
        $sliders = json_decode(file_get_contents(public_path('site/data/' . $locale . '/sliders.json')));
        $achievements = json_decode(file_get_contents(public_path('site/data/' . $locale . '/achievements.json')));
        $choosing = json_decode(file_get_contents(public_path('site/data/' . $locale . '/choosing.json')));
        $services = json_decode(file_get_contents(public_path('site/data/' . $locale . '/services.json')));
        return view('welcome', compact('sliders', 'choosing', 'services', 'achievements'));
    })->name('welcome');

    // PRIVACY POLICY
    Route::get('/privacy-policy', function () {
        $area = [
            'title' => __('global.privacy_policy'),
            'subtitle' => __('global.our_commitment_to_your_privacy'),
        ];

        $content = file_get_contents(public_path('site/data/' . app()->getLocale() . '/privacy-policy.html'));

        return view('welcome', compact('content', 'area'));
    })->name('privacy-policy');

    // COOKIE POLICY
    Route::get('/cookie-policy', function () {
        $area = [
            'title' => __('global.cookie_policy'),
            'subtitle' => __('global.our_commitment_to_your_cookie'),
        ];

        $content = file_get_contents(public_path('site/data/' . app()->getLocale() . '/cookie-policy.html'));

        return view('welcome', compact('content', 'area'));
    })->name('cookie-policy');

    // TERMS & CONDITIONS
    Route::get('/terms-condition', function () {
        $area = [
            'title' => __('global.terms_and_conditions'),
            'subtitle' => __('global.please_read_our_terms_carefully'),
        ];

        $content = file_get_contents(public_path('site/data/' . app()->getLocale() . '/terms-condition.html'));

        return view('welcome', compact('content', 'area'));
    })->name('terms-condition');

    // FAQ
    Route::get('/faq', function () {
        $area = [
            'title' => __('global.frequently_asked_questions'),
            'subtitle' => __('global.your_questions_answered'),
        ];

        $talk = true;

        $categories = json_decode(file_get_contents(public_path('site/data/' . app()->getLocale() . '/faqs.json')));

        return view('welcome', compact('categories', 'area', 'talk'));
    })->name('faq');

    // INTEGRATIONS (list + detail)
    Route::get('/integrations/{slug?}', function () {
        $slug = request()->route('slug');

        $integrations = json_decode(file_get_contents(public_path('site/data/' . app()->getLocale() . '/integrations.json')));
        $integration = collect($integrations)->firstWhere('slug', $slug);

        // LIST PAGE
        if (!$integration) {
            $area = [
                'title'   => __('global.integrations'),
                'subtitle' => __('global.connect_your_favorite_tools'),
            ];
            $talk = true;

            return view('welcome', compact('integrations', 'area', 'talk'));
        }

        // DETAIL PAGE
        $area = [
            'title'    => $integration->name,
            'subtitle' => $integration->short_description ?? '',
        ];

        $meta = $integration->meta ?? new stdClass();

        return view('welcome', compact('integrations', 'area', 'meta', 'integration'));
    })->name('integrations');

    // CAREERS (list + detail)
    Route::get('/careers/{slug?}', function () {
        $slug = request()->route('slug');

        $talk = true;

        $careers = json_decode(file_get_contents(public_path('site/data/' . app()->getLocale() . '/careers.json')));
        $career = collect($careers)->firstWhere('slug', $slug);

        // LIST PAGE
        if (!$career) {
            $area = [
                'title'   => __('global.careers'),
                'subtitle' => __('global.join_our_team'),
            ];

            return view('welcome', compact('careers', 'area', 'talk'));
        }

        // DETAIL PAGE
        $area = [
            'title'    => $career->title,
            'subtitle' => $career->location ?? '',
        ];

        return view('welcome', compact('careers', 'area', 'talk'));
    })->name('careers');

    // TEAMS
    Route::get('/teams', function () {
        $talk = true;

        $teams = json_decode(file_get_contents(public_path('site/data/' . app()->getLocale() . '/teams.json')));

        $area = [
            'title'   => __('global.our_teams'),
            'subtitle' => __('global.meet_our_experts'),
        ];

        return view('welcome', compact('teams', 'area', 'talk'));
    })->name('teams');

    // ABOUT
    Route::get('/about', function () {
        $talk = true;

        $content  = file_get_contents(public_path('site/data/' . app()->getLocale() . '/about.html'));
        $contacts = json_decode(file_get_contents(public_path('site/data/' . app()->getLocale() . '/contacts.json')));

        $area = [
            'title'   => __('global.about_us'),
            'subtitle' => __('global.learn_more_about_our_company'),
        ];

        return view('welcome', compact('content', 'area', 'talk', 'contacts'));
    })->name('about-us');

    // CONTACT
    Route::get('/contact', function () {
        $talk = true;

        $contacts = json_decode(file_get_contents(public_path('site/data/' . app()->getLocale() . '/contacts.json')));
        $choosing = json_decode(file_get_contents(public_path('site/data/' . app()->getLocale() . '/choosing.json')));

        $area = [
            'title'   => __('global.contact_us'),
            'subtitle' => __('global.get_in_touch_with_our_team'),
        ];

        return view('welcome', compact('area', 'talk', 'contacts', 'choosing'));
    })->name('contact-us');

    // BLOGS (list + detail)
    Route::get('/blogs/{slug?}', function () {
        $slug = request()->route('slug');

        $talk = true;

        $articles = json_decode(file_get_contents(public_path('site/data/' . app()->getLocale() . '/articles.json')));
        $article  = collect($articles)->firstWhere('slug', $slug);

        // LIST PAGE
        if (!$article) {
            $area = [
                'title'   => __('global.our_blogs'),
                'subtitle' => __('global.latest_insights_and_updates'),
            ];

            return view('welcome', compact('articles', 'area', 'talk'));
        }

        // DETAIL PAGE
        $area = [
            'title'    => $article->title,
            'subtitle' => $article->short_description ?? '',
        ];

        return view('welcome', compact('articles', 'area', 'talk', 'article'));
    })->name('blogs');

    // SERVICES (list + detail)
    Route::get('/services/{slug?}', function () {
        $slug = request()->route('slug');

        $talk = true;

        $services = json_decode(file_get_contents(public_path('site/data/' . app()->getLocale() . '/services.json')));
        $service  = collect($services)->firstWhere('slug', $slug);

        // LIST PAGE
        if (!$service) {
            $area = [
                'title'   => __('global.services'),
                'subtitle' => __('global.our_service_offerings'),
            ];

            return view('welcome', compact('services', 'area', 'talk'));
        }

        // DETAIL PAGE
        $area = [
            'title'    => $service->name,
            'subtitle' => $service->short_description ?? '',
        ];

        $meta = $service->meta ?? new stdClass();

        return view('welcome', compact('services', 'service', 'area', 'talk', 'meta'));
    })->name('services');

    // PRODUCTS (list + detail)
    Route::get('/products/{slug?}', function () {
        $slug = request()->route('slug');

        $talk = true;

        $products = json_decode(file_get_contents(public_path('site/data/' . app()->getLocale() . '/products.json')));
        $product  = collect($products)->firstWhere('slug', $slug);

        // LIST PAGE
        if (!$product) {
            $area = [
                'title'   => __('global.products'),
                'subtitle' => __('global.our_product_offerings'),
            ];

            return view('welcome', compact('products', 'area', 'talk'));
        }

        // DETAIL PAGE
        $area = [
            'title'    => $product->name,
            'subtitle' => $product->short_description ?? '',
        ];

        $meta    = $product->meta ?? new stdClass();
        $details = $product->features ?? [];

        return view('welcome', compact('product', 'details', 'area', 'talk', 'meta'));
    })->name('products');

    // PRICING (list view only in your current data model)
    Route::get('/pricing/{slug?}', function () {
        $pricing = json_decode(file_get_contents(public_path('site/data/' . app()->getLocale() . '/pricing.json')));

        $talk = true;

        $area = [
            'title'   => __('global.pricing'),
            'subtitle' => __('global.our_pricing_plans'),
        ];

        return view('welcome', compact('pricing', 'area', 'talk'));
    })->name('pricing');

    // CONTACT FORM SUBMIT
    Route::post('/submit-contact', function () {
        Log::info('Contact form submitted', request()->all());

        return response()->json([
            'status'  => 'success',
            'message' => __('global.thank_you_for_getting_in_touch_we_will_get_back_to_you_soon'),
        ]);
    })->name('contact.submit');

    // WEBP ASSET HELPER
    Route::get('/webp', function () {
        if (!request()->has('image')) {
            abort(404);
        }

        echo webpasset(request()->get('image'), request()->get('height'));
    })->name('webp');
});
