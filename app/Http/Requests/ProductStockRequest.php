<?php

namespace App\Http\Requests;

use App\Rules\ProductQty;
use Illuminate\Foundation\Http\FormRequest;

class ProductStockRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_id' => 'required|exists:products,id',
            'sku' => 'required|min:3|max:10',
            'manage_stoke' => 'required|in:0,1',
            'in_stock' => 'required|in:0,1',
            //'qty' => 'required_if:manage_stock,==,1',
            'qty' => [new ProductQty($this->manage_stock)],
        ];

    }
    
}
