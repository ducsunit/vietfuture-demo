<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestRegister extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username' => 'required|string|min:3|max:50|unique:users,username',
            'password' => 'required|string|min:6|max:100',
            'age' => 'required|string|max:20',
        ];
    }
    
    public function messages(): array
    {
        return [
            'username.required' => 'Tên đăng nhập không được để trống',
            'password.required' => 'Mật khẩu không được để trống',
            'age.required' => 'Tuổi không được để trống',
            'username.unique' => 'Tên đăng nhập đã tồn tại',
            
        ];
    }
}
