<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TranslateRequest extends FormRequest
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
            'translate_name' => 'required',
            'translate_canonical' => 'required|unique:routers,canonical,' . $this->id . ',module_id',
        ];
    }

    public function messages(): array
    {
        // Định nghĩa các message khi nhập sai
        return [
            'translate_name.required' => "Bạn chưa nhập vào tên ngôn ngữ.",
            'translate_canonical.required' => "Bạn chưa nhập vào từ khóa của ngôn ngữ.",
            'translate_canonical.unique' => "Từ khóa đã tồn tại. Hãy chọn từ khóa khác",
        ];
    }
}
