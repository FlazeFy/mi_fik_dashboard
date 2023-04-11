<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class TypeHistory implements Rule
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
        $type = ['event', 'user', 'task', 'maintenance', 'info', 'group', 'admin', 'scheduling', 'about'];

        foreach ($type as $format) {
            if ($format === $value) {
                return true;
            }
        }

        return false;
    }

    public function message()
    {
        return 'History type is not available';
    }
}
