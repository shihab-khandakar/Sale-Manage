<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    protected function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    protected function rules(): array
    {
        // $id = request()->route('id') ?? null;

        $rules = [
            "name" => 'required',
            "brand_id" => 'required|int|exists:brands,id',
            "category_id" => 'required|int|exists:categories,id',
            "stock_in_price" => 'required',
            "stock_out_price" => 'nullable',
            "retailer_sale_price" => 'nullable',
            "offer_id" => 'nullable',
            "code" => 'required',
            'image'  => 'nullable|mimes:jpeg,png,jpg,gif,svg|max:1024',
        ];
        
        return $rules;
    }
}
