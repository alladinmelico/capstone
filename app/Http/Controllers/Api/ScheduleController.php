<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ScheduleResource;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $schedules = User::with('classrooms.schedule')
            ->where('id', $request->user)
            ->get()
            ->pluck('classrooms')
            ->flatten()
            ->pluck('schedule');
        return ScheduleResource::collection($schedules);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, $this->rules());

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data['valid_until'] = Carbon::parse($data['valid_until']);

        return new ScheduleResource(Schedule::create($data));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Schedule $schedule)
    {
        return new ScheduleResource($schedule);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Schedule $schedule)
    {
        $data = $request->all();

        $validator = Validator::make($data, $this->rules());

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data['valid_until'] = Carbon::parse($data['valid_until']);

        return new ScheduleResource($schedule->update($data));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Schedule $schedule)
    {
        return response()->json($schedule->delete());
    }

    protected function rules(): array
    {
        return [
            'start_at' => 'required',
            'end_at' => 'required',
            'day' => 'required|string|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'valid_until' => 'required|date',
            'note' => 'nullable',
            'facility_id' => 'required|numeric|exists:facilities,id',
            'user_id' => 'required|numeric|exists:users,id',
        ];
    }
}