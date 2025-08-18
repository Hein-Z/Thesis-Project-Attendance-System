<?php

namespace App\Http\Controllers;
use App\Models\Teacher;
use App\Models\TeacherID;

use Illuminate\View\View;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function show(): View
    {
        $teachers = Teacher::with('teacher_info')
        ->whereNotNull('teacher_id')
        ->orderBy('check_in', 'desc')
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

    public function update(Request $request, $id)
{ 
    $teacher = Teacher::findOrFail($id);

    $request->validate([
        'check_in'  => 'required|date',
        'check_out' => 'nullable|date|after_or_equal:check_in',
    ],[
        'check_out.after_or_equal' => 'Checkout time must be after or equal to check-in time.',
    ]);

    $teacher->update([
        'check_in'  => $request->check_in,
        'check_out' => $request->check_out,
        'checkout_type' => $request->check_out ? 'changed by admin' : $teacher->checkout_type,
    ]);
        return redirect()->route('teachers.edit', $teacher->id) ->with('success', 'Attendance updated successfully!');
    }

public function profile($teacher_id)
{
    $teacher = TeacherID::where('id', $teacher_id)->firstOrFail();

    $attendances = Teacher::where('teacher_id', $teacher_id)
        ->orderBy('check_in', 'asc')
        ->get();

    // Weekly total duration in minutes
    $weeklyData = [];
    foreach ($attendances as $att) {
        if(!$att->check_in || !$att->check_out) continue;

        $checkIn = \Carbon\Carbon::parse($att->check_in);
        $weekStart = $checkIn->copy()->startOfWeek(); // Monday
        $weekEnd = $checkIn->copy()->endOfWeek();     // Sunday
        $rangeKey = $weekStart->format('d M') . ' - ' . $weekEnd->format('d M');

        $duration = $checkIn->diffInMinutes(\Carbon\Carbon::parse($att->check_out));

        if(!isset($weeklyData[$rangeKey])) $weeklyData[$rangeKey] = 0;

        $weeklyData[$rangeKey] += $duration;
    }

    return view('teacherProfile', compact('teacher', 'attendances', 'weeklyData'));
}

public function latest()
{
    $today = Carbon::now('Asia/Yangon')->toDateString();

    $teacher = Teacher::whereDate('updated_at', $today)->with('teacher_info')
        ->orderBy('updated_at', 'desc')
        ->first();

       if ($teacher) {
        return response()->json([
            'teacher_id' => $teacher->teacher_id,
            'name'=>$teacher->teacher_info->name,
            'check_in'   => $teacher->check_in ? $teacher->check_in->format('H:i:s') : null,
            'check_out'  => $teacher->check_out ? $teacher->check_out->format('H:i:s') : null,
        ]);
    }
    return response()->json(null);
}

}
