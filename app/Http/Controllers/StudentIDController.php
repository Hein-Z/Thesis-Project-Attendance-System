<?php

namespace App\Http\Controllers;
use App\Models\StudentID;

use Illuminate\Http\Request;

class StudentIDController extends Controller
{
    public function storeById($student_id)
    {
        // Create new record or return existing
        $student = StudentID::firstOrCreate(['student_id' => $student_id]);

        return response()->json([
            'success' => true,
            'message' => 'Student stored successfully',
            'data'    => $student
        ]);
    }
}
