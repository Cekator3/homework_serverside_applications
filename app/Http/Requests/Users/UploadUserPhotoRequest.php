<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class UploadUserPhotoRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        $maxSize = config('user_photos.max_filesize');
        return [
            'file' => "required|file|mimes:jpg,jpeg,png,gif|max:$maxSize",
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ];
    }
}
