<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class EitherNumericOrArray implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (is_numeric($value)) {
            return true; // It's a single numeric value
        }

        if (is_array($value) && count($value) > 0 && !array_diff($value, array_filter($value, 'is_numeric'))) {
            return true; // It's an array of numeric values
        }

        return false; // Neither a single numeric value nor an array of numeric values
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'De docenten zijn niet correct doorgevoerd.';
    }
}
