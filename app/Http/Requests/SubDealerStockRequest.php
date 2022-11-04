<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;

class SubDealerStockRequest extends FormRequest
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
            "sub_dealer_id" => 'required|exists:users,id',
            "sd_code" => 'required',
            "date" => 'required',
            "product_id" => 'required|exists:products,id',
            "category_id" => 'required|exists:categories,id',
            "brand_id" => 'required|exists:brands,id',
            "stock_by" => 'required|exists:users,id',
            "quantity_pisces" => 'required',
            "booking_quantity_pisces" => 'nullable',
        ];
       
        return $rules;
    }
}
