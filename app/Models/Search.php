<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Search extends Model
{
    use HasFactory;

    protected $table = 'pharmaceutical_products';
    public $timestamps = true;

    protected $fillable = [
        'generic_name',
        'brand_names',
        'form',
        'strength',
        'therapeutic_class',
        'atc_code',
        'manufacturer_name',
        'manufacturer_country',
        'gmp_status',
        'dmf_number',
        'cofepris_status',
        'free_sale_certificate',
        'moq',
        'shelf_life',
        'storage',
        'incoterms',
        'lead_time',
        'coa',
        'gmp_certificate',
        'sds',
        'ctd_summary',
        'rfq',
        'contact_regulatory_advisor',
        'download_dossier',
		'embedding_vector',
		'image',
    ];
	
	public function category()
{
    return $this->belongsTo(\App\Models\Categories::class, 'category_id');
}

}
