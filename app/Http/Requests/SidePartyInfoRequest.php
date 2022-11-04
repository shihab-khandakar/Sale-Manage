<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;

class SidePartyInfoRequest extends FormRequest
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
            "name" => 'required|min:3',
            "shope_name" => 'required',
            "phone" => 'required|max:11|min:11|regex:/(01)[0-9]{9}/',
            "email" => 'nullable|email',
            "address" => 'required',
            "division_id" => 'required|exists:divisions,id',
            "district_id" => 'required|exists:districts,id',
            "upazila_id" => 'required|exists:upazilas,id',
        ];

        return $rules;

    }
}
