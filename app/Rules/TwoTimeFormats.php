<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class TwoTimeFormats implements Rule
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

    public function passes($attribute, $value)
    {
        $timeFormats = ['H:i:s', 'H:i'];

        foreach ($timeFormats as $format) {
            $time = \DateTime::createFromFormat($format, $value);
            if ($time && $time->format($format) === $value) {
                return true;
            }
        }

        return false;
    }

    public function message()
    {
        return 'Time format is not available';
    }
}
