<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\RequestValidationRulesTrait;
use App\Models\Group;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class GroupController extends Controller
{
    use RequestValidationRulesTrait;

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
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
    public function create()
    {
        return view('admin.group.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return RedirectResponse
     */
    public function store()
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
        return view('admin.group.show', [
            'group' => $group->load('users')
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Group $group
     *
     * @return View
     */
    public function edit(Group $group)
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
    public function update(Group $group)
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
