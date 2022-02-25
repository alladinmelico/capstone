<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use App\Http\Requests\CourseRequest;
use Illuminate\Http\Request;
use App\Events\CourseCreated;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return CourseResource::collection(Course::where('name', 'like', '%'. $request->search . '%')
            ->orWhere('code', 'like', '%'. $request->search . '%')
            ->orderBy('updated_at', 'desc')
            ->paginate($request->limit)
        );
    }

    public function store(CourseRequest $request)
    {
        $course = Course::create($request->validated());
        broadcast(new CourseCreated($course))->toOthers();
        return new CourseResource($course);
    }

    public function show(Course $course)
    {
        return new CourseResource($course);
    }

    public function update(CourseRequest $request, Course $course)
    {
        $course->update($request->validated());
        return new CourseResource($course);
    }

    public function destroy(Course $course)
    {
        return $course->delete();
    }
}
