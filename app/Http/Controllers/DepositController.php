<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Resources\ApiResource;

class DepositController extends Controller
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
            'deposit' => 'required|integer',
            'student_id' => 'required|integer|exists:students,id',
            'input_date' => 'required|date_format:Y-m-d H:i:s',
        ]);

        $student = Student::find($request->student_id);
        if(!$student) {
            return new ApiResource(false, 'Student not found', null);
        }

        $student->deposits()->create([
            'deposit' => $request->deposit,
            'student_id' => $student->id,
            'input_date' => $request->input_date,
        ]);
        $student->load('deposits', 'credits');

        return new ApiResource(true, 'Deposit created', $student);
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
        $student->deposits = $student->deposits()->orderBy('input_date', 'desc')->get();
        $student->credits = $student->credits()->orderBy('input_date', 'desc')->get();

        return new ApiResource(true, 'Student deposits', ['student' => $student]);
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
            'deposit' => 'required|integer',
        ]);

        $deposit = Deposit::find($id);
        if(!$deposit) {
            return new ApiResource(false, 'Deposit not found', null);
        }

        $deposit->update([
            'deposit' => $request->deposit,
        ]);
        $deposit->load('student');

        return new ApiResource(true, 'Deposit updated', $deposit);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deposit = Deposit::find($id);
        if(!$deposit) {
            return new ApiResource(false, 'Deposit not found', null);
        }

        $deposit->delete();

        return new ApiResource(true, 'Deposit deleted', null);
    }
}
