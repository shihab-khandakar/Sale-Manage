<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;
use Illuminate\Support\Facades\Request;

class LoginRequest extends FormRequest
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
        if (array_key_exists('email', $this->request->all())) {
            $rules = [
                'email' => 'required|max:255|email',
                'password' => 'required|min:6|string',
            ];
        }

        if(array_key_exists('phone', $this->request->all())) {
            $rules = [
                'phone' => 'required|max:11|min:11|regex:/(01)[0-9]{9}/',
                'password' => 'required|min:6|string',
            ];
        }

        return $rules;
    }
}
