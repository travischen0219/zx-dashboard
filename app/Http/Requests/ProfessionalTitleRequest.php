<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfessionalTitleRequest extends FormRequest
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
            'name' => 'required|unique:professional_titles,name,' . $this->professional_title . ',id,deleted_at,NULL|max:50'
        ];
    }

    public function attributes()
    {
        return [
            'name' => '職稱名稱'
        ];
    }
}
