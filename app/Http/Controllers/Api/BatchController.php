<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\Batch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BatchController extends Controller
{
    public function index()
    {
        //
    }

    public function attendance (Request $request, Batch $batch) {
        $request->validate(['is_absent' => 'required|boolean']);
        $batch->is_absent = $request->is_absent;
        $batch->save();
        response()->json(['success' => 'success'], 200);
    }

    public function approve (Request $request, Batch $batch) {
        $batch->is_approved = true;
        $batch->save();
        response()->json(['success' => 'success'], 200);
    }

    public function leaveApplication (Request $request, Batch $batch) {
        $data = $request->validate([
            'note' => 'required|string',
            'attachment' => 'sometimes|file|mimes:jpg,jpeg,bmp,png,pdf',
            'attachment_string' => 'nullable|string',
        ]);

        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('attendance_attachments', 's3');
            $data['attachment'] = Storage::disk('s3')->url($path);
        } else if (!empty($data['attachment_string'])) {
            $fileName = 'attendance_attachments/' . uniqid($batch->id) . '.pdf';
            Storage::disk('s3')->put($fileName,base64_decode($request->attachment_string));
            $data['attachment'] = Storage::disk('s3')->url($fileName);
        }

        $batch->update($data);

        response()->json(['success' => 'success'], 200);
    }
}
