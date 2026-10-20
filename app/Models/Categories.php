<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;

class Categories extends Model
{
    use CrudTrait;
    use HasTranslations;

    public $translatable = ['name'];

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'categories';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
 protected $fillable = ['name', 'slug', 'description', 'image', 'parent_id', 'sort_order', 'status'];
    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
public function parent()
    {
        return $this->belongsTo(Categories::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Categories::class, 'parent_id');
    }

public static function boot()
{
    parent::boot();

    // static::saving(function ($model) {
    //     $model->slug = \Str::slug($model->name);
    // });
    static::saving(function ($model) {
    $name = $model->getTranslation('name', 'en', false);

    if (is_string($name) && $name !== '') {
        $model->slug = \Illuminate\Support\Str::slug($name);
    }
});
}


public function setImageAttribute($value)
{
    $attribute_name = "image";
    $disk = "public";
    $destination_path = "uploads/categories";

    // if a base64 was sent, store it as file
    if ($value == null) {
        \Storage::disk($disk)->delete($this->{$attribute_name});
        $this->attributes[$attribute_name] = null;
    }

    if (request()->hasFile($attribute_name)) {
        $file = request()->file($attribute_name);
        $filename = time() . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs($destination_path, $filename, $disk);
        $this->attributes[$attribute_name] = $path;
    }
}


public function getImageUrlAttribute()
{
    return $this->image ? asset('storage/' . $this->image) : null;
}

public function products()
{
    return $this->hasMany(Product::class, 'category_id');
}


    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
