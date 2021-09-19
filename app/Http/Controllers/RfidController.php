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
        return inertia('Rfid/Index', [
            'items' => Rfid::with('user')
                ->orderBy('updated_at', 'desc')
                ->get()
                ->map(function ($rfid) {
                    return [
                        'id' => $rfid->id,
                        'value' => $rfid->value,
                        'user' => $rfid->user->name ?? null,
                        'is_logged' => $rfid->is_logged ? 'Yes' : 'No',
                        'updated_at' => $rfid->updated_at->toDateTimeString(),
                    ];
                }),
            'allowNewRfids' => config('constants.allow_new_rfid')]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return inertia('Rfid/Form');
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