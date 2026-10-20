<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FaqCategory extends Model
{
    use CrudTrait;
    //use HasFactory;
	
	 protected $fillable = ['name'];

  public function faqs()
    {
        return $this->hasMany(Faq::class, 'faq_category_id');
    }
}
