<?php

namespace App\Http\Requests\Api\V1\Image;

use Illuminate\Foundation\Http\FormRequest;

class UploadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'delkey'  => 'nullable|alpha_dash',
            'comment' => 'nullable|max:255|string',
            'image'   => 'required|file|max:' . config('roda.upload.max'),
        ];
    }
}
