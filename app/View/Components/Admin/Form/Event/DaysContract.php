<?php

namespace App\View\Components\Admin\Form\Event;

interface DaysContract
{
    /**
     * Used in the blade files for select field and in controllers for validation rules
     *
     * @param bool $returnKeys
     *
     * @return array
     */
    public static function getDaysOptions(bool $returnKeys = false): array;
}
