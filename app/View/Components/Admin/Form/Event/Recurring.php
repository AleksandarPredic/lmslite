<?php

namespace App\View\Components\Admin\Form\Event;

use Illuminate\View\Component;

class Recurring extends Component
{
    /**
     * Component prop
     * @var mixed
     */
    public $value;

    /**
     * Create a new component instance.
     *
     * @param null|mixed $value
     */
    public function __construct($value = null)
    {
        $this->value = $value;
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
