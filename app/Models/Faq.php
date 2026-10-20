<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use CrudTrait;
   // use HasFactory;
   use HasTranslations;

 public $translatable = ['question', 'answer'];
   
     protected $fillable = ['faq_category_id', 'question', 'answer','embedding_vector'];
  
public function category()
    {
        return $this->belongsTo(FaqCategory::class, 'faq_category_id');
    }
}
