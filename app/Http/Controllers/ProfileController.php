<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApiResource;
use App\Models\Student;
use Illuminate\Http\Request;
use PHPUnit\Framework\MockObject\Builder\Stub;

class ProfileController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $user = auth()->user()->load('grade');
        $students = Student::where('grade_id', $user->grade_id)->get();
        //get length of $students
        $students = count($students);
        // get student where he is deposit
        $studentDeposit = Student::where('grade_id', $user->grade_id)->whereHas('deposits')->get();
        // get length of $studentDeposit
        $studentDeposit = count($studentDeposit);

        return new ApiResource(true, 'Profile data retrieved successfully', [
            'user' => $user,
            'students' => $students,
            'students_deposit' => $studentDeposit,
        ]);
    }
}
