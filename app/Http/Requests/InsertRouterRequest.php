<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InsertRouterRequest extends FormRequest
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
            'sap_id'        => 'required|min:18|max:18',
            'hostname'      => 'required|max:15|unique:routers,hostname|ipv4',
            'mac_address'   => ['required', 'max:17']
        ];
    }
}
