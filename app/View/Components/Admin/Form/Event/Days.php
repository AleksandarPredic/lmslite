<?php

namespace App\View\Components\Admin\Form\Event;

use Illuminate\View\Component;

class Days extends Component
{
    /**
     * Component prop
     * @var array|null
     */
    public $value;

    /**
     * Create a new component instance.
     *
     * @param array|null $value
     */
    public function __construct($value = null)
    {
        $this->value = $value;
    }

    /**
     * Used in the blade files for select field and in contollers for validation rules
     *
     * @param bool $returnKeys
     *
     * @return array
     */
    public static function getDaysOptions(bool $returnKeys = false): array
    {
        $options = [
            0 => __('Monday'),
            1 => __('Tuesday'),
            3 => __('Wednesday'),
            4 => __('Thursday'),
            5 => __('Friday'),
            6 => __('Saturday'),
            7 => __('Sunday')
        ];

        if ($returnKeys) {
            return array_keys($options);
        }

        return $options;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.admin.form.event.days', [
            'options' => self::getDaysOptions()
        ]);
    }
}
