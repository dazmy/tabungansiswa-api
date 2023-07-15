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
        $deposit = Deposit::orderBy('created_at', 'desc')->take(3)->get();
        $credit = Credit::orderBy('created_at', 'desc')->take(3)->get();

        return new ApiResource(true, 'Homepage', [
            'user' => $user,
            'depositStudents' => $deposit,
            'creditStudents' => $credit,
        ], 'Homepage');
    }
}
