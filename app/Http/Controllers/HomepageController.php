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

        $deposit = Deposit::whereHas('student', function ($query) {
            $query->where('grade_id', auth()->user()->grade_id);
        })->orderBy('created_at', 'desc')->take(3)->get()->load('student');

        $credit = Credit::whereHas('student', function ($query) {
            $query->where('grade_id', auth()->user()->grade_id);
        })->orderBy('created_at', 'desc')->take(3)->get()->load('student');

        return new ApiResource(true, 'Homepage', [
            'user' => $user,
            'deposit_students' => $deposit,
            'credit_students' => $credit,
        ], 'Homepage');
    }
}
