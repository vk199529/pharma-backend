<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ProductPdfController extends Controller
{


public function downloadPdf(Request $request)
{
    $data = json_decode($request->input('product_data'), true);

    // Fallback if key not present
    if (!$data) {
        abort(400, 'Invalid product data');
    }

    // Fill any missing values with defaults
    $product = (object)[
        'generic_name' => $data['generic_name'] ?? '',
        'brand_name' => $data['brand_names'] ?? '',
		'product_type' => $data['product_type'] ?? '',
        'form' => $data['form'] ?? '',
        'strength' => $data['strength'] ?? '',
        'therapeutic_class' => $data['therapeutic_class'] ?? '',
        'atc_code' => $data['atc_code'] ?? '',
        'manufacturer' => $data['manufacturer_name'] ?? '',
        'gmp_compliance' => $data['gmp_status'] ?? '',
        'dmf_cep' => $data['dmf_cep'] ?? '',
        'cofepris' => $data['cofepris_status'] ?? '',
        'free_sale_certificate' => $data['free_sale_certificate'] ?? '',
        'moq' => $data['moq'] ?? '',
        'shelf_life' => $data['shelf_life'] ?? '',
        'storage' => $data['storage'] ?? '',
        'incoterms' => $data['incoterms'] ?? '',
        'lead_time' => $data['lead_time'] ?? '',
        // 'documents' => [
        //     'Certificate of Analysis (PDF)',
        //     'GMP Certificate (PDF)',
        //     'SDS – Safety Data Sheet (PDF)',
        //     'CTD Summary (PDF)'
        // ],
         'image' => $data['image'] ?? ''
    ];

    return Pdf::loadView('pdf.product', compact('product'))
        ->download('product-details.pdf');
}

}
