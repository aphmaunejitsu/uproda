<?php

namespace App\Rules;

use App\Models\ImageHash;
use App\Repositories\ImageHashRepository;
use App\Services\Traits\ImageTrait;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\UploadedFile;

class CheckImageHash implements Rule
{
    use ImageTrait;

    private $imageHash;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->imageHash = new ImageHashRepository(new ImageHash());
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (! $value instanceof UploadedFile) {
            return false;
        }

        $hash = $this->getHash($value);
        return !($this->imageHash->isNg($hash));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'アップロードできないファイルです';
    }
}
