<?php

namespace App\Listeners;

use App\Events\LeadSubmitted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendLeadToCRM
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
   public function handle(LeadSubmitted $event): void
    {
        $postData = $event->leadData;

        /* $url = 'https://crm.panamericanexports.com/api/leads';
        $apiKey = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyIjoicGFuYW1mYXJtYV9sYXJhdmVsIiwibmFtZSI6InBhbmFtZmFybWEgbGFyYXZlbCIsIkFQSV9USU1FIjoxNzQ5NDY1NTcyfQ.HdAGaxp8qlNgo56UNcQSnQ6z1Hj9GyX2mvX98bbZ2Ww';
 */
 
         $url = 'https://crm.panamfarma.com/api/leads';
         $apiKey = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyIjoiRm9yZXZlciIsIm5hbWUiOiIiLCJBUElfVElNRSI6MTc2MjQwNDE0OX0.yRXjI7BsMzdVV2sdeEoBr2IwcMZPl_gSb_bLqIA9_2k';		 
           
		   $ch = curl_init($url);
                  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                  curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
                  curl_setopt($ch, CURLOPT_HTTPHEADER, [
                         'Content-Type: application/x-www-form-urlencoded',
                           'authtoken: ' . $apiKey
         ]);

           $response = curl_exec($ch);

        if (curl_errno($ch)) {
            \Log::error('CRM Lead API Error: ' . curl_error($ch));
        } else {
            \Log::info('CRM Response: ' . $response);
        }

        curl_close($ch);

    }
}