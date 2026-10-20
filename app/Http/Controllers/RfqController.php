<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rfq;
use App\Events\LeadSubmitted;


class RfqController extends Controller
{
 public function storeFormData(Request $request)
{
    

 try {
    $validated = $request->validate([
        'product_id'    => 'nullable|integer',
        'product_name'  => 'required|string',
        'name'          => 'required|string',
		'company'       => 'nullable|string',
        'email'         => 'required|email',
        'country'       => 'required|string',
        'number'        => 'nullable|string',
        'quantity'      => 'nullable|string'
    ]);

    \App\Models\Rfq::create($validated);

$crmData = [
    'name'        => $validated['name'],
    'email'       => $validated['email'],
    'phonenumber' => $validated['number'] ?? '',   
    'company'     => $validated['company'] ?? '',
    'country'     => $validated['country'],
    'assigned'    => '2',
    'status'      => '3',
    'source'      => '3',
    'custom_fields' => [
        'leads' => [
            52 => $validated['product_name'],
            43 => $validated['quantity'] ?? '',
        ]
    ],
];


    event(new LeadSubmitted($crmData));

    return response()->json(['success' => true]);

} catch (\Illuminate\Validation\ValidationException $e) {
    return response()->json([
        'success' => false,
        'errors' => $e->errors()
    ], 422);
}  catch (\Exception $e) {
    \Log::error('RFQ Submission Error: ' . $e->getMessage(), [
        'trace' => $e->getTraceAsString(),
    ]);
    return response()->json([
        'success' => false,
        'message' => $e->getMessage(),
    ], 500);
}


}


}
