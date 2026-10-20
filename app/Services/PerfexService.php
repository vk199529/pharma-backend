<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PerfexService
{
    private $url = 'https://crm.panamfarma.com/api/items';
    private $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyIjoiRm9yZXZlciBJdGVtcyIsIm5hbWUiOiIiLCJBUElfVElNRSI6MTc3NzAwOTE0Nn0.7TYHBom7uWVN0EytHgpxMGvsLggRFeohuw5GWVQMjg4';

    public function createItem($product)
    {
        try {

            $response = Http::withoutVerifying()
                ->withHeaders([
                    'authtoken' => $this->token,
                    'Content-Type' => 'application/json'
                ])
                ->post($this->url, [
                 'description'      => $product['description'] ?? '',
                'rate'             => $product['rate'] ?? 0,
                'long_description' => $product['long_description'] ?? '',
				'unit'             => $product['unit'] ?? '',
				'custom_fields' => [
                              'items' => [
                             7 => $product['brand_names'] ?? '',
							 10 => $product['strength'] ?? '',
							 11 => $product['therapeutic_class'] ?? '',
                             12 => $product['atc_code'] ?? '',
                             13 => $product['mfg_name'] ?? '',
                             14 => $product['mfg_country'] ?? '',
                             15 => $product['gmp_status'] ?? '',
                             16 => $product['dmf_number'] ?? '',
                             17 => $product['cofepris_status'] ?? '',
                             18 => $product['free_sale_certificate'] ?? '',
                             19 => $product['moq'] ?? '',
                             20 => $product['shelf_life'] ?? '',
							 21 => $product['storage'] ?? '',
                             22 => $product['incoterms'] ?? '',
                             23 => $product['lead_time'] ?? '',
                             24 => $product['c_o_a'] ?? '',
                             25 => $product['msds_sds'] ?? '',
                             26 => $product['ctd_summary'] ?? '',
                             27 => $product['rfq_available'] ?? '',
                             29 => $product['dossier_available'] ?? '',
                             30 => $product['wac_wholesale_acquisition_costs'] ?? '',
                             31 => $product['category'] ?? '',
                                 ]
    ]
                ]);

            
Log::info('Perfex API Request', [
    'product' => $product
]);
            Log::info('Perfex API Response', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            // ❗ agar fail hua
            if ($response->failed()) {
                Log::error('Perfex API Failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
            }

            return $response->json();

        } catch (\Exception $e) {

            Log::error('Perfex Exception', [
                'message' => $e->getMessage(),
                'line' => $e->getLine()
            ]);

            return [
                'status' => false,
                'message' => $e->getMessage()
            ];
        }
    }
	
	public function updateItem($perfexId, $product)
{
    try {

        $url = $this->url . '/' . $perfexId;

        $response = Http::withoutVerifying()
            ->withHeaders([
                'authtoken' => $this->token,
                'Content-Type' => 'application/json'
            ])
            ->put($url, [
                'description'      => $product['description'] ?? null,
                'rate'             => $product['rate'] ?? 0,
                'long_description' => $product['long_description'] ?? null,
				'unit'             => $product['unit'] ?? null,
				'custom_fields' => [
                              'items' => [
                             7 => $product['brand_names'] ?? null,
							 10 => $product['strength'] ?? null,
							 11 => $product['therapeutic_class'] ?? null,
                             12 => $product['atc_code'] ?? null,
                             13 => $product['mfg_name'] ?? null,
                             14 => $product['mfg_country'] ?? null,
                             15 => $product['gmp_status'] ?? null,
                             16 => $product['dmf_number'] ?? null,
                             17 => $product['cofepris_status'] ?? null,
                             18 => $product['free_sale_certificate'] ?? null,
                             19 => $product['moq'] ?? null,
                             20 => $product['shelf_life'] ?? null,
							 21 => $product['storage'] ?? null,
                             22 => $product['incoterms'] ?? null,
                             23 => $product['lead_time'] ?? null,
                             24 => $product['c_o_a'] ?? null,
                             25 => $product['msds_sds'] ?? null,
                             26 => $product['ctd_summary'] ?? null,
                             27 => $product['rfq_available'] ?? null,
                             29 => $product['dossier_available'] ?? null,
                             30 => $product['wac_wholesale_acquisition_costs'] ?? null,
                             31 => $product['category'] ?? null,
                                 ]
    ]
            ]);

        /* Log::info('Perfex Update Request', [
            'perfex_id' => $perfexId,
            'payload' => $product
        ]);

        Log::info('Perfex Update Response', [
            'status' => $response->status(),
            'body' => $response->body()
        ]); */

        if ($response->failed()) {
            Log::error('Perfex Update Failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
        }

        return $response->json();

    } catch (\Exception $e) {

        Log::error('Perfex Update Exception', [
            'message' => $e->getMessage()
        ]);

        return [
            'status' => false,
            'message' => $e->getMessage()
        ];
    }
}
}