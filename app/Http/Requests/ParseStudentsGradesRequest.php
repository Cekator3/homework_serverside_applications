<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ParseStudentsGradesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'file' => [
                'required',
                'file',
                'mimes:xlsx,xls',
            ],
        ];
    }
}
