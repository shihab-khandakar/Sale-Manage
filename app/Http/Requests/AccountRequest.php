<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;

class AccountRequest extends FormRequest
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
            'user_id' => 'required|int|exists:users,id|unique:accounts,user_id,'.$id,
            'account_number' => 'required|string|digits_between:10,20|unique:accounts,account_number,'.$id,
            // 'bank_id' => 'required|int|exists:users,id',
            'bank_id' => 'required|exists:banks,id',
            'credit' => 'nullable',
            'debit'  => 'nullable',
            'discount' => 'nullable|numeric|digits_between:1,100',
            'note'     => 'nullable|string|max:500',
        ];

        return $rules;
    }
}
