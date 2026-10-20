<?php

use Illuminate\Support\Facades\Route;
//use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\ProductPageController;
use App\Http\Controllers\ProductPdfController;
use App\Http\Controllers\RfqController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\CompanyFaqController;
use App\Http\Controllers\ShippingFaqController;
use App\Http\Controllers\B2bLeadController;
use App\Http\Controllers\ContactController;

/*
|--------------------------------------------------------------------------
| Web Routes  (English + Spanish)
|--------------------------------------------------------------------------
| Pages live under /en/... and /es/...   (default language: config('app.locale') = en)
| Forms / AJAX routes have no prefix; they use the language saved in the session.
*/

// "/" -> last used language, or the default one
Route::get('/', fn () => redirect('/' . session('site_locale', config('app.locale'))));

// ---------- PAGES (one set of routes, two languages) ----------
Route::prefix('{locale}')->where(['locale' => 'en|es'])->group(function () {

Route::get('/', function () {
    return view('pages/welcome');
});
    //Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/shop', [ShopController::class, 'index'])->name('shop');
    Route::get('/product/{id}/{slug?}', [ProductPageController::class, 'show'])->whereNumber('id')->name('product.show');

    Route::view('/our-company', 'pages.our-company')->name('our.company');
    Route::view('/warehousing', 'pages.warehousing')->name('warehousing');
    Route::view('/b2b', 'pages.b2b')->name('b2b');
    Route::view('/contract-development-manufacturing-services', 'pages.cdmo')->name('cdmo');
    Route::view('/what-we-are-working-on', 'pages.rd-pipeline')->name('rd.pipeline');

    Route::get('/faq', [FaqController::class, 'index'])->name('faq.page');
    Route::view('/contact-us', 'pages.contact')->name('contact');
    Route::get('/', [CountryController::class, 'index']);
});

// ---------- OLD URLs (no language) -> redirect to the localized page ----------
foreach (['shop', 'faq', 'contact-us', 'b2b', 'warehousing', 'our-company', 'contract-development-manufacturing-services', 'what-we-are-working-on'] as $page) {
    Route::get('/' . $page, fn () => redirect('/' . session('site_locale', config('app.locale')) . '/' . $page, 301));
}
Route::get('/product/{id}/{slug?}', fn ($id, $slug = null) => redirect('/' . session('site_locale', config('app.locale')) . '/product/' . $id . ($slug ? '/' . $slug : ''), 301))
    ->whereNumber('id');

// ---------- SEARCH (used by the search box) ----------
Route::get('/products-smart-search', [ProductController::class, 'search']);
Route::get('/faq/search', [FaqController::class, 'search'])->name('faq.search');
Route::get('/company-faq-search', [CompanyFaqController::class, 'search']);
Route::get('/shipping-faq-search', [ShippingFaqController::class, 'search']);

// ---------- FORMS / DOWNLOADS ----------
Route::post('/submit-rfq', [RfqController::class, 'storeFormData']);
Route::get('/submit-rfq-test', [RfqController::class, 'test']);
Route::post('/b2b/lead', [B2bLeadController::class, 'store'])->name('b2b.lead');
Route::post('/contact-us/send', [ContactController::class, 'store'])->name('contact.send');
Route::post('/download-pdf', [ProductPdfController::class, 'downloadPdf'])->name('download.pdf');

// ---------- TOOLS ----------
Route::get('/timezone', function () {
    return [
        'timezone'     => config('app.timezone'),
        'current_time' => now()->toDateTimeString(),
    ];
});