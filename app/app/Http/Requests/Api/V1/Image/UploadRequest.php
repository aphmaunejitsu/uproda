<?php

namespace App\Http\Requests\Api\V1\Image;

use App\Models\DenyWord;
use App\Models\ImageHash;
use App\Repositories\ImageHashRepository;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\CheckImageHash;
use App\Rules\CheckNgWord;
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
        $max = config('roda.upload.max') * 1024;
        return [
            'delkey'  => 'nullable|alpha_dash',
            'comment' => [
                'nullable',
                'max:255',
                'string',
                new CheckNgWord(),
            ],
            'file'    =>  [
                'required',
                'max:' . $max,
                new CheckImageHash(),
            ],
            'hash'    => 'required|uuid'
        ];
    }
}
