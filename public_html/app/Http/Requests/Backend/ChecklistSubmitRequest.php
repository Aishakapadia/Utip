<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;
use App\Question;

class ChecklistSubmitRequest extends FormRequest
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
        return [
            'transporter'         => 'required',
            'site_id_from'        => 'required',
            'site_id_to'          => 'required',
            'inspection_site'          => 'required',
            'vehicle_number'      => 'required',
            'driver_name'         => 'required',
            'driver_nic'          => 'required',
            'type'          => 'required',
            'vehicle_type'          => 'required',
            'question'            => 'required|array|size:' . count(Question::all())
        ];
    }
}
