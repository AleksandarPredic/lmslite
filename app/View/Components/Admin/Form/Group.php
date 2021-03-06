<?php

namespace App\View\Components\Admin\Form;

use App\Models\Group as GroupModel;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Group extends Component
{
    /**
     * Component prop
     * @var int
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
     * @return void
     */
    public function __construct(int $value = 0, bool $disabled = false)
    {
        $this->value = $value;
        $this->disabled = $disabled;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View
     */
    public function render()
    {
        $options = ['0' => __('None')];
        if (! empty($groups = GroupModel::orderByName())) {
            foreach ($groups as $group) {
                $options[$group->id] = $group->name;
            }
        }

        return view('components.admin.form.group', [
            'options' => $options
        ]);
    }
}
