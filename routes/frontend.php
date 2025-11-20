<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\ServiceController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\TeamController;
use App\Http\Controllers\Frontend\BlogController;
use App\Http\Controllers\Frontend\FaqController;
use App\Http\Controllers\Frontend\IntegrationController;
use App\Http\Controllers\Frontend\NavigationController;
use App\Http\Controllers\Frontend\ChoosingController;
use App\Http\Controllers\Frontend\ClientController;
use App\Http\Controllers\Frontend\PartnerController;
use App\Http\Controllers\Frontend\PlanController;
use App\Http\Controllers\Frontend\AchievementController;
use App\Http\Controllers\Frontend\CareerController;
use App\Http\Controllers\Frontend\CategoryController;
use App\Http\Controllers\Frontend\TagController;

Route::group(['prefix' => 'frontends', 'as' => 'frontends.', 'middleware' => ['auth', 'verified', 'abilities', 'editor']], function () {
    
    // Categories Routes
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::match(['get', 'post'], '/add', [CategoryController::class, 'add'])->name('add');
        Route::match(['get', 'post'], '/{id}/edit', [CategoryController::class, 'edit'])->name('edit');
        Route::delete('/{id}/delete', [CategoryController::class, 'destroy'])->name('delete');
    });
    
    // Tags Routes
    Route::prefix('tags')->name('tags.')->group(function () {
        Route::get('/', [TagController::class, 'index'])->name('index');
        Route::match(['get', 'post'], '/add', [TagController::class, 'add'])->name('add');
        Route::match(['get', 'post'], '/{id}/edit', [TagController::class, 'edit'])->name('edit');
        Route::delete('/{id}/delete', [TagController::class, 'destroy'])->name('delete');
    });
    
    // Services Routes
    Route::prefix('services')->name('services.')->group(function () {
        Route::get('/', [ServiceController::class, 'index'])->name('index');
        Route::match(['get', 'post'], '/add', [ServiceController::class, 'add'])->name('add');
        Route::match(['get', 'post'], '/{id}/edit', [ServiceController::class, 'edit'])->name('edit');
        Route::delete('/{id}/delete', [ServiceController::class, 'destroy'])->name('delete');
    });
    
    // Products Routes
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::match(['get', 'post'], '/add', [ProductController::class, 'add'])->name('add');
        Route::match(['get', 'post'], '/{id}/edit', [ProductController::class, 'edit'])->name('edit');
        Route::match(['get', 'post'], '/{id}/feature', [ProductController::class, 'feature'])->name('feature');
        Route::delete('/{id}/delete', [ProductController::class, 'destroy'])->name('delete');
    });
    
    // Teams Routes
    Route::prefix('teams')->name('teams.')->group(function () {
        Route::get('/', [TeamController::class, 'index'])->name('index');
        Route::match(['get', 'post'], '/add', [TeamController::class, 'add'])->name('add');
        Route::match(['get', 'post'], '/{id}/edit', [TeamController::class, 'edit'])->name('edit');
        Route::delete('/{id}/delete', [TeamController::class, 'destroy'])->name('delete');
    });
    
    // Blogs Routes
    Route::prefix('blogs')->name('blogs.')->group(function () {
        Route::get('/', [BlogController::class, 'index'])->name('index');
        Route::match(['get', 'post'], '/add', [BlogController::class, 'add'])->name('add');
        Route::match(['get', 'post'], '/{id}/edit', [BlogController::class, 'edit'])->name('edit');
        Route::delete('/{id}/delete', [BlogController::class, 'destroy'])->name('delete');
    });
    
    // FAQs Routes
    Route::prefix('faqs')->name('faqs.')->group(function () {
        Route::get('/', [FaqController::class, 'index'])->name('index');
        Route::match(['get', 'post'], '/add', [FaqController::class, 'add'])->name('add');
        Route::match(['get', 'post'], '/{id}/edit', [FaqController::class, 'edit'])->name('edit');
        Route::delete('/{id}/delete', [FaqController::class, 'destroy'])->name('delete');
    });
    
    // Integrations Routes
    Route::prefix('integrations')->name('integrations.')->group(function () {
        Route::get('/', [IntegrationController::class, 'index'])->name('index');
        Route::match(['get', 'post'], '/add', [IntegrationController::class, 'add'])->name('add');
        Route::match(['get', 'post'], '/{id}/edit', [IntegrationController::class, 'edit'])->name('edit');
        Route::delete('/{id}/delete', [IntegrationController::class, 'destroy'])->name('delete');
    });
    
    // Navigation Routes
    Route::prefix('navigations')->name('navigations.')->group(function () {
        Route::get('/', [NavigationController::class, 'index'])->name('index');
        Route::match(['get', 'post'], '/add', [NavigationController::class, 'add'])->name('add');
        Route::match(['get', 'post'], '/{id}/edit', [NavigationController::class, 'edit'])->name('edit');
        Route::delete('/{id}/delete', [NavigationController::class, 'destroy'])->name('delete');
    });
    
    // Choosings Routes
    Route::prefix('choosings')->name('choosings.')->group(function () {
        Route::get('/', [ChoosingController::class, 'index'])->name('index');
        Route::match(['get', 'post'], '/add', [ChoosingController::class, 'add'])->name('add');
        Route::match(['get', 'post'], '/{id}/edit', [ChoosingController::class, 'edit'])->name('edit');
        Route::delete('/{id}/delete', [ChoosingController::class, 'destroy'])->name('delete');
    });
    
    // Clients Routes
    Route::prefix('clients')->name('clients.')->group(function () {
        Route::get('/', [ClientController::class, 'index'])->name('index');
        Route::match(['get', 'post'], '/add', [ClientController::class, 'add'])->name('add');
        Route::match(['get', 'post'], '/{id}/edit', [ClientController::class, 'edit'])->name('edit');
        Route::delete('/{id}/delete', [ClientController::class, 'destroy'])->name('delete');
    });
    
    // Partners Routes
    Route::prefix('partners')->name('partners.')->group(function () {
        Route::get('/', [PartnerController::class, 'index'])->name('index');
        Route::match(['get', 'post'], '/add', [PartnerController::class, 'add'])->name('add');
        Route::match(['get', 'post'], '/{id}/edit', [PartnerController::class, 'edit'])->name('edit');
        Route::delete('/{id}/delete', [PartnerController::class, 'destroy'])->name('delete');
    });
    
    // Plans Routes
    Route::prefix('plans')->name('plans.')->group(function () {
        Route::get('/', [PlanController::class, 'index'])->name('index');
        Route::match(['get', 'post'], '/add', [PlanController::class, 'add'])->name('add');
        Route::match(['get', 'post'], '/{id}/edit', [PlanController::class, 'edit'])->name('edit');
        Route::delete('/{id}/delete', [PlanController::class, 'destroy'])->name('delete');
    });
    
    // Achievements Routes
    Route::prefix('achievements')->name('achievements.')->group(function () {
        Route::get('/', [AchievementController::class, 'index'])->name('index');
        Route::match(['get', 'post'], '/add', [AchievementController::class, 'add'])->name('add');
        Route::match(['get', 'post'], '/{id}/edit', [AchievementController::class, 'edit'])->name('edit');
        Route::delete('/{id}/delete', [AchievementController::class, 'destroy'])->name('delete');
    });
    
    // Careers Routes
    Route::prefix('careers')->name('careers.')->group(function () {
        Route::get('/', [CareerController::class, 'index'])->name('index');
        Route::match(['get', 'post'], '/add', [CareerController::class, 'add'])->name('add');
        Route::match(['get', 'post'], '/{id}/edit', [CareerController::class, 'edit'])->name('edit');
        Route::delete('/{id}/delete', [CareerController::class, 'destroy'])->name('delete');
    });
    
});
