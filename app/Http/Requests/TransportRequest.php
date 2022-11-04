<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;

class TransportRequest extends FormRequest
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
            "branch_name" => 'required|min:3',
            "phone" => 'required|max:11|min:11|regex:/(01)[0-9]{9}/',
            "email" => 'nullable|email',
            "opening_time" => 'nullable'
        ];

        return $rules;

    }
}
