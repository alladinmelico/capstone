<?php

namespace App\Http\Controllers;

use App\Models\Course;
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
        return inertia('User/Index', ['items' => User::where('name', 'like', '%'. request()->search . '%')
            ->orWhere('email', 'like', '%'. request()->search . '%')
            ->orWhere('school_id', 'like', '%'. request()->search . '%')
            ->orderBy('updated_at', 'desc')
            ->withTrashed()
            ->paginate(10)]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return inertia('User/Form');
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
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $user->load('course');
        return inertia('User/Show', ['item' => $user, 'courses' => Course::select('id', 'name', 'code')->get()]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return inertia('User/Form', ['item' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }

    public function userRequests()
    {
        return inertia('User/Requests', ['items' => User::where('changes_verified', false)->orderBy('updated_at', 'desc')->get()]);
    }

    public function userApprove(Request $request, User $user)
    {
        $user->changes_verified = true;
        $user->verified_by = auth()->user()->id;
        $user->save();

        return redirect()->back();
    }
}