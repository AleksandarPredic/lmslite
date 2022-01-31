<?php

namespace App\View\Components\Admin\Form;

use App\Models\Course as CourseModel;
use Illuminate\View\Component;
use Illuminate\View\View;

class Course extends Component
{
    /**
     * Component prop
     * @var int
     */
    public $value;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(int $value = 0)
    {
        $this->value = $value;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View
     */
    public function render()
    {
        $options = ['0' => __('None')];
        if (! empty($courses = CourseModel::orderByName())) {
            foreach ($courses as $course) {
                $options[$course->id] = $course->name;
            }
        }

        return view('components.admin.form.course', [
            'options' => $options
        ]);
    }
}
