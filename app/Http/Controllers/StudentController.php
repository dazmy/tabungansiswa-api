<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApiResource;
use App\Models\Student;
use Illuminate\Http\Request;
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
        $student = Student::find($id);
        if(!$student) {
            return new ApiResource(false, 'Student not found', null);
        }
        $student->deposits;
        $student->credits;
        return new ApiResource(true, 'Student details', $student);
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
