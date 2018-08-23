<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use GuzzleHttp\Client;

class Recaptcha implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        $client = new Client(['verify' => config('recaptcha.ssl_verify')]);
    
        $response = $client->post(
            config('recaptcha.post_url'),
            ['form_params'=>
                [
                    'secret'=>config('recaptcha.secret'),
                    'response'=>$value
                 ]
            ]
        );
    
        $body = json_decode((string)$response->getBody());
        return $body->success;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Please ensure that you are a human!';
    }
}
