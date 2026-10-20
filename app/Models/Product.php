<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use App\Jobs\SyncProductToPerfex;


class Product extends Model
{
    use CrudTrait;
    //use HasFactory;
    use HasTranslations;


    protected $table = 'pharmaceutical_products';
    public $timestamps = true;

    protected $translatable = [
        'generic_name',
        'brand_names',
        'product_type',
        'form',
        'therapeutic_class',
        'manufacturer_name',
        'manufacturer_country',
        'gmp_status',
        'cofepris_status',
        'free_sale_certificate',
        'shelf_life',
        'storage',
        'incoterms',
        'lead_time',
        'special_notes',
    ];

    
    protected $fillable = [
        'generic_name',
        'brand_names',
		'product_type',
		'category_id',
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
        'price',
		'wac',
		'special_notes',
		'image',
		'status',
		'show_on_homepage',
		'embedding_vector',
		'perfex_id',
    ];

    public function getPrintViewAttribute(){
    return "crud::operations.print";
}


public function setImageAttribute($value)
{
    $attribute_name = "image";
    $disk = "public";
    $destination_path = "uploads/products";

    // If null, delete old image
    if ($value === null) {
        if (!empty($this->{$attribute_name})) {
            \Storage::disk($disk)->delete($this->{$attribute_name});
        }
        $this->attributes[$attribute_name] = null;
        return;
    }

    // If it's an uploaded file (from form)
    if (request()->hasFile($attribute_name)) {
        $file = request()->file($attribute_name);
        $filename = time() . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs($destination_path, $filename, $disk);
        $this->attributes[$attribute_name] = $path;
        return;
    }

    
    if (is_string($value)) {
        $this->attributes[$attribute_name] = $value;
    }
}



public function getImageUrlAttribute()
{
    return $this->image ? asset('storage/' . $this->image) : null;
}

public function category()
{
    return $this->belongsTo(Categories::class, 'category_id');
}

protected static function booted()
{
    static::created(function ($product) {
        SyncProductToPerfex::dispatchSync($product);
    });

    static::updated(function ($product) {

        if ($product->perfex_id) {
            SyncProductToPerfex::dispatchSync($product);
        }

    });
}


}
