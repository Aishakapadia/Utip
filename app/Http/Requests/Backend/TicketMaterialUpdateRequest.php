<?php

namespace App\Http\Requests\Backend;
use Illuminate\Foundation\Http\FormRequest;

class TicketMaterialUpdateRequest extends FormRequest
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
            'material_type.*'     => 'required',
            'material_id.*'       => 'required',
            'unit_id.*'           => 'required',
            'quantity.*'          => 'required',
            'weight.*'            => 'required',
            'po_number.*'         => 'required'
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'material_id.*.required'       => 'The Material Code field is required.',
            'material_type.*.required'     => 'The Material Type field is required.',
            'unit_id.*.required'           => 'The Unit field is required.',
            'quantity.*.required'          => 'The Quantity field is required.',
            'weight.*.required'            => 'The Weight field is required.',
            'po_number.*.required'         => 'The PO Number field is required.'
            
        ];
    }
}
