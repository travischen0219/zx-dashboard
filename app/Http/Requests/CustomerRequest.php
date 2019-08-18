<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
        $method = $this->method();

        return [
            'fullName' => 'required|string',
            'shortName' => 'required|string',
            'gpn' => 'nullable|max:8'
        ];
    }

    public function attributes()
    {
        return [
            'fullName' => '全名',
            'shortName' => '簡稱',
            'gpn' => '統一編號'
        ];
    }
}
