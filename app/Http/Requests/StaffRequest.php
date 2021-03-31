<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StaffRequest extends FormRequest
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
            $staff_code = 'required|unique:users,staff_code|max:20';
            $username = 'required|unique:users,username|max:50';
            $email = 'required|unique:users,email|max:120';
            $password = 'required|min:8|confirmed';
        } else {
            $staff_code = '';
            $username = '';
            $email = '';
            $password = 'nullable|min:8|confirmed';
        }

        return [
            'staff_code' => $staff_code,
            'fullname' => 'required|string',
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'access_id' => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'staff_code' => '員工編號',
            'fullname' => '姓名',
            'email' => 'Email',
            'username' => '帳號',
            'password' => '密碼',
            'access_id' => '權限角色'
        ];
    }
}
