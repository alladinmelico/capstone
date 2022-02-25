<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Http\Requests\SectionRequest;
use App\Http\Resources\SectionResource;
use Illuminate\Http\Request;

class SectionController extends Controller
{

    public function index(Request $request)
    {
        return SectionResource::collection(Section::with(['president', 'faculty'])->paginate($request->limit));
    }

    public function store(SectionRequest $request)
    {
        return new SectionResource(Section::create($request->validated()));
    }

    public function show(Section $section)
    {
        return new SectionResource($section->load(['president', 'faculty']));
    }

    public function update(SectionRequest $request, Section $section)
    {
        $section->update($request->validated());
        return new SectionResource($section);
    }

    public function destroy(Section $section)
    {
        return $section->delete();
    }
}
