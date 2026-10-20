<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;


   // protected $table = 'countries';

   
    protected $primaryKey = 'country_id';

 
    public $incrementing = true;
    protected $keyType = 'int';

   
    public $timestamps = false;

  
    protected $fillable = [
        'iso2',
        'short_name',
        'long_name',
        'iso3',
        'numcode',
        'un_member',
        'calling_code',
        'cctld',
    ];
}
