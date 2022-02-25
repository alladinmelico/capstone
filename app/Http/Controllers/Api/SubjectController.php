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
        return SubjectResource::collection(Subject::orderBy('updated_at', 'desc')->paginate($request->limit));
    }

    public function store(SubjectRequest $request)
    {
        return new SubjectResource(Subject::create($request->validated()));
    }

    public function show(Subject $subject)
    {
        return new SubjectResource($subject);
    }

    public function update(SubjectRequest $request, Subject $subject)
    {
        $subject->update($request->validated());
        return new SubjectResource($subject);
    }

    public function destroy(Subject $subject)
    {
        return $subject->delete();
    }
}
