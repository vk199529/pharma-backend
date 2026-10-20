<?php

namespace App\Observers;

use App\Models\Faq;
use OpenAI\Laravel\Facades\OpenAI;
use App\Services\EmbeddingService;


class FaqObserver
{
    /**
     * Handle the Faq "created" event.
     */
 public function created(Faq $faq): void
{
    $embedding = app(EmbeddingService::class)->generate($faq->question);

    if ($embedding) {
        $faq->embedding_vector = json_encode($embedding);
        $faq->save();

        \Log::info("✅ FAQ embedding created for ID: {$faq->id}");
    }
}

    /**
     * Handle the Faq "updated" event.
     */
    public function updated(Faq $faq): void
    {
        //
    }

    /**
     * Handle the Faq "deleted" event.
     */
    public function deleted(Faq $faq): void
    {
        //
    }

    /**
     * Handle the Faq "restored" event.
     */
    public function restored(Faq $faq): void
    {
        //
    }

    /**
     * Handle the Faq "force deleted" event.
     */
    public function forceDeleted(Faq $faq): void
    {
        //
    }
}
