<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApiResource;
use App\Models\Student;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $user = auth()->user()->load('grade');
        $students = Student::where('grade_id', $user->grade_id)->get()->load('deposits', 'credits');
        
        foreach ($students as $student) {
            $student->deposit = $student->deposits->sum('deposit');
            $student->credit = $student->credits->sum('credit');
        }

        return new ApiResource(true, 'Grade data retrieved successfully', [
            'students' => $students,
        ]);
    }
}
