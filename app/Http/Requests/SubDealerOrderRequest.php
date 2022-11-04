<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;

class SubDealerOrderRequest extends FormRequest
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
            "dealer_id" => 'required|exists:users,id',
            "offer_id" => 'nullable|exists:offers,id',
            "side_party_information_id" => 'nullable|exists:side_party_infos,id',
            "transport_id" => 'nullable|exists:transports,id',
            "d_code" => 'required',
            "date" => 'required',
            "without_commission_bill" => 'required',
            "total_bill" => 'required',
            "product_id" => 'required|exists:products,id',
            "category_id" => 'required|exists:categories,id',
            "brand_id" => 'required|exists:brands,id',
            "quantity" => 'required',
            "sub_total" => 'required',
            // "gift_item" => 'int'
        ];
       
        return $rules;
    }
}
