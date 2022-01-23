<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\RequestValidationRulesTrait;
use App\Models\Group;
use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;

class GroupController extends Controller
{
    use RequestValidationRulesTrait;

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        return view('admin.group.index', [
            'groups' => Group::orderBy('name', 'asc')->paginate(10)->withQueryString()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        return view('admin.group.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return RedirectResponse
     */
    public function store(): RedirectResponse
    {
        $attributes = $this->validateSanitizeRequest(new Group());

        // Create event
        $group = Group::create($attributes);

        return redirect(route('admin.groups.show', $group))->with(
            'admin.message.success',
            sprintf(
                'Group, %s created!',
                $group->name
            )
        );
    }

    /**
     * Display the specified resource.
     *
     * @param Group $group
     *
     * @return View
     */
    public function show(Group $group): View
    {
        return view('admin.group.show', [
            'group' => $group->load('users'),
            'users' => $group->users->sortBy('name'),
            'exclude' => $group->users ? $group->users->map(fn ($user) => $user->id) : []
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Group $group
     *
     * @return View
     */
    public function edit(Group $group): View
    {
        return view('admin.group.edit', [
            'group' => $group
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Group  $group
     *
     * @return RedirectResponse
     */
    public function update(Group $group): RedirectResponse
    {
        $attributes = $this->validateSanitizeRequest($group);

        // Update observer will only be fired if the model is dirty
        $updated = $group->update($attributes);

        if (! $updated) {
            return redirect()->back()->with(
                'admin.message.error',
                sprintf(
                    '[ERROR] Group, %s could not be updated!',
                    $group->name
                )
            );
        }

        return redirect()->back()->with(
            'admin.message.success',
            sprintf(
                'Group, %s updated!',
                $group->name
            )
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Group  $group
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        // TODO: Delete group + update event group_id to null
    }

    /**
     * Add user to the Group relationship via pivot table
     *
     * @param Group $group
     *
     * @return RedirectResponse
     */
    public function addUser(Group $group): RedirectResponse
    {
        $attributes = \request()->validate([
            'user_id' => ['required', 'numeric']
        ]);

        $userId = $attributes['user_id'];

        // Check if we already have this user
        if ($group->users()->find($userId)) {
            throw ValidationException::withMessages(
                ['user_id' => 'User is already in the group.']
            );
        }

        UserGroup::create([
            'group_id' => $group->id,
            'user_id' => $userId
        ]);

        return back()->with(
            'admin.message.success',
            sprintf(
                'User %s added to the group!',
                User::find($userId)->name
            )
        );
    }

    /**
     * Remove the user relationship via the pivot table
     *
     * @param  UserGroup $userGroup
     * @param  User $user
     *
     * @return RedirectResponse
     */
    public function removeUser(UserGroup $userGroup, User $user): RedirectResponse
    {
        try {
            $userGroup->deleteOrFail();
            return redirect()->back()->with(
                'admin.message.success',
                sprintf(
                    'User %s removed successfully from this group!',
                    $user->name
                )
            );

        } catch (\Throwable $exception) {
            // TODO: Add logger here
            return redirect()->back()->with(
                'admin.message.error',
                sprintf(
                    '[ERROR] User with id %d could not be removed from this group!',
                    $user->id
                )
            );
        }
    }

    /**
     * Validate and sanitize request
     *
     * @param Group $group
     *
     * @return array
     *
     */
    protected function validateSanitizeRequest(Group $group): array
    {
        $attributes = request()->validate([
            'name' => array_merge(['required'], $this->getNameFieldRules()),
            'starting_at' => array_merge(['required'], $this->getStartingAtFieldRules()),
            'ending_at' => array_merge(['required'], $this->getEndingAtFieldRules()),
            'note' => array_merge(['nullable'], $this->getNoteFieldRules()),
        ]);

        // additional sanitization
        $attributes['name'] = strip_tags($attributes['name']);

        if (isset($attributes['note'])) {
            $attributes['note'] = strip_tags($attributes['note']);
        }

        return $attributes;
    }
}
