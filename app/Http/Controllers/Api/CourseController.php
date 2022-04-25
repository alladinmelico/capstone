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
            ->when(auth()->user()->role_id === 1 && empty($request->no_trash), function ($query) {
                return $query->withTrashed();
            })
            ->orderBy('updated_at', 'desc')
            ->withCount('users')
            ->paginate($request->limit)
        );
    }

    public function store(CourseRequest $request)
    {
        $data = $request->validated();
        if (!empty($request->cover)) {
            $data['cover'] = json_decode($request->cover, true);
        }
        $course = Course::create($data);
        return new CourseResource($course);
    }

    public function show(Course $course)
    {
        return new CourseResource($course);
    }

    public function update(CourseRequest $request, Course $course)
    {
        $data = $request->validated();
        if (!empty($request->cover)) {
            $data['cover'] = json_decode($request->cover, true);
        }
        $course->update($data);
        return new CourseResource($course);
    }

    public function destroy(Course $course)
    {
        return $course->delete();
    }

    public function delete($course)
    {
        $course = Course::withTrashed()->findOrFail($course);
        return $course->forceDelete();
    }

    public function restore($course)
    {
        return Course::withTrashed()->findOrFail($course)->restore();
    }

}
