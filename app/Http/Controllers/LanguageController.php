<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function setLanguage($lang)
    {
        $supportedLanguages = array_keys(config('init.languages'));

        if (in_array($lang, $supportedLanguages)) {
            session(['locale' => $lang]);
            app()->setLocale($lang);
        }

        return redirect()->back();
    }
}
