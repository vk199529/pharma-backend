<?php

namespace App\Observers;

use App\Models\ShippingFaq;
use App\Services\EmbeddingService;

class ShippingFaqObserver
{
    /**
     * Handle the ShippingFaq "created" event.
     */
    public function created(ShippingFaq $shippingFaq): void
    {
      
       $embedding = app(EmbeddingService::class)->generate($shippingFaq->question);

    if ($embedding) {
        $shippingFaq->embedding_vector = json_encode($embedding);
        $shippingFaq->save();

        \Log::info("✅ FAQ embedding created for ID: {$shippingFaq->id}");
    }
    }

    /**
     * Handle the ShippingFaq "updated" event.
     */
    public function updated(ShippingFaq $shippingFaq): void
    {
        //
    }

    /**
     * Handle the ShippingFaq "deleted" event.
     */
    public function deleted(ShippingFaq $shippingFaq): void
    {
        //
    }

    /**
     * Handle the ShippingFaq "restored" event.
     */
    public function restored(ShippingFaq $shippingFaq): void
    {
        //
    }

    /**
     * Handle the ShippingFaq "force deleted" event.
     */
    public function forceDeleted(ShippingFaq $shippingFaq): void
    {
        //
    }
}
