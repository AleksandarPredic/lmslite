<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Http\RedirectResponse;

class UserGroupController extends Controller
{
    /**
     * Remove the specified resource from storage.
     *
     * @param  UserGroup $userGroup
     * @param  User $user
     *
     * @return RedirectResponse
     */
    public function destroy(UserGroup $userGroup, User $user): RedirectResponse
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
}
