<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Categories; 
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Str;

class ProductsImport implements ToModel, WithHeadingRow, WithChunkReading, ShouldQueue
{
    public function model(array $row)
    {
		//dd($row);
		   $imagePath = null;


if (!empty($row['image']) && str_contains($row['image'], '/storage/uploads/products/')) {
            // Convert to storage path: storage/uploads/products/filename.png => uploads/products/filename.png
            $imagePath = str_replace(url('storage') . '/', '', $row['image']);
            
        }
		
  $categoryId = null;

    // ✅ Find or create the category by name
  if (!empty($row['category'])) {
    $category = Categories::firstOrCreate(
        ['name' => trim($row['category'])],
        ['slug' => \Str::slug($row['category'])]
    );
    $categoryId = $category->id;
}

	
      return new Product([
    'generic_name' => $row['generic_name'] ?? null,
    'brand_names' => $row['brand_names'] ?? null,
	'product_type' => $row['product_type'] ?? null,
    'form' => $row['form'] ?? null,
	'category_id' => $categoryId,
    'strength' => $row['strength'] ?? null,
    'therapeutic_class' => $row['therapeutic_class'] ?? null,
    'atc_code' => $row['atc_code'] ?? null,
    'manufacturer_name' => $row['manufacturer_name'] ?? null,
    'manufacturer_country' => $row['manufacturer_country'] ?? null,
    'gmp_status' => $row['gmp_status'] ?? null,
    'dmf_number' => $row['dmf_number'] ?? null,
    'cofepris_status' => $row['cofepris_status'] ?? null,
    'free_sale_certificate' => $row['free_sale_certificate'] ?? null,
    'moq' => $row['moq'] ?? null,
    'shelf_life' => $row['shelf_life'] ?? null,
    'storage' => $row['storage'] ?? null,
    'incoterms' => $row['incoterms'] ?? null,
    'lead_time' => $row['lead_time'] ?? null,
    'coa' => $row['coa'] ?? null,
    /* 'gmp_certificate' => $row['gmp_certificate'] ?? null, */
    'sds' => $row['msds_sds'] ?? null,
    'ctd_summary' => $row['ctd_summary'] ?? null,
    'rfq' => $row['rfq_available'] ?? null,
    'contact_regulatory_advisor' => $row['regulatory_advisor'] ?? null,
    'download_dossier' => $row['dossier_available'] ?? null,
    'price' => isset($row['price']) ? floatval(str_replace(',', '', $row['price'])) : 0.00,
    'wac' => isset($row['wac']) ? floatval(str_replace(',', '', $row['wac'])) : 0.00,
     'special_notes' => $row['special_notes'] ?? null,
      'image' => $imagePath, 
	  
	    'show_on_homepage' => 1,
    'status' => 1,
     ]);

    }
	
	 public function chunkSize(): int
    {
        return 100;
    }
}
