<?php
namespace App\Http\Enumeration;
use Spatie\Enum\Enum;
final class CategoryType extends Enum
 //final class لايغير القيم اي تبقى ثابتة في اي مكان 
{
    const MainCategory = 1;
    const SupCategory = 2;
}