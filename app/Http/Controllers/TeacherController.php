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
        // Get teacher info
        $teacher = TeacherID::where('id', $teacher_id)->firstOrFail();

        // Get all attendances of this teacher
        $attendances = Teacher::where('teacher_id', $teacher_id)
            ->orderBy('check_in', 'asc')
            ->get();

        return view('teacherProfile', compact('teacher', 'attendances'));
    }
   public function attendanceData(Request $request, $teacherId)
    {
        $query = Teacher::where('teacher_id', $teacherId);

        // Filter by date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('check_in', [
                Carbon::parse($request->start_date)->startOfDay(),
                Carbon::parse($request->end_date)->endOfDay(),
            ]);
        }

        // Filter by checkout type
        if ($request->filled('checkout_type')) {
            $query->where('checkout_type', $request->checkout_type);
        }

        $attendances = $query->orderBy('check_in')->get();

        // Prepare weekly aggregated data for chart
        $weeklyData = $attendances->groupBy(function ($item) {
            // Ensure check_in is a Carbon instance
            $checkIn = $item->check_in instanceof Carbon
                ? $item->check_in
                : Carbon::parse($item->check_in ?? now());

            return $checkIn->startOfWeek()->format('Y-m-d');
        })->map(function ($week) {
            return $week->sum(function ($item) {
                if ($item->check_out) {
                    $checkIn = $item->check_in instanceof Carbon
                        ? $item->check_in
                        : Carbon::parse($item->check_in);
                    $checkOut = $item->check_out instanceof Carbon
                        ? $item->check_out
                        : Carbon::parse($item->check_out);

                    return $checkOut->diffInMinutes($checkIn);
                }
                return 0;
            });
        });

        return response()->json([
            'attendances' => $attendances,
            'weeklyData'  => $weeklyData,
        ]);
    }

}
