<?php

namespace App\Http\Controllers;

use PDF;
use App\Models\User;
use App\Models\Credit;
use App\Models\Deposit;
use App\Models\Student;

class PDFController extends Controller
{
    public function getMonth(string $month, string $year)
    {
        $user = auth()->user();
        $students = Student::where('grade_id', $user->grade_id)->get();
        $thisMonth = $this->getMonthNumber($month);
        $deposits = Deposit::whereHas('student', function ($query) use ($user) {
            $query->where('grade_id', $user->grade_id); 
        })->whereMonth('input_date', $thisMonth)->whereYear('input_date', $year)->get();
        $credits = Credit::whereHas('student', function ($query) use ($user) {
            $query->where('grade_id', $user->grade_id); 
        })->whereMonth('input_date', $thisMonth)->whereYear('input_date', $year)->get();
    
        $days = '';
        switch (strtolower($month)) {
            case 'januari':
            case 'maret':
            case 'mei':
            case 'juli':
            case 'agustus':
            case 'oktober':
            case 'desember':
                $days = 31;
                break;
            case 'februari':
                $days = 28;
                break;
            case 'april':
            case 'juni':
            case 'september':
            case 'november':
                $days = 30;
                break;
            default:
                $days = null;
                break;
        }

        // cek kabisat
        if ($thisMonth == '02') {
            if ($year % 4 == 0) {
                $days = 29;
            }
        }

        $data = [
            'students' => $students, 
            'days' => $days,
            'month' => $month,
            'deposits' => $deposits,
            'year' => $year,
            'credits' => $credits,
        ];

        $pdf = PDF::loadView('pdfmonth', $data);

        return $pdf->stream();
        // return $pdf->download('laporan-bulanan-'.$month.'-'.$year.'.pdf');

        // return view('pdfmonth', [
        //     'students' => $students, 
        //     'days' => $days,
        //     'month' => $month,
        //     'deposits' => $deposits,
        //     'year' => $year,
        // ]);
    }
    
    public function getMonthNumber(string $month)
    {
        $months = [
            'januari' => '01',
            'februari' => '02',
            'maret' => '03',
            'april' => '04',
            'mei' => '05',
            'juni' => '06',
            'juli' => '07',
            'agustus' => '08',
            'september' => '09',
            'oktober' => '10',
            'november' => '11',
            'desember' => '12',
        ];
    
        return $months[strtolower($month)];
    }
}