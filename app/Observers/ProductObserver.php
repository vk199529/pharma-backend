<?php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Event;
use App\Services\EmbeddingService;


class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
public function created(Product $product): void
{
    $text = trim("{$product->generic_name} {$product->brand_names} {$product->therapeutic_class}");

    $embedding = app(EmbeddingService::class)->generate($text);

    if ($embedding) {
        Event::fakeFor(function () use ($product, $embedding) {
            $product->embedding_vector = json_encode($embedding);
            $product->save();
        });
        

        \Log::info("✅ Product embedding created for ID: {$product->id}");
    }
}

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
{
    // if (!$product->wasChanged(['generic_name', 'brand_names', 'therapeutic_class'])) {
    //     return; // Skip if nothing relevant changed
    // }

    // $text = trim("{$product->generic_name} {$product->brand_names} {$product->therapeutic_class}");
    // if (empty($text)) return;

    // $response = Http::withToken(env('OPENAI_API_KEY'))->post('https://api.openai.com/v1/embeddings', [
    //     'model' => 'text-embedding-3-large',
    //     'input' => $text,
    // ]);

    // if ($response->successful() && isset($response['data'][0]['embedding'])) {
    //     $product->embedding_vector = json_encode($response['data'][0]['embedding']);
    //     $product->save();
    //     \Log::info("🔁 Product embedding updated for ID: {$product->id}");
    // }
}


    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        //
    }
}
