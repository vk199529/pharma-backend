<?php

namespace App\Http\Controllers;

use App\Services\PerfexService;

class PerfexController extends Controller
{
    public function syncProduct(PerfexService $perfex)
    {
        $product = [
            'name' => 'Test Product Laravel',
            'description' => 'From Laravel',
            'price' => 200
        ];

        $response = $perfex->createItem($product);

        return response()->json($response);
    }
}