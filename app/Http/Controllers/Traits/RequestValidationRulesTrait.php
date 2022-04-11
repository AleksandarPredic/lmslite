<?php

namespace App\Http\Controllers\Traits;

/**
 * Trait to store common form field validation rules used in controllers
 */
trait RequestValidationRulesTrait
{
    protected function getNameFieldRules(): array
    {
        return ['min:3', 'max:255'];
    }

    protected function getStartingAtFieldRules(): array
    {
        return ['date', 'before:ending_at'];
    }

    protected function getEndingAtFieldRules(): array
    {
        return ['date', 'after:starting_at'];
    }

    protected function getNoteFieldRules(): array
    {
        return ['min:3', 'max:255'];
    }
}
