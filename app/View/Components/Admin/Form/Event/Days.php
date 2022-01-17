<?php

namespace App\View\Components\Admin\Form\Event;

use Illuminate\View\Component;

class Days extends Component implements DaysContract
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
     * @param bool $disabled
     */
    public function __construct($value = null)
    {
        $this->value = $value;
    }

    /**
     * Used in the blade files for select field and in controllers for validation rules
     *
     * @param bool $returnKeys
     *
     * @return array
     */
    public static function getDaysOptions(bool $returnKeys = false): array
    {
        $options = [
            1 => __('Monday'),
            2 => __('Tuesday'),
            3 => __('Wednesday'),
            4 => __('Thursday'),
            5 => __('Friday'),
            6 => __('Saturday'),
            0 => __('Sunday')
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
