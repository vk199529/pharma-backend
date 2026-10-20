<?php

namespace App\Observers;

use App\Models\CompanyFaq;
use App\Services\EmbeddingService;

class CompanyFaqObserver
{
    /**
     * Handle the CompanyFaq "created" event.
     */
      public function created(CompanyFaq $companyFaq): void
    {
        $embedding = app(EmbeddingService::class)->generate($companyFaq->question);

    if ($embedding) {
        $companyFaq->embedding_vector = json_encode($embedding);
        $companyFaq->save();

        \Log::info("✅ FAQ embedding created for ID: {$companyFaq->id}");
    }
    }

    /**
     * Handle the CompanyFaq "updated" event.
     */
    public function updated(CompanyFaq $companyFaq): void
    {
        //
    }

    /**
     * Handle the CompanyFaq "deleted" event.
     */
    public function deleted(CompanyFaq $companyFaq): void
    {
        //
    }

    /**
     * Handle the CompanyFaq "restored" event.
     */
    public function restored(CompanyFaq $companyFaq): void
    {
        //
    }

    /**
     * Handle the CompanyFaq "force deleted" event.
     */
    public function forceDeleted(CompanyFaq $companyFaq): void
    {
        //
    }
}
