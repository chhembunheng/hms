<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Traits\HomepageDataAdapter;
use App\Traits\SiteContentAdapter;

class HomeController extends Controller
{
    use HomepageDataAdapter, SiteContentAdapter;

    public function index()
    {
        $locale = app()->getLocale();

        // All content now from database
        $data = $this->getCommonContent($locale);
        $stats = $this->getHomepageData();

        return view('welcome', [
            'sliders' => $data['services'] ?? collect(),
            'achievements' => $data['achievements'] ?? collect(),
            'choosing' => $data['choosing'] ?? collect(),
            'services' => $data['services'] ?? collect(),
            'stats' => $stats,
        ]);
    }
}
