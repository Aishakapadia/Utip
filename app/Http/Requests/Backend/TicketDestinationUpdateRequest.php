<?php

namespace App\Http\Requests\Backend;

use App\Rules\LaneCheck;
use Illuminate\Foundation\Http\FormRequest;

class TicketDestinationUpdateRequest extends FormRequest
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
            'site_id_from'        => 'required',
            'site_id_to'          => ['required', new LaneCheck]
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'to_site_id.required'     => 'To Site is required.',
        ];
    }
}
