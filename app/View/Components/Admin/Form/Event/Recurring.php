<?php

namespace App\View\Components\Admin\Form\Event;

use Illuminate\View\Component;

class Recurring extends Component
{
    /**
     * Component prop
     * @var bool
     */
    public $value;

    /**
     * Component prop
     * @var bool
     */
    public $disabled;

    /**
     * Create a new component instance.
     *
     * @param bool $value
     * @param bool $disabled
     */
    public function __construct(bool $value = false, bool $disabled = false)
    {
        $this->value = $value;
        $this->disabled = $disabled;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.admin.form.event.recurring', [
            'options' => [
                0 => __('No'),
                1 => __('Yes')
            ]
        ]);
    }
}
