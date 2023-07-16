<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApiResource;
use App\Models\Credit;
use App\Models\User;
use App\Models\Deposit;
use Illuminate\Http\Request;

class HomepageController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $user = auth()->user()->load('grade');

        $deposit = Deposit::orderBy('created_at', 'desc')->get()->load('student');
        $deposit = $deposit->filter(function ($item) {
            return $item->student->grade_id == auth()->user()->grade_id;
        })->take(3);
        
        $credit = Credit::orderBy('created_at', 'desc')->get()->load('student');
        $credit = $credit->filter(function ($item) {
            return $item->student->grade_id == auth()->user()->grade_id;
        })->take(3);

        return new ApiResource(true, 'Homepage', [
            'user' => $user,
            'deposit_students' => $deposit,
            'credit_students' => $credit,
        ], 'Homepage');
    }
}
