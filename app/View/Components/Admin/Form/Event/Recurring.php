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
     * Create a new component instance.
     *
     * @param bool $value
     */
    public function __construct(bool $value = false)
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
