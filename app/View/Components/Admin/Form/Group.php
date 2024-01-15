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
        // These properties are usied in resources/views/components/admin/form/group.blade.php
        // We don't use them for now as in the edit event the group can not be changed
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
        if (! empty($groups = GroupModel::where('active', true)->orderBy('name', 'ASC')->get())) {
            foreach ($groups as $group) {
                $options[$group->id] = $group->name;
            }
        }

        return view('components.admin.form.group', [
            'options' => $options
        ]);
    }
}
