<?php

namespace App\Http\Controllers;

use App\Models\Credit;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Resources\ApiResource;

class CreditController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $request->validate([
            'credit' => 'required|integer',
            'student_id' => 'required|integer|exists:students,id',
            'input_date' => 'required|date',
        ]);

        $student = Student::find($request->student_id);
        if(!$student) {
            return new ApiResource(false, 'Student not found', null);
        }

        $student->credits()->create([
            'credit' => $request->credit,
            'student_id' => $student->id,
            'input_date' => $request->input_date,
        ]);
        $student->load('deposits', 'credits');

        return new ApiResource(true, 'Credit created', $student);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        $request->validate([
            'credit' => 'required|integer',
        ]);

        $credit = Credit::find($id);
        if(!$credit) {
            return new ApiResource(false, 'Credit not found', null);
        }

        $credit->update([
            'credit' => $request->credit,
        ]);
        $credit->load('student');

        return new ApiResource(true, 'Credit updated', $credit);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $credit = Credit::find($id);
        if(!$credit) {
            return new ApiResource(false, 'Credit not found', null);
        }

        $credit->delete();

        return new ApiResource(true, 'Credit deleted', null);
    }
}
