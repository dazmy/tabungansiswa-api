<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Deposit;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Resources\ApiResource;
use App\Models\Credit;
use PHPUnit\Framework\MockObject\Builder\Stub;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::where('grade_id', auth()->user()->grade_id)->get();
        $students->load('deposits', 'credits');
        return new ApiResource(true, 'List of students', $students);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $teacher = auth()->user();
        $request->validate([
            'name' => 'required|string|max:50',
        ]);

        $student = Student::create([
            'name' => $request->name,
            'grade_id' => $teacher->grade_id,
        ]);

        return new ApiResource(true, 'Student created', $student);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $student = Student::find($id)->load('grade');
        if(!$student) {
            return new ApiResource(false, 'Student not found', null);
        }
        $student->deposits;
        $student->credits;

        $student->deposit = $student->deposits->sum('deposit');
        $student->credit = $student->credits->sum('credit');
        // get the sum one month of student's credit
        $month_deposit = Deposit::where('student_id', $student->id)
            ->whereMonth('input_date', Carbon::now()->month)
            ->pluck('deposit')->sum() - Credit::where('student_id', $student->id)->whereMonth('input_date', Carbon::now()->month)->pluck('credit')->sum();

        return new ApiResource(true, 'Student details', [
            'student' => $student,
            'month_deposit' => $month_deposit,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $teacher = auth()->user();
        $request->validate([
            'name' => 'nullable|string|max:50',
        ]);

        $student = Student::find($id);
        if (!$student) {
            return new ApiResource(false, 'Student not found', null);
        }
        $student->update([
            'name' => $request->name,
            'grade_id' => $teacher->grade_id,
        ]);

        return new ApiResource(true, 'Student updated', $student);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $student = Student::find($id);
        if (!$student) {
            return new ApiResource(false, 'Student not found', null);
        }
        $student->delete();
        return new ApiResource(true, 'Student deleted', null);
    }
}
