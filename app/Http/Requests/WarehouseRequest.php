<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;

class WarehouseRequest extends FormRequest
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
            'name' => 'required|string|unique:warehouses,name,'.$id,
            'state' => 'nullable|string|unique:warehouses,state,'.$id,
            'location_url' => 'nullable|string|unique:warehouses,location_url,'.$id,
            'upazila_id' => 'required|int|exists:upazilas,id|unique:warehouses,upazila_id,'.$id,
            'district_id' => 'required|int|exists:districts,id|unique:warehouses,district_id,'.$id,
            'division_id' => 'required|int|exists:divisions,id'
       ];

        return $rules;
    }
}
