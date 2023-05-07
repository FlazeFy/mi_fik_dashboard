<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class TypeQuestion implements Rule
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
        $type = ['event', 'task', 'account', 'others', 'group','announcement'];

        foreach ($type as $format) {
            if ($format === $value) {
                return true;
            }
        }

        return false;
    }

    public function message()
    {
        return 'Question type is not available';
    }
}
