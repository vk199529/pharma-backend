<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProductCrudController;
use App\Http\Controllers\Admin\FaqCrudController;
use App\Http\Controllers\Admin\ShippingFaqCrudController;
use App\Http\Controllers\Admin\CompanyFaqCrudController;
use App\Http\Controllers\Admin\ProductBulkController;

Route::group([
    'prefix' => config('backpack.base.route_prefix'),
    'middleware' => ['web', backpack_middleware()],
], function () {

    Route::get(
        'product/bulk-edit',
        [ProductBulkController::class, 'index']
    );

    Route::post(
        'product/bulk-update',
        [ProductBulkController::class, 'update']
    );

});

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\CRUD.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace' => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('product', 'ProductCrudController');
	
	  Route::get('product/bulk-edit', [ProductBulkController::class, 'index']);

    Route::post('product/bulk-update', [ProductBulkController::class, 'update']);
	
    Route::crud('rfq', 'RfqCrudController');
	Route::get('product/export', [ProductCrudController::class, 'export'])->name('product.export');
        Route::post('admin/product/import', [ProductCrudController::class, 'importCsv'])->name('product.import');
    Route::crud('categories', 'CategoriesCrudController');
	Route::get('product/{id}/copy', [ProductCrudController::class, 'copy'])->name('product.copy');
    Route::crud('faq-category', 'FaqCategoryCrudController');
    Route::crud('faq', 'FaqCrudController');
	Route::post('import-faqs', [FaqCrudController::class, 'import'])->name('faq.import');
	Route::get('faq/export', [FaqCrudController::class, 'export'])->name('faq.export');
	
	  Route::crud('shipping-faq', 'ShippingFaqCrudController');
	Route::post('shipping-faq/import', [ShippingFaqCrudController::class, 'import'])->name('shipping-faq.import');
Route::get('shipping-faq/export', [ShippingFaqCrudController::class, 'export'])->name('shipping-faq.export');

Route::crud('company-faq', 'CompanyFaqCrudController');
    Route::get('companyfaq/export', [CompanyFaqCrudController::class, 'export'])->name('companyfaq.export');
Route::post('companyfaq/import', [CompanyFaqCrudController::class, 'import'])->name('companyfaq.import');
}); // this should be the absolute last line of this file

/**
 * DO NOT ADD ANYTHING HERE.
 */
