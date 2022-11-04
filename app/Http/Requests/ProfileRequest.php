<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;

class ProfileRequest extends FormRequest
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
            "user_id" => 'required|int|exists:users,id|unique:profiles,user_id,'.$id,
            "f_name" => 'required|string|max:30',
            "l_name" => 'required|string|max:30',
            "image" => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024',
            "state" => 'required|string',
            "upazila_id" => 'required|int|exists:upazilas,id',
            "district_id" => 'required|int|exists:districts,id',
            "division_id" => 'required|int|exists:divisions,id',
            "nid" => 'required|unique:profiles,nid,'.$id,
            "birth_no" => 'nullable|unique:profiles,birth_no,'.$id,
            "birth" => 'nullable',
        ];
       
        return $rules;
    }
}
