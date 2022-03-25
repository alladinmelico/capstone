<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubjectRequest;
use App\Http\Resources\SubjectResource;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{

    public function index(Request $request)
    {
        return SubjectResource::collection(Subject::with('classrooms')->orderBy('updated_at', 'desc')->paginate($request->limit));
    }

    public function store(SubjectRequest $request)
    {
        $data = $request->validated();
        if (!empty($request->cover)) {
            $data['cover'] = json_decode($request->cover, true);
        }
        return new SubjectResource(Subject::create($data));
    }

    public function show(Subject $subject)
    {
        return new SubjectResource($subject->load('classrooms.subject', 'classrooms.section'));
    }

    public function update(SubjectRequest $request, Subject $subject)
    {
        $data = $request->validated();
        if (!empty($request->cover)) {
            $data['cover'] = json_decode($request->cover, true);
        }
        $subject->update($data);
        return new SubjectResource($subject);
    }

    public function destroy(Subject $subject)
    {
        return $subject->delete();
    }
}
