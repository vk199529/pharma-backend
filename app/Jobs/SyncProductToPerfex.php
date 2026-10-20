<?php

namespace App\Jobs;

use App\Models\Product;
use App\Services\PerfexService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SyncProductToPerfex implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

public function handle()
{
    //Log::info('Perfex Job Start', ['id' => $this->product->id]);

    try {

        $service = app(PerfexService::class);

       $payload = [
               'description'      => $this->product->generic_name,
               'rate'             => (float) $this->product->price,
               'long_description' => html_entity_decode($this->product->special_notes ?? ''),
	           'unit'             => $this->product->form,
			   'brand_names'      => $this->product->brand_names,
			 
				'brand_names'                    => $this->product->brand_names ?? '',
				'strength'                       => $this->product->strength ?? '',
				'therapeutic_class'              => $this->product->therapeutic_class ?? '',
				'atc_code'                       => $this->product->atc_code ?? '',
				'mfg_name'                       => $this->product->manufacturer_name ?? '',
				'mfg_country'                    => $this->product->manufacturer_country ?? '',
				'gmp_status'                     => $this->product->gmp_status ?? '',
				'dmf_number'                     => $this->product->dmf_number ?? '',
				'cofepris_status'                => $this->product->cofepris_status ?? '',
				'free_sale_certificate'          => $this->product->free_sale_certificate ?? '',
				'moq'                            => $this->product->moq ?? '',
				'shelf_life'                     => $this->product->shelf_life ?? '',
				'storage'                        => $this->product->storage ?? '',
				'incoterms'                      => $this->product->incoterms ?? '',
				'lead_time'                      => $this->product->lead_time ?? '',
				'c_o_a'                          => $this->product->coa ?? '',
				'msds_sds'                       => $this->product->sds ?? '',
				'ctd_summary'                    => $this->product->ctd_summary ?? '',
				'rfq_available'                  => $this->product->rfq ?? '',
				'dossier_available'              => $this->product->download_dossier ?? '',
				'wac_wholesale_acquisition_costs'=> $this->product->wac ?? '',
				'category'                       => optional($this->product->category)->name ?? '',
           ];

        // 👉 UPDATE if exists
        if ($this->product->perfex_id) {

            $response = $service->updateItem(
                $this->product->perfex_id,
                $payload
            );

             //  Log::info('Perfex Updated', ['response' => $response]);

        } 
        // 👉 CREATE if not exists
        else {

            $response = $service->createItem($payload);

                Log::info('Perfex Created', ['response' => $response]);

            if (isset($response['id'])) {
                $this->product->perfex_id = $response['id'];
                $this->product->save();
            }
        }

    } catch (\Exception $e) {

        Log::error('Perfex Job Error', [
            'message' => $e->getMessage()
        ]);
    }
}
}