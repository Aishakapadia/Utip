<?php

namespace App\Http\Requests\Backend;

use App\Rules\LaneCheck;
use Illuminate\Foundation\Http\FormRequest;

class VehicleNumberUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'new_vehicle_number'     => 'required',
            'new_mobile_number'     => 'required',
            'vehicle_type_id'     => 'required',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'new_vehicle_number.required'     => 'New vehicle number is required.',
            'new_mobile_number.required'     => 'New mobile number is required.',
            'vehicle_type_id.required'     => 'Vehicle type is required.',
        ];
    }
}
