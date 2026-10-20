<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class ShippingFaq extends Model
{
    //use HasFactory;
    use CrudTrait;
    use HasTranslations;

    public $translatable = ['question', 'answer'];

    protected $fillable = ['question', 'answer', 'embedding_vector'];
}
