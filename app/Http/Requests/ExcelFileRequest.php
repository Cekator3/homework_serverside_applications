<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExcelFileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'file' => [
                'required',
                'file',
                'mimes:xlsx,xls',
            ],
            'ignoreErrors' => [
                'required',
                'boolean'
            ],
            'isUpdatesAllowed' => [
                'required',
                'boolean'
            ]
        ];
    }

    /**
     * Custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'file.required' => 'The file is required.',
            'file.file' => 'The uploaded item must be a valid file.',
            'file.mimes' => 'The file must be an Excel file of type: xlsx or xls',
        ];
    }
}
