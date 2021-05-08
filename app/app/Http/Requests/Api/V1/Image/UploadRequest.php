<?php

namespace App\Http\Requests\Api\V1\Image;

use App\Models\ImageHash;
use App\Repositories\ImageHashRepository;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\CheckImageHash;
use Illuminate\Support\Facades\Log;

class UploadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
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
            'file'    =>  [
                'required',
                'max:' . config('roda.upload.max'),
                new CheckImageHash(),
            ],
            'hash'    => 'required|uuid'
        ];
    }
}
