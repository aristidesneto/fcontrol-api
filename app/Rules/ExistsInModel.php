<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ExistsInModel implements ValidationRule
{
    protected string $modelName;

    protected string $column;

    public function __construct(string $modelName, string $column = 'id')
    {
        $this->modelName = $modelName;

        $this->column = $column;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $builder = app()->make($this->modelName)->newQuery();

        if (! $builder->where($this->column, $value)->exists()) {
            $fail("The selected $builder->from id is invalid");
        }
    }
}
