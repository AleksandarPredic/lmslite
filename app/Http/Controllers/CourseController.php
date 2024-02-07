<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseMembership;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        return view('admin.course.index', [
            'courses' => Course::orderBy('name', 'asc')->paginate(10)->withQueryString()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
    {
        return view('admin.course.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return RedirectResponse
     */
    public function store()
    {
        $attributes = $this->processRequest();

        $course = Course::create([
            'name' => $attributes['name']
        ]);

        $course->addNewMembershipPrice($attributes['price']);

        return redirect(route('admin.courses.index'))
            ->with('admin.message.success', "Course, {$course->name}, created!");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Course $course
     *
     * @return View
     */
    public function edit(Course $course)
    {
        return view('admin.course.edit', [
           'course' => $course,
            'price' => $course->getLatestMembershipPriceAsDecimal()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Course $course
     *
     * @return RedirectResponse
     */
    public function update(Course $course)
    {
        $attributes = $this->processRequest();

        $course->update([
            'name' => $attributes['name']
        ]);

        $course->addNewMembershipPrice($attributes['price']);

        return back()->with('admin.message.success', 'Course updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Course $course
     *
     * @return RedirectResponse
     */
    public function destroy(Course $course)
    {
        $course->delete();

        return redirect(route('admin.courses.index'))
            ->with('admin.message.success', "Course, {$course->name}, deleted!");
    }

    /**
     * Validate request
     *
     * @return array
     */
    protected function processRequest(): array
    {
        $attributes = \request()->validate([
            'name' => ['required', 'min:3','max:255'],
            'price' => ['required', 'numeric']
        ]);

        $attributes['name'] = strip_tags($attributes['name']);

        return $attributes;
    }
}
