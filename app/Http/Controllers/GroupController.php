<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\RequestValidationRulesTrait;
use App\Models\Group;
use App\Models\User;
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
            'groups' => Group::orderBy('starting_at', 'desc')->paginate(20)->withQueryString()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        // When creating group, only active option is allowed
        $activeOptions = $this->getGroupActiveColumnSelectOptions();
        unset($activeOptions[0]);

        return view('admin.group.create', [
            'activeOptions' => $activeOptions
        ]);
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
    public function show(Group $group)
    {
        $users = $group->load(['users'])->users()->userDefaultSorting()->get();

        return view('admin.group.show', [
            'group' => $group,
            'users' => $users,
            'exclude' => $users ? $users->pluck('id')->toArray() : []
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
            'group' => $group->load('course'),
            'activeOptions' => $this->getGroupActiveColumnSelectOptions()
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
     * @return RedirectResponse
     */
    public function destroy(Group $group)
    {
        $group->delete();

        return redirect(route('admin.groups.index'))
            ->with('admin.message.success', "Group, {$group->name}, deleted!");
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
            'user_id' => ['required', 'numeric', 'min:1']
        ]);

        $userId = $attributes['user_id'];

        $user = User::find($userId);

        if (! $user) {
            return redirect()->back()->with(
                'admin.message.error',
                '[ERROR] User you are trying to add doesn\'t exists in our records!'
            );
        }

        $userGroup = $group->addUser($user);

        // Prevent adding the same user in the group
        if (! $userGroup->wasRecentlyCreated) {
            throw ValidationException::withMessages(
                ['user_id' => 'User is already in the Group.']
            );
        }

        return back()->with(
            'admin.message.success',
            sprintf(
                'User %s added to the group!',
                User::find($userId)->name
            )
        );
    }

    public function updateUserPriceType(Group $group, User $user)
    {
        $validated = request()->validate([
            'price_type' => 'required|in:price_1,price_2',
        ]);

        $group->users()->updateExistingPivot($user->id, [
            'price_type' => $validated['price_type'],
        ]);

        return back()->with(
            'admin.message.success',
            sprintf(
                __('Price type updated successfully for %s.'),
                $user->name
            )

        );
    }

    /**
     * Remove the user relationship via the pivot table
     *
     * @param  Group $group
     * @param  User $user
     *
     * @return RedirectResponse
     */
    public function removeUser(Group $group, User $user): RedirectResponse
    {
        try {
            $group->removeUser($user);
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
            'course_id' => ['nullable', 'numeric'],
            'note' => array_merge(['nullable'], $this->getNoteFieldRules()),
            'price_1' => 'nullable|numeric|min:0|max:99999999.99',
            'price_2' => 'nullable|numeric|min:0|max:99999999.99',
            'active' => ['required', 'boolean'],
        ]);

        // additional sanitization
        $attributes['name'] = strip_tags($attributes['name']);

        if (isset($attributes['note'])) {
            $attributes['note'] = strip_tags($attributes['note']);
        }

        return $attributes;
    }

    private function getGroupActiveColumnSelectOptions()
    {
        return [1 => __('Yes'), 0 => __('No')];
    }
}
