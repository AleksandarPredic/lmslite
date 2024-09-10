<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class CourseController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @return RedirectResponse
     */
    public function storeDiscount(Course $course)
    {

        $attributes = $this->processRequest();

        $discount = $course->addNewDiscount(
            $attributes['name'],
            $attributes['price']
        );

        return redirect(route('admin.courses.show', $course->id))
            ->with('admin.message.success', "Course discount, {$discount->name}, added!");
    }

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

        return redirect(route('admin.courses.show', $course->id))
            ->with('admin.message.success', "Course, {$course->name}, created!");
    }

    /**
     * Display the specified resource.
     *
     * @param Course $course
     *
     * @return View
     */
    public function show(Course $course)
    {
        return view('admin.course.show', [
            'course' => $course,
            'prices' => $course->getAllPricesSordedFromNewest(),
            'discounts' => $course->courseDiscounts,
        ]);
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
