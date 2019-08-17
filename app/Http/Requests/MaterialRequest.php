<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MaterialRequest extends FormRequest
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
            'fullName' => 'required',
            'unit' => 'required|numeric|not_in:0',
            'fullCode' => 'required|unique:materials,fullCode,' . $this->material . ',id,deleted_at,NULL|max:50'
        ];
    }

    public function attributes()
    {
        return [
            'fullName' => '品名',
            'unit' => '單位',
            'fullCode' => '物料編號',
            'fullCode.required' => '物料編號 不完整'
        ];
    }

    public function messages()
    {
        return [
            'fullCode.required' => '物料編號 不完整'
        ];
    }
}
