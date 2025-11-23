<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Traits\SiteContentAdapter;

class SitePagesController extends Controller
{
    use SiteContentAdapter;

    private function getContent($locale)
    {
        return $this->getCommonContent($locale);
    }

    /**
     * Privacy Policy Page
     * Content: Static policy text
     */
    public function privacyPolicy()
    {
        $area = ['title' => __('global.privacy_policy'), 'subtitle' => __('global.our_commitment_to_your_privacy')];
        return view('welcome', compact('area'));
    }

    /**
     * Cookie Policy Page
     * Content: Static cookie policy text
     */
    public function cookiePolicy()
    {
        $area = ['title' => __('global.cookie_policy'), 'subtitle' => __('global.our_cookie_policy')];
        return view('welcome', compact('area'));
    }

    /**
     * Terms & Conditions Page
     * Content: Static terms text
     */
    public function termsCondition()
    {
        $area = ['title' => __('global.terms_condition'), 'subtitle' => __('global.our_terms_and_conditions')];
        return view('welcome', compact('area'));
    }

    /**
     * FAQ Page
     * Content: List of FAQ categories with Q&A
     */
    public function faq()
    {
        $locale = app()->getLocale();
        $content = $this->getContent($locale);
        $categories = $content['categories'] ?? collect();
        $area = ['title' => __('global.faq'), 'subtitle' => __('global.frequently_asked_questions')];
        return view('welcome', compact('categories', 'area'));
    }

    /**
     * Integrations Page
     * Content: List of partner integrations OR single integration detail by slug
     */
    public function integrations()
    {
        $locale = app()->getLocale();
        $slug = request()->route('slug');
        $content = $this->getContent($locale);
        $integrations = $content['integrations'] ?? collect();
        
        if ($slug) {
            $integration = $integrations->firstWhere('slug', $slug);
            if (!$integration) abort(404);
            $area = ['title' => $integration->translations?->where('locale', $locale)->first()?->name ?? 'Integration', 'subtitle' => $integration->translations?->where('locale', $locale)->first()?->description ?? ''];
            return view('welcome', compact('integration', 'area'));
        }
        
        $area = ['title' => __('global.integrations'), 'subtitle' => __('global.our_partners')];
        return view('welcome', compact('integrations', 'area'));
    }

    /**
     * Careers Page
     * Content: List of all job openings OR single career detail by slug
     */
    public function careers()
    {
        $locale = app()->getLocale();
        $slug = request()->route('slug');
        $content = $this->getContent($locale);
        $careers = $content['careers'] ?? collect();
        
        if ($slug) {
            $career = $careers->firstWhere('slug', $slug);
            if (!$career) abort(404);
            $area = ['title' => $career->translations?->where('locale', $locale)->first()?->name ?? 'Career', 'subtitle' => $career->location ?? ''];
            return view('welcome', compact('career', 'area'));
        }
        
        $area = ['title' => __('global.careers'), 'subtitle' => __('global.join_our_team')];
        return view('welcome', compact('careers', 'area'));
    }

    /**
     * Teams Page
     * Content: List of all team members OR single team member detail by slug
     */
    public function teams()
    {
        $locale = app()->getLocale();
        $slug = request()->route('slug');
        $content = $this->getContent($locale);
        $teams = $content['teams'] ?? collect();
        
        if ($slug) {
            $team = $teams->firstWhere('slug', $slug);
            if (!$team) abort(404);
            $area = ['title' => $team->translations?->where('locale', $locale)->first()?->name ?? 'Team', 'subtitle' => $team->translations?->where('locale', $locale)->first()?->position_name ?? ''];
            return view('welcome', compact('team', 'area'));
        }
        
        $area = ['title' => __('global.teams'), 'subtitle' => __('global.our_team')];
        return view('welcome', compact('teams', 'area'));
    }

    /**
     * About Page
     * Content: Company information
     */
    public function about()
    {
        $area = ['title' => __('global.about'), 'subtitle' => __('global.about_us')];
        return view('welcome', compact('area'));
    }

    /**
     * Contact Page
     * Content: Contact form + Why choose us sections
     */
    public function contact()
    {
        $locale = app()->getLocale();
        $content = $this->getContent($locale);
        $choosing = $content['choosing'] ?? collect();
        $area = ['title' => __('global.contact'), 'subtitle' => __('global.contact_us')];
        return view('welcome', compact('choosing', 'area'));
    }

    /**
     * Blogs Page
     * Content: List of blog articles OR single blog detail by slug
     */
    public function blogs()
    {
        $locale = app()->getLocale();
        $slug = request()->route('slug');
        $content = $this->getContent($locale);
        $articles = collect();
        
        if ($slug) {
            $article = null;
            if ($article) {
                $area = ['title' => $article->title, 'subtitle' => ''];
                return view('welcome', compact('article', 'area', 'articles'));
            }
            abort(404);
        }
        
        $area = ['title' => __('global.blogs'), 'subtitle' => __('global.our_blog')];
        return view('welcome', compact('articles', 'area'));
    }

    /**
     * Services Page
     * Content: List of all services OR single service detail by slug
     */
    public function services()
    {
        $locale = app()->getLocale();
        $slug = request()->route('slug');
        $content = $this->getContent($locale);
        $services = $content['services'] ?? collect();
        
        if ($slug) {
            $service = $services->firstWhere('slug', $slug);
            if (!$service) abort(404);
            $area = ['title' => $service->translations?->where('locale', $locale)->first()?->name ?? 'Service', 'subtitle' => $service->translations?->where('locale', $locale)->first()?->description ?? ''];
            $details = [];
            return view('welcome', compact('service', 'area', 'details'));
        }
        
        $area = ['title' => __('global.services'), 'subtitle' => __('global.our_services')];
        return view('welcome', compact('services', 'area'));
    }

    /**
     * Products Page
     * Content: List of all products OR single product detail by slug
     */
    public function products()
    {
        $locale = app()->getLocale();
        $slug = request()->route('slug');
        $content = $this->getContent($locale);
        $products = $content['products'] ?? collect();
        
        if ($slug) {
            $product = $products->firstWhere('slug', $slug);
            if (!$product) abort(404);
            $area = ['title' => $product->translations?->where('locale', $locale)->first()?->name ?? 'Product', 'subtitle' => $product->translations?->where('locale', $locale)->first()?->description ?? ''];
            $details = $product->features ?? [];
            return view('welcome', compact('product', 'area', 'details'));
        }
        
        $area = ['title' => __('global.products'), 'subtitle' => __('global.our_products')];
        return view('welcome', compact('products', 'area'));
    }

    /**
     * Pricing Page
     * Content: All pricing plans
     */
    public function pricing()
    {
        $locale = app()->getLocale();
        $content = $this->getContent($locale);
        $pricing = $content['pricing'] ?? collect();
        
        $area = ['title' => __('global.pricing'), 'subtitle' => __('global.our_pricing_plans')];
        return view('welcome', compact('pricing', 'area'));
    }
}
