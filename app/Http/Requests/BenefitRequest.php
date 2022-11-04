<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;

class BenefitRequest extends FormRequest
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
        $id = request()->route('id') ?? null;
        $rules = [
            "user_id" => 'required|exists:users,id',
            "benefit_type_id" => 'required',
            "benefit_amount" => 'required|double',
            "date" => 'required',
            "note" => 'required',
            "start_date" => 'required',
            "end_date" => 'required'
        ];
       
        return $rules;
    }
}
