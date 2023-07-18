<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApiResource;
use Illuminate\Http\Request;

class SearchByMonthController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, string $studentid, string $month, string $year)
    {
        $month = $month;
        $year = $year;
        $student_id = $studentid;

        $deposit = \App\Models\Deposit::where('student_id', $student_id)
            ->whereMonth('input_date', $month)
            ->whereYear('input_date', $year)
            ->get();

        $credit = \App\Models\Credit::where('student_id', $student_id)
            ->whereMonth('input_date', $month)
            ->whereYear('input_date', $year)
            ->get();

        $total_deposit = 0;
        $total_credit = 0;

        foreach ($deposit as $item) {
            $total_deposit += $item->deposit;
        }

        foreach ($credit as $item) {
            $total_credit += $item->credit;
        }

        $total = $total_deposit - $total_credit;

        return new ApiResource(true, 'success get data', [
            'deposit' => $deposit,
            'credit' => $credit,
            'total' => $total,
        ]);
    }
}
