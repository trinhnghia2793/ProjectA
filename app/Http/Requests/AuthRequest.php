<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
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
        // Định nghĩa các rule cho các trường cần nhập
        return [
            'email' => 'required|email',
            'password' => 'required',
        ];
    }

    public function messages(): array
    {
        // Định nghĩa các message khi nhập sai
        return [
            'email.required' => "Bạn chưa nhập vào email.",
            'email.email' => "Email chưa đúng định dạng",
            'password.required' => "Bạn chưa nhập vào mật khẩu.",
        ];
    }
}
