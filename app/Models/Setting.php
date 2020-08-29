<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Boolean;

class Setting extends Model
{
    use Translatable; //بدل ما اعمل علاقة
    protected $with = ['translations'];//ترجع ترجمة

    protected $fillable = ['key' , 'is_translatable' , 'plain_value'];

    protected $casts = [ // تغير النوع
        'is_translatable' =>'boolean',
    ];
    protected $translatedAttributes = ['value'];
    
    public static function setMany($setting){
        foreach($setting as $key => $value){
            self::set($key , $value); //self يعني داخل الكلاس الي انا فيه   
        }
    }

    public static function set($key , $value) {
        if($key ==='translatable'){
            return static::setTranslatableSettings($value);
        }
        if(is_array($value)){
            $value = json_encode($value);// عشان اقدر اخزن في الاراي دون اي مشاكل لانه سترنق
        }
        static::updateOrCreate(['key' =>$key ],['plain_value' =>$value]);
    }

    public static function setTranslatableSettings($setting = []){
        foreach($setting as $key => $value){
            static::updateOrCreate(['key' => $key] , [
                'is_translatable' => true,
                'value' => $value,
            ]);
        }
    }
}
