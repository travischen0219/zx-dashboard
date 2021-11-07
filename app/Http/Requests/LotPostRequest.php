<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LotPostRequest extends FormRequest
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

        if ($method == 'POST') {
            $code = 'required|unique:lots,code,NULL,id,deleted_at,NULL|max:20';
        } else {
            $code = '';
        }

        return [
            'code' => $code,
            'name' => 'required',
            'customer_id' => 'required',
            'cost' => 'required|numeric',
            'start_date' => 'required|date',
            'end_date' => 'required|date'
        ];
    }

    public function attributes()
    {
        return [
            'code' => '批號',
            'name' => '案件名稱',
            'cost' => '管銷成本',
            'customer_id' => '客戶',
            'start_date' => '開始日期',
            'end_date' => '結束日期'
        ];
    }
}
