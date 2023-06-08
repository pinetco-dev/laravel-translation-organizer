<?php

namespace Pinetcodev\LaravelTranslationOrganizer\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;

class CheckKeyName implements ValidationRule
{
//    public $file;
    public $file;

    /**
     * Create a new rule instance.
     *
     * @param $file
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->file == '_json' && ((Str::substrCount($value, '.') > 0 && !Str::endsWith($value, '.')) || (Str::substrCount($value, '.') > 1 && Str::endsWith($value, '.')))) {
            $fail('You are not allowed to add json array');
        } else  if (Str::endsWith($value, '.') && $this->file != '_json') {
            $fail('You cannot put . (dot) at the ending of the key name');
        } else if (Str::startsWith($value, '.')) {
            $fail('You cannot put . (dot) at the starting of the key name');
        }
    }
}
