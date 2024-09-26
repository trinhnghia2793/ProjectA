<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostCatalogueRequest extends FormRequest
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
            'name' => 'required',
            'canonical' => 'required|unique:routers,canonical,' . $this->id . ',module_id',
        ];
    }

    public function messages(): array
    {
        // Định nghĩa các message khi nhập sai
        return [
            'name.required' => "Bạn chưa nhập vào ô tiêu đề.",
            'canonical.required' => "Bạn chưa nhập vào ô đường dẫn.",
            'canonical.unique' => "Đường dẫn đã tồn tại. Hãy chọn đường dẫn khác.",
        ];
    }
}
