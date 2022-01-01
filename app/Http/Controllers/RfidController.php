<?php

namespace App\Http\Controllers;

use App\Models\Rfid;
use Illuminate\Http\Request;

class RfidController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return inertia('Rfid/Index', ['items' => Rfid::where('value', 'like', '%'. request()->search . '%')
            ->orWhere('user_id', 'like', '%'. request()->search . '%')
            ->orderBy('updated_at', 'desc')
            ->with('user')
            ->paginate(10),
            'allowNewRfids' => config('constants.allow_new_rfid')]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Rfid  $rfid
     * @return \Illuminate\Http\Response
     */
    public function show(Rfid $rfid)
    {
        return inertia('Rfid/Show', [
            'item' => $rfid->load('user'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Rfid  $rfid
     * @return \Illuminate\Http\Response
     */
    public function edit(Rfid $rfid)
    {
        return inertia('Rfid/Form', [
            'item' => $rfid->load('user'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Rfid  $rfid
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Rfid $rfid)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Rfid  $rfid
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rfid $rfid)
    {
        //
    }
}
