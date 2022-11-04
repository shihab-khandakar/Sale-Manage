<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;

class RegisterRequest extends FormRequest
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
            'name'     => 'required|string',
            'shope_name' => 'nullable',
            'email'    => 'required|email|unique:users,email,' . $id,
            'phone'    => 'required|max:11|min:11|regex:/(01)[0-9]{9}/|unique:users,phone,' . $id,
            'position' => 'nullable',
            'password' => 'required|min:6',
            'role_id'  => 'required|exists:roles,id',
            'role'  => 'required',
            'code'  => 'required|string',
            'hierarchy_id'  => 'required|exists:hierarchies,id',
       ];
         
        if (request()->isMethod('put') || request()->isMethod('patch')) {
            $rules['password']  = 'nullable|min:6';
        }

        return $rules;
    }
}
