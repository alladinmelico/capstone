<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use App\Http\Resources\TicketResource;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Notifications\AdminReportCreated;
use App\Notifications\TicketUpdated;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return TicketResource::collection(Ticket::with('user')->orderBy('updated_at', 'desc')->paginate(request()->limit));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTicketRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTicketRequest $request)
    {
        $data = $request->validated();
        $data['ticket_id'] = strtoupper(uniqid());
        $data['user_id'] = auth()->user()->id;
        $ticket = Ticket::create($data);

        $users = User::isValidEmail()->where('role_id', 1)->get();
        $users->each(function ($value, $key) use ($data) {
            if ($value->is_valid_email) {
                $value->notify(new AdminReportCreated($data['ticket_id'], $data['title'], $data['category'], $data['message']));
            }
        });

        return new TicketResource($ticket);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function show(Ticket $ticket)
    {
        return new TicketResource($ticket->load('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTicketRequest  $request
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        $ticket->update($request->validated());
        $ticket->user->notify(new TicketUpdated($ticket->ticket_id, $ticket->status_label));
        return new TicketResource($ticket);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ticket $ticket)
    {
        return $ticket->delete();
    }
}
