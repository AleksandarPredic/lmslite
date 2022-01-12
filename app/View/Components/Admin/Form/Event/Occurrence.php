<?php

namespace App\View\Components\Admin\Form\Event;

use App\Models\Event;
use Illuminate\View\Component;

class Occurrence extends Component
{
    /**
     * Component prop
     * @var null|string
     */
    public $value;

    /**
     * Create a new component instance.
     *
     * @param null|string $value
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
        return view('components.admin.form.event.occurrence', [
            'options' => Event::getOccurrenceOptions()
        ]);
    }
}
