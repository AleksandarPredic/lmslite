<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // TODO: list users
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Find users by name via JS
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
            ->whereRaw('lower(name) like (?)',["%{$name}%"]);

        if (! empty($exclude)) {
            $users->whereNotIn('id', $exclude);
        }

        return $users->get(['id', 'name'])->toArray();
    }
}
