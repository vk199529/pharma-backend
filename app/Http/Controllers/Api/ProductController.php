<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Categories;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
 public function groupedByCategory(): JsonResponse
{
   
    $categories = \App\Models\Categories::with(['products' => function ($query) {
        $query->select([
          'id',
         'category_id',
         'generic_name',
        'brand_names',
		'product_type',
        'form',
        'strength',
        'therapeutic_class',
        'atc_code',
        'manufacturer_name',
        'manufacturer_country',
        'gmp_status',
        'dmf_number',
        'cofepris_status',
        'free_sale_certificate',
        'moq',
        'shelf_life',
        'storage',
        'incoterms',
        'lead_time',
        'coa',
        'gmp_certificate',
        'sds',
        'ctd_summary',
        'rfq',
        'contact_regulatory_advisor',
        'download_dossier',
		'image',
        ])
		->orderBy('generic_name', 'asc');
    }])
    ->get()
    ->filter(function ($category) {
        return $category->products->isNotEmpty();
    })
    ->map(function ($category) {
        return [
            'name' => $category->name,
            'products' => $category->products,
        ];
    })
    ->values();

   
    $uncategorizedProducts = \App\Models\Product::whereNull('category_id')
        ->orWhere('category_id', 0)
        ->select([
            'id',
            'category_id',
            'generic_name',
        'brand_names',
		'product_type',
        'form',
        'strength',
        'therapeutic_class',
        'atc_code',
        'manufacturer_name',
        'manufacturer_country',
        'gmp_status',
        'dmf_number',
        'cofepris_status',
        'free_sale_certificate',
        'moq',
        'shelf_life',
        'storage',
        'incoterms',
        'lead_time',
        'coa',
        'gmp_certificate',
        'sds',
        'ctd_summary',
        'rfq',
        'contact_regulatory_advisor',
        'download_dossier',
		'image',
        ])
		 ->orderBy('generic_name', 'asc') 
        ->get();

    if ($uncategorizedProducts->isNotEmpty()) {
        $categories->push([
            'name' => 'Other',
            'products' => $uncategorizedProducts
        ]);
    }

    return response()->json([
        'success' => true,
        'data' => $categories,
    ]);
}

 public function allProducts()
    {
        $products = \App\Models\Product::select('id', 
		'generic_name',
		'brand_names',
		'product_type',
		'form',
		'strength',
		'therapeutic_class', 
		'atc_code',
		'manufacturer_name',
        'gmp_status',
        'dmf_number',
        'cofepris_status',
        'free_sale_certificate',
		'moq',
        'shelf_life',
        'storage',
        'incoterms',
        'lead_time',
        'coa',
        'gmp_certificate',
        'sds',
        'ctd_summary',
		'image')
          ->orderBy('generic_name', 'asc')
            ->get();

        return response()->json([
            'status' => true,
            'total'  => $products->count(),
            'data'   => $products,
        ]);
    }

public function homepageProducts()
{
    /* $products = \App\Models\Product::where('show_on_homepage', true)
        ->select('id','generic_name', 'image')
        ->orderBy('generic_name', 'asc')   
        ->limit(18)                         
        ->get(); */
		
		$products = \App\Models\Product::where('show_on_homepage', true)
        ->select('id',
		'generic_name',
        'brand_names',
		'product_type',
		'form',
		'strength',
		'therapeutic_class', 
		'atc_code',
		'manufacturer_name',
        'gmp_status',
        'dmf_number',
        'cofepris_status',
        'free_sale_certificate',
		'moq',
        'shelf_life',
        'storage',
        'incoterms',
        'lead_time',
        'coa',
        'gmp_certificate',
        'sds',
        'ctd_summary',		
		
		'image')
        ->orderBy('generic_name', 'asc')                      
        ->get();
 
    return response()->json([
        'status' => true,
        'data' => $products,
    ]);
}

public function getProductById($id)
{
    $product = \App\Models\Product::select(
        'id',
        'generic_name',
        'brand_names',
		'product_type',
        'form',
        'strength',
		'therapeutic_class', 
		'atc_code',
        'manufacturer_name',
        'manufacturer_country',
		'gmp_status',
        'dmf_number',
        'cofepris_status',
        'free_sale_certificate',
       'moq',
        'shelf_life',
        'storage',
        'incoterms',
        'lead_time',
        'coa',
        'gmp_certificate',
        'sds',
        'ctd_summary',
        'image',
		'special_notes'
    )->find($id);

    if (!$product) {
        return response()->json([
            'status' => false,
            'message' => 'Product not found.'
        ], 404);
    }

   
    $product->image = url('storage/' . ltrim($product->image, '/'));

    return response()->json([
        'status' => true,
        'data' => $product
    ]);
}

public function search(Request $request)
{
    $keyword = $request->get('keyword');

    $query = \App\Models\Product::with('category')
        ->when($keyword, function ($q) use ($keyword) {
            $q->where(function ($subQuery) use ($keyword) {
                $subQuery->where('generic_name', 'like', "%$keyword%")
                    ->orWhere('therapeutic_class', 'like', "%$keyword%")
                    ->orWhereHas('category', function ($q2) use ($keyword) {
                        $q2->where('name', 'like', "%$keyword%");
                    });
            });
        });

    $products = $query->get();

    return response()->json([
        'success' => true,
        'count'   => $products->count(),
        'data'    => $products,
    ]);
}

}
