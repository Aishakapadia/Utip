<?php

namespace App\Http\Requests\Backend;

use App\Rules\LaneCheck;
use Illuminate\Foundation\Http\FormRequest;

class TicketTransporterUpdateRequest extends FormRequest
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
            'transporter_id'        => 'required',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'transporter_id.required'     => 'Transporter field is required.',
        ];
    }
}
