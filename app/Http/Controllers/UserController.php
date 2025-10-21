<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\RequestValidationRulesTrait;
use App\Models\CalendarEventUserCompensation;
use App\Models\CalendarEventUserStatus;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use RequestValidationRulesTrait;

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        return view('admin.users.index', [
            'users' => User::with('groups')
                           ->filterByName(request()->get('name'))
                           ->allExceptAdmins()
                           ->UserDefaultSorting()
                           ->paginate(20)
                           ->withQueryString()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @return View
     */
    public function create(): View
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return RedirectResponse
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function store(): RedirectResponse
    {
        $attributes = $this->validateSanitizeRequest(new User());

        // Create user
        $user = User::create($attributes);
        $user->createRole($attributes['role_id']);

        return redirect(route('admin.users.show', $user))->with(
            'admin.message.success',
            sprintf(
                'Event, %s created!',
                $user->name
            )
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  User  $user
     * @return View
     */
    public function show(User $user): View
    {
        return view('admin.users.show', [
            'user' => $user
        ]);
    }

    /**
     * Display the user next calendar events
     *
     * @param User $user
     *
     * @return View
     * @throws \Exception
     */
    public function nextCalendarEvents(User $user): View
    {
        return view('admin.users.next-calendar-events', [
            'user' => $user,
            'calendarEvents' => $user->getUserNextEvents(5)
        ]);
    }

    /**
     * Display the user history, Which courses and groups this user was member of. Sorted desc
     *
     * @param User $user
     *
     * @return View
     */
    public function showGroupsHistory(User $user): View
    {
        // Get all groups the user was ever a member of (including current groups)
        $groupsWithCourses = $user->groups()
                                  ->with(['course'])
                                  ->withPivot('created_at')
                                  ->orderBy('pivot_created_at', 'desc')
                                  ->get()
                                  ->groupBy('course.name')
                                  ->sortKeys();

        return view('admin.users.groups-history', [
            'user' => $user,
            'groupsSortedByCourseName' => $groupsWithCourses
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  User  $user
     * @return View
     */
    public function edit(User $user): View
    {
        return view('admin.users.edit', [
            'user' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  User  $user
     * @return RedirectResponse
     */
    public function update(User $user): RedirectResponse
    {
        // TODO: Add support for admin user to change data. In this version it is not needed.
        if ($user->can('admin')) {
            return redirect()->back()->with(
                'admin.message.error',
                sprintf(
                    '[ERROR] User, %s is not updatable!',
                    $user->name
                )
            );
        }

        $attributes = $this->validateSanitizeRequest($user);

        $updated = $user->update($attributes);

        if (! $updated) {
            return redirect()->back()->with(
                'admin.message.error',
                sprintf(
                    '[ERROR] User, %s could not be updated!',
                    $user->name
                )
            )->withInput();
        }

        // Update role only if it was changed
        if ((int)$attributes['role_id'] !== $user->role->first()->id) {
            $roleUpdated = $user->updateRole($attributes['role_id']);

            if (! $roleUpdated) {
                return redirect()->back()->with(
                    'admin.message.error',
                    sprintf(
                        '[ERROR] Role not updated for user %s!',
                        $user->name
                    )
                )->withInput();
            }
        }

        return redirect()->back()->with(
            'admin.message.success',
            sprintf(
                'User, %s updated!',
                $user->name
            )
        )->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User  $user
     * @return RedirectResponse
     */
    public function destroy(User $user): RedirectResponse
    {
        // TODO: Add support for admin user CRUD. In this version it is not needed.
        if ($user->can('admin')) {
            return redirect()->back()->with(
                'admin.message.error',
                sprintf(
                    '[ERROR] User, %s is not deletable!',
                    $user->name
                )
            );
        }

        $user->delete();

        return redirect(route('admin.users.index'))
            ->with('admin.message.success', "Event, {$user->name}, deleted!");
    }

    /**
     * Validate and sanitize request
     *
     * @param User $user
     *
     * @return array
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function validateSanitizeRequest(User $user)
    {
        $textRules = ['nullable', 'min:3', 'max:255'];
        $datedRules = ['nullable', 'date'];

        $emailRules = ['nullable', 'email', 'max:255'];
        // New users must have unique email
        if (! $user->exists) {
            $emailRules = array_merge($emailRules, ['unique:users,email']);
        }

        // If user exists and try to change an email
        if ($user->exists && (request()->get('email') !== $user->email)) {
            $emailRules = array_merge($emailRules, ['unique:users,email']);
        }

        $attributes = request()->validate([
            'name' => array_merge(['required'], $this->getNameFieldRules()),
            'email' => $emailRules,
            'role_id' => ['required', 'numeric', 'exists:roles,id'],
            'parent_1_name' => $textRules,
            'parent_1_phone' => $textRules,
            'parent_2_name' => $textRules,
            'parent_2_phone' => $textRules,
            'date_of_birth' => $datedRules,
            'address' => $textRules,
            'school' => $textRules,
            'school_info' => $textRules,
            'sign_up_date' => $datedRules,
            'active' => ['required', 'boolean'],
            'note' => array_merge(['nullable'], $this->getNoteFieldRules()),
            'payment_note' => array_merge(['nullable'], $this->getNoteFieldRules()),
            'media_consent' => ['required', 'boolean'],
        ]);

        // Add random password for every user, but we will not yet use passwords. Maybe in next version
        if (! $user->exists) {
            $random = str_shuffle('abcdefghjklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ234567890!$%^&!$%^&');
            $password = substr($random, 0, 10);
            $attributes['password'] = Hash::make($password);
        }

        // additional sanitization
        foreach ($attributes as $field => $value) {
            if (! in_array(
                $field, [
                    'name',
                    'parent_1_name',
                    'parent_1_phone',
                    'parent_2_name',
                    'parent_2_phone',
                    'address',
                    'school',
                    'school_info',
                    'note',
                    'payment_note',
                ] )) {

                continue;
            }

            $attributes[$field] = strip_tags($value);
        }

        return $attributes;
    }

    /**
     * Find users by name via JS
     *
     * @see resources/views/components/admin/user/add-user.blade.php
     */
    public function findUsers(): array
    {
        $attributes = request()->validate([
            'name' => ['required', 'min:3'],
            // Exclude users that are already on the the calendar event for example
            'exclude' => ['nullable', 'array']
        ]);

        $name = filter_var($attributes['name'], FILTER_SANITIZE_STRING);
        $exclude = ! empty($attributes['exclude']) ? array_filter($attributes['exclude'], 'is_numeric') : [];

        $users = User::without('role')
            ->filterByName($name);


        if (! empty($exclude)) {
            $users->whereNotIn('id', $exclude);
        }

        return $users->get(['id', 'name'])->toArray();
    }

    /**
     * Find users with statuses eligible for compensation
     * @see resources/js/calendar-event/CalendarEventAddCompensation.js
     *
     * @return JsonResponse
     */
    public function findUserStatusesEligibleForCompensationForCalendarEvent()
    {
        $attributes = request()->validate([
            'user_id' => ['required', 'numeric', 'exists:users,id'],
            'calendar_event_id' => ['required', 'numeric', 'exists:calendar_events,id'],
        ]);

        $user = User::find($attributes['user_id']);
        $calendarEventId = $attributes['calendar_event_id'];

        /*
         * Find users statuses for:
         * - Users that are not on this event, as we are adding them async via ajax.
         *      Even if we have compensation attached, we can have user with multiple statuses that are eligible for the compensation
         * - Users who has no compensation already attached to this event
         * - Users who has no compensation relationship attached to the calendarEventStatus
         */

        // First check if the user already has Compensation for this Calendar Event
        if ($user->hasCompensationForCalendarEvent($calendarEventId)) {
            response()->json([]);
        }

        $calendarEventUserStatuses = CalendarEventUserCompensation::getUserStatusesEligibleForCompensation($user);

        /**
         * Map statuses to a simple array with calendar event and status
         * @var CalendarEventUserStatus $status
         */
        $results = $calendarEventUserStatuses->map(function ($status) {
            return [
                'event' => $status->calendarEvent->event->name,
                'status_id' => $status->id,
                'calendar_event_date' => lmsCarbonDateFormat($status->calendarEvent->starting_at),
                'status' => $status->status,
                'paid_compensation' => in_array($status->status, CalendarEventUserCompensation::getCalendarEventUserStatusesForPaidCompensation()),
            ];
        })->toArray();

        return response()->json($results);
    }
}
