<?php

namespace App\Http\Requests\Admin\Brand;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBrandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'bail',
                'required',
                'string',
                'min:2',
                'max:255',
                Rule::unique('brands', 'name')
                    ->ignore($this->route('uuid'), 'uuid')
                    ->where('country_id', admin()->user()->country_id)
                    ->whereNull('deleted_at'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم الماركة مطلوب',
            'name.string'   => 'اسم الماركة يجب أن يكون نصاً',
            'name.min'      => 'اسم الماركة يجب أن يكون على الأقل حرفين',
            'name.max'      => 'اسم الماركة يجب أن لا يتجاوز 255 حرفاً',
            'name.unique'   => 'اسم الماركة موجود بالفعل في نفس البلد',
        ];
    }
}
