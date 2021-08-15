<?php

namespace App\Rules;

use App\Services\GoogleRecaptchaService;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Log;

class GoogleRecaptcha implements Rule
{
    private $service;
    private $uuid;
    private $ipaddr;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(
        ?string $uuid,
        ?string $ipaddr,
        GoogleRecaptchaService $service
    ) {
        //
        $this->service = $service;
        $this->uuid = $uuid;
        $this->ipaddr = $ipaddr;
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
        return $this->service->verify(
            $this->uuid,
            $this->ipaddr,
            $value,
            config('roda.google.recaptcha.cache', 300),
        );
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'BOT と判断された';
    }
}
