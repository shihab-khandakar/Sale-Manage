<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;

class RoleRequest extends FormRequest
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
        $id = $this->role->id ?? null;
        return [
            'name' => 'required|unique:roles,name,' . $id,
            'permission_ids' => 'required|array',
            'permission_ids.*' => 'required',
        ];
    }
}
