<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;

class CompanyOrderRequest extends FormRequest
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
        $rules = [
            "side_party_information_id" => 'nullable',
            "transport_id" => 'nullable',
            "d_code" => 'required',
            "date" => 'required',
            "product_id" => 'required|exists:products,id',
            "category_id" => 'required|exists:categories,id',
            "brand_id" => 'required|exists:brands,id',
            "quantity" => 'required',
            // "sub_total" => 'int',
        ];
       
        return $rules;
    }
}
