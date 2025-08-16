<?php

namespace App\Http\Controllers;
use App\Models\Teacher;
use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function show(): View
    {
        $teachers = Teacher::with('teacher_info')
        ->whereNotNull('teacher_id')
        ->orderBy('created_at', 'desc')
        ->get();

    return view('teacher', compact('teachers'));
    }

    public function edit(Teacher $teacher)
    {
        $teacher->load('teacher_info');
        return view('teacher_edit', compact('teacher'));
    }

    public function destroy(Teacher $teacher)
    {
        $teacher->delete();
        return response()->json(['success' => true]);
    }

    public function update(Request $request, Teacher $teacher)
{
    // Validate the input
    $request->validate([
        'time' => 'nullable|date_format:H:i',
        'status' => 'required|in:In,Out,Absent',
    ]);

    // Update the main row
    $teacher->update([
        'time' => $request->time ?Carbon::parse($request->time) : null,
        'status' => $request->status,
    ]);

    return redirect()->route('teachers.index')
                     ->with('success', 'Teacher attendance updated successfully!');
}
}
