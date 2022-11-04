<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;

class CompanyStockRequest extends FormRequest
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
            'warehouse_id'=> 'required|exists:warehouses,id',
            'date'=> 'required',
            'product_id'=> 'required|array',
            'product_id.*'=> 'required|exists:products,id',
            'category_id'=> 'required|array',
            'category_id.*'=> 'required|exists:categories,id',
            'brand_id'=> 'required|array',
            'brand_id.*'=> 'required|exists:brands,id',
            'quantity'=> 'required|array',
            'quantity.*'=> 'required',
       ];
       
        return $rules;
    }
}
