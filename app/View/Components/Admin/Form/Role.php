<?php

namespace App\View\Components\Admin\Form;

use App\Models\Role as RoleModel;
use Illuminate\View\Component;
use Illuminate\View\View;

class Role extends Component
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
    public $includeAdminRole;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(int $value, bool $includeAdminRole = false)
    {
        $this->value = $value;
        $this->includeAdminRole = $includeAdminRole;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View
     */
    public function render()
    {
        $roles = $this->includeAdminRole ? RoleModel::all() : RoleModel::excludeAdminRole()->get();

        $options = [];
        if (! empty($roles)) {
            foreach ($roles as $role) {
                $options[$role->id] = $role->name;
            }
        }

        return view('components.admin.form.role', [
            'options' => $options
        ]);
    }
}
