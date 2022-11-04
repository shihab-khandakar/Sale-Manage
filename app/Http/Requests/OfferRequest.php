<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;

class OfferRequest extends FormRequest
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
            "title" => 'required|string|max:250',
            "type" => 'required|max:20',
            "start_date" => 'required',
            "end_date" => 'required',
            "quantity_or_amount" => 'required|int|min:1',
            "rules" => 'required|string|max:500'
        ];

        return $rules;
    }
}
