<?php

namespace App\Http\Requests\contact;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
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
            'name'=>[
                'required',
                'max:255',
                'string'
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('contact','email')->whereNull('deleted_at')
            ],
            'Phone'     => 'required|digits:10',
            'gender'    => 'required|in:1,2,3',
            'prof_img'  => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'doc'       => 'nullable|mimes:csv,pdf,doc,docx,jpg,png',
            // 'custom_field.*.name'   => 'nullable|string|max:255',
            // 'custom_field.*.value' => 'nullable|string|max:255',

        ];
    }

    /**
     * @return array|string[]
     */
    public function messages(): array
    {
        return [
            'email.unique' => 'Email must be unique',
        ];
    }
}
