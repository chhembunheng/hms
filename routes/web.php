<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\TextEditorController;
use App\Http\Controllers\MetaGeneratorController;

Route::get('/sitemap.xml', [SitemapController::class, 'index']);
Route::get('/privacy-policy.html', function () {
    $area = [
        'title' => 'Privacy Policy',
        'subtitle' => 'Our Commitment to Your Privacy'
    ];
    $content = file_get_contents(public_path('site/data/' . app()->getLocale() . '/privacy-policy.html'));
    return view('welcome', compact('content', 'area'));
})->name('privacy-policy-static-html');

Route::get('/privacy-policy', function () {
    $area = [
        'title' => 'Privacy Policy',
        'subtitle' => 'Our Commitment to Your Privacy'
    ];
    $content = file_get_contents(public_path('site/data/' . app()->getLocale() . '/privacy-policy.html'));
    return view('welcome', compact('content', 'area'));
})->name('privacy-policy-static');


Route::middleware(['auth', 'abilities'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard.index');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/', function () {
    return redirect(app()->getLocale());
});

Route::match(['get', 'post'], '/text-editor/upload', [TextEditorController::class, 'upload'])->name('text-editor.upload');
Route::match(['get', 'post'], '/generate-meta', [MetaGeneratorController::class, 'generate'])->name('meta-generator.generate');

require __DIR__ . '/auth.php';
require __DIR__ . '/frontend.php';
require __DIR__ . '/settings.php';
require __DIR__ . '/landing.php';
