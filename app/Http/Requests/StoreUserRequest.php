<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'email' => 'required|string|email|unique:users|max:191',
            'name' => 'required|string',
            'user_catalouge_id' => 'gt:0',
            'password' => 'required|string|min:6',
            're_password' => 'required|string|same:password',
        ];
    }

    public function messages(): array
    {
        // Định nghĩa các message khi nhập sai
        return [
            'email.required' => "Bạn chưa nhập vào email.",
            'email.email' => "Email chưa đúng định dạng.",
            'email.unique' => "Email đã tồn tại.",
            'email.string' => "Email phải là dạng ký tự.",
            'email.max' => "Độ dài email tối đa là 191 ký tự.",

            'name.required' => "Bạn chưa nhập Họ và Tên.",
            'name.string' => "Họ và Tên phải là dạng ký tự.",

            'user_catalouge_id.gt' => "Bạn chưa chọn nhóm thành viên.",

            'password.required' => "Bạn chưa nhập vào mật khẩu.",

            're_password.required' => "Bạn phải nhập vào ô Nhập lại mật khẩu.",
            're_password.same' => "Mật khẩu không khớp.",
        ];
    }
}
