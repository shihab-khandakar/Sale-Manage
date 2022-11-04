<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;

class DealerStockHistoriesRequest extends FormRequest
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
            'dealer_stock_id'=> 'required|exists:dealer_stocks,id',
            'date_time'=> 'required|string',
            'quantity_pisces'=> 'required|int',
            'type'=> 'required|string'
        ];

        return $rules;
    }
}
