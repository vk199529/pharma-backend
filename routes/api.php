<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RfqController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\ProductPdfController;
use App\Http\Controllers\CompanyFaqController;
use App\Http\Controllers\ShippingFaqController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\Api\ProductController as ApiProductController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/smart-search', [ProductController::class, 'search']);
Route::post('/submit-rfq', [RfqController::class, 'storeFormData']);
Route::get('/countries', [CountryController::class, 'getJson']);
Route::post('/download-pdf', [ProductPdfController::class, 'downloadPdf']);

Route::get('/company-faq-search', [CompanyFaqController::class, 'search']);
Route::get('/shipping-faq-search', [ShippingFaqController::class, 'search']);

Route::get('/faq/search', [FaqController::class, 'search'])->name('faq.search');

Route::get('/products-by-category', [ApiProductController::class, 'groupedByCategory']);

Route::get('/products', [ApiProductController::class, 'allProducts']);

Route::get('/homepage-products', [ApiProductController::class, 'homepageProducts']);

Route::get('/product/{id}', [ApiProductController::class, 'getProductById']);

Route::get('/products/search', [ApiProductController::class, 'search']);


