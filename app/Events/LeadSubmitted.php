<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LeadSubmitted
{
    use Dispatchable, SerializesModels;

    public $leadData;

    public function __construct(array $leadData)
    {
        $this->leadData = $leadData;
    }
}
