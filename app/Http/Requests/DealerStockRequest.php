<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;

class DealerStockRequest extends FormRequest
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
            'dealer_id'=> 'required|exists:users,id',
            'd_code'=> 'required',
            'date'=> 'required',
            'product_id'=> 'required|exists:products,id',
            'category_id'=> 'required|exists:categories,id',
            'brand_id'=> 'required|exists:brands,id',
            'stock_by'=> 'required|int',
            'quantity_pisces'=> 'required|int',
            'booking_quantity_pisces'=> 'nullable|int',
            'type'=> 'required|string',
       ];

        return $rules;
    }
}
