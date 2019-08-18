<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MaterialCategoryRequest extends FormRequest
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
            'code' => 'required|unique:material_categories,code,' . $this->material_category . ',id,deleted_at,NULL|max:1',
            'name' => 'required',
            'cal' => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'code' => '分類代號',
            'name' => '分類名稱',
            'cal' => '計價欄位'
        ];
    }
}
