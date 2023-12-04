<?php

namespace App\Http\Requests\Backend;

use App\Rules\LaneCheck;
use Illuminate\Foundation\Http\FormRequest;

class TicketIBDUpdateRequest extends FormRequest
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
            'ibd_num.*'        => 'required',
            'po_num.*'        => 'required',
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'ibd_num.required'     => 'IBD Number field is required.',
            'po_num.required'     => 'PO Number field is required.',
        ];
    }
}
