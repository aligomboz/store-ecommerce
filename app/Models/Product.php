<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use Translatable,SoftDeletes;
    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['translations'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'is_active' , 'brand_id' ,'slug' , 'sku' , 'price' , 'special_price',
        'special_price_type' , 'special_price_start','special_price_end', 'selling_price',
        'manage_stoke','qty','in_stock','viewed',
    ];
        /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = ['translations'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
        'manage_stoke' => 'boolean',
        'in_stock' => 'boolean',
    ];
    protected $dates = [
        'special_price_start', 'special_price_end',
        'start_date' , 'end_date' , 'deleted_at',
    ];
    protected $translatedAttributes = ['name' , 'description' , 'short_description'];

    public function brand(){
        return $this->belongsTo(Brand::class)->withDefault();
    }
    public function categories(){
        return $this->belongsToMany(Category::class , 'product_ctegories');
    }
    public function tags(){
        return $this->belongsToMany(Tag::class , 'product_tags');
    }
    public function getActive(){
        return $this->is_active == 0 ? ' غير مفعل' : 'مفعل ';
    }

}
