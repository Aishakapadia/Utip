<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class TransporterUpdateRequest extends FormRequest
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
        $id = \Request::segment(4);
        
        $rules = [
            'title' => 'required|min:3',
            'sap_code' => 'required|unique:transporters,id,' . $id,
//            'slug'  => 'required|unique:pages',
            'sort'  => 'required|numeric',
        ];

        return $rules;
    }

    public function attributes()
    {
        return [
            'title' => 'Title',
            'sap_code' => 'Code',
//            'slug'  => 'Slug',
            'sort'  => 'Sort',
        ];
    }
}
