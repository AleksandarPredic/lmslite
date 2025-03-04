<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class UserPaymentsController extends Controller
{
    /**
     *
     * @param  User  $user
     * @return View
     */
    public function index(User $user): View
    {
        $userObj = $user->load(['groups']);

        return view('admin.users.payments.index', [
            'user' => $userObj
        ]);
    }

}
