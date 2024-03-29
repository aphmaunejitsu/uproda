<?php

namespace App\Rules;

use App\Services\DenyWordService;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Log;

class CheckNgWord implements Rule
{
    private $service;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(DenyWordService $service)
    {
        $this->service = $service;
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
        if (! ($denyWords = $this->service->get())) {
            return true;
        }

        $v = trim($value);

        foreach ($denyWords as $denyWord) {
            if (mb_strpos($v, $denyWord->word) !== false) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':attribute に禁止ワードが含まれています';
    }
}
