<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Rfq extends Model
{
    use CrudTrait;
   // use HasFactory;
   use HasTranslations;

   public $translatable = ['product_name', 'name', 'company',];

   protected $table = 'rfq_cotizar';
    public $timestamps = true;

    protected $fillable = [
    'product_id',
    'product_name',
    'sku',
    'name',
	'company',
    'email',
    'number',
    'license',
    'quantity',
    'country',
    'incoterm',
];

}
