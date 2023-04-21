<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class TypeSuggest implements Rule
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
        $type = ['feedback_performance', 'feedback_design', 'feedback_services', 'feedback_security', 'feedback_features'];

        foreach ($type as $format) {
            if ($format === $value) {
                return true;
            }
        }

        return false;
    }

    public function message()
    {
        return 'Suggest type is not available';
    }
}
