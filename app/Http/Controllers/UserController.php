<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\RequestValidationRulesTrait;
use App\Models\User;
use Illuminate\Contracts\View\View;
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
     * Display the user statistics
     *
     * @param User $user
     *
     * @return View
     * @throws \Exception
     */
    public function statistics(User $user): View
    {
        $statuses = $user->getCalendarEventStatusesLastMonths(6);

        return view('admin.users.statistics', [
            'user' => $user,
            'calendarEvents' => $user->getUserNextEvents(5),
            'calendarEventStatusesAttended' => $statuses->where('status', 'attended'),
            'calendarEventStatusesCanceled' => $statuses->where('status', 'canceled'),
            'calendarEventStatusesNoShow' => $statuses->where('status', 'no-show'),
            'calendarEventStatusesCompensation' => $statuses->where('info', 'compensation')
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

        try {
            $updated = $user->update($attributes);
            $user->updateRole($attributes['role_id']);
        } catch (\Exception $exception) {
            // TODO: log error

            return redirect()->back()->with(
                'admin.message.error',
                sprintf(
                    '[ERROR] User, %s could not be updated!',
                    $user->name
                )
            )->withInput();
        }

        if (! $updated) {
            return redirect()->back()->with(
                'admin.message.error',
                sprintf(
                    '[ERROR] User, %s could not be updated!',
                    $user->name
                )
            );
        }

        return redirect()->back()->with(
            'admin.message.success',
            sprintf(
                'User, %s updated!',
                $user->name
            )
        );
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
            'role_id' => ['required', 'numeric', 'exists:user_roles,id'],
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
                    'note'
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
}
