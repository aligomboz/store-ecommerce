<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductTranslation extends Model
{
    protected $fillable = [
        'description' , 'short_description' ,'name'
    ];
    public $timestamps = false;
}
