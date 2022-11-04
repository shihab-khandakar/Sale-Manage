<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;

class PermissionRequest extends FormRequest
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
                'name' => 'required|unique:permissions,name,' . $id,
                'module_name' => 'required|string',
            ];
        

        return $rules;
    }
}
