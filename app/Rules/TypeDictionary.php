<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class TypeDictionary implements Rule
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
        $type = ['SLC-001', 'INF-001', 'ATT-001', 'NTF-001', 'QST-001', 'FBC-001','TAG-001'];

        foreach ($type as $format) {
            if ($format === $value) {
                return true;
            }
        }

        return false;
    }

    public function message()
    {
        return 'Dictionary type is not available';
    }
}
