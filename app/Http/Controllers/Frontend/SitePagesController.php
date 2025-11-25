<?php

namespace App\Http\Controllers\Frontend;

use App\Traits\SiteContentAdapter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

class SitePagesController extends Controller
{
    use SiteContentAdapter;

    private function loadView(array $area = [])
    {
        $contents = $this->getCommonContent(app()->getLocale());
        $data = [];
        $routeName = Route::currentRouteName();
        foreach ($contents as $key => $collection) {
            if (in_array($key, config('init.sections.' . $routeName, []))) {
                if($key === 'products' && $routeName === 'products') {
                    $product = $collection->where('slug', request()->route('slug'))->first();
                    $area = [
                        'title' => $product?->getName() ?? __('global.products'),
                        'subtitle' => $product?->getDescription() ?? __('global.our_products'),
                    ];
                    $data['product'] = $product;
                    $data['products'] = $collection;
                    continue;
                }
                if($key === 'services' && $routeName === 'services') {
                    $service = $collection->where('slug', request()->route('slug'))->first();
                    $area = [
                        'title' => $service?->getName() ?? __('global.services'),
                        'subtitle' => $service?->getDescription() ?? __('global.our_offered_services'),
                    ];
                    $data['service'] = $service;
                    $data['services'] = $collection;
                    continue;
                }
                if($key === 'integrations' && $routeName === 'integrations') {
                    $integration = $collection->where('slug', request()->route('slug'))->first();
                    $area = [
                        'title' => $integration?->getName() ?? __('global.integrations'),
                        'subtitle' => $integration?->getDescription() ?? __('global.our_offered_integrations'),
                    ];
                    $data['integration'] = $integration;
                    $data['integrations'] = $collection;
                    continue;
                }
                if($key === 'careers' && $routeName === 'careers') {
                    $career = $collection->where('slug', request()->route('slug'))->first();
                    $area = [
                        'title' => $career?->getTitle() ?? __('global.careers'),
                        'subtitle' => $career?->getDescription() ?? __('global.our_offered_careers'),
                    ];
                    $data['career'] = $career;
                    $data['careers'] = $collection;
                    continue;
                }
                $data[$key] = $collection;
            }
        }
        if(!empty($area)) {
            $data['area'] = $area;
        }
        return view('welcome', $data);
    }

    public function index()
    {
        return $this->loadView();
    }

    public function privacyPolicy()
    {
        $content = file_get_contents(public_path('site/data/' . app()->getLocale() . '/privacy-policy.html'));
        $area = [
            'title' => __('global.privacy_policy'),
            'subtitle' => __('global.our_privacy_policy'),
        ];
        return $this->loadView()->with('content', $content)->with('area', $area);
    }

    public function cookiePolicy()
    {
        return $this->loadView()->with('area', [
            'title' => __('global.cookie_policy'),
            'subtitle' => __('global.our_cookie_policy'),
        ]);
    }

    public function termsCondition()
    {
        return $this->loadView()->with('area', [
            'title' => __('global.terms_and_conditions'),
            'subtitle' => __('global.our_terms_and_conditions'),
        ]);
    }
    public function faqs()
    {
        return $this->loadView()->with('area', [
            'title' => __('global.faq'),
            'subtitle' => __('global.frequently_asked_questions'),
        ]);
    }

    public function integrations()
    {
        return $this->loadView()->with('area', [
            'title' => __('global.integrations'),
            'subtitle' => __('global.our_integrations'),
        ]);
    }

    public function careers()
    {
        return $this->loadView()->with('area', [
            'title' => __('global.careers'),
            'subtitle' => __('global.join_our_team'),
        ]);
    }

    public function teams()
    {
        $area = [
            'title' => __('global.our_team'),
            'subtitle' => __('global.meet_our_experts'),
        ];
        return $this->loadView($area);
    }
    public function about()
    {
        return $this->loadView()->with('area', [
            'title' => __('global.about_us'),
            'subtitle' => __('global.learn_more_about_us'),
        ]);
    }

    public function contact()
    {
        return $this->loadView()->with('area', [
            'title' => __('global.contact_us'),
            'subtitle' => __('global.get_in_touch_with_us'),
        ]);
    }

    public function blogs()
    {
        return $this->loadView()->with('area', [
            'title' => __('global.blogs'),
            'subtitle' => __('global.our_latest_news'),
        ]);
    }

    public function services()
    {
        $area = [
            'title' => __('global.services'),
            'subtitle' => __('global.our_offered_services'),
        ];
        return $this->loadView($area);
    }

    public function products()
    {
        $area = [
            'title' => __('global.products'),
            'subtitle' => __('global.our_products'),
        ];
        return $this->loadView($area);
    }

    public function pricing()
    {
        return $this->loadView()->with('area', [
            'title' => __('global.pricing'),
            'subtitle' => __('global.our_pricing_plans'),
        ]);
    }
}
