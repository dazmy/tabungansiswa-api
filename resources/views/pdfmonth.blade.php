@inject('carbon', 'Carbon\Carbon')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Tabungan Per Hari - Bulan {{ strtoupper($month) }}</title>
    <style>
        table, th, td {
          border: 1px solid black;
          border-collapse: collapse;
        }
        th, td {
            text-align: center;
        }
        .width-40 {
            width: 40px;
        }
        .width-220 {
            width: 220px;
        }
        .width-100 {
            width: 100px;
        }
        .height-90 {
            height: 90px;
        }
        .height-120 {
            height: 120px;
        }
        table {
          /* border-spacing: 50px; */
          width: 100%;
        }
    </style>
</head>

<body>
    <h2 style="text-align:center; text-transform: uppercase;">TABUNGAN {{ $students[0]->grade->name }}</h2>
    <h4 style="text-align:center;">Bulan : {{ Str::ucfirst($month) }} {{ $year }}</h4>
    <h4 style="text-align:center;">Tahun Ajaran {{ $year }}</h4>
    <table>
        <thead>
            <tr>
                <th rowspan="2">No</th>
                <th rowspan="2" class="width-220" style="padding: 30px">Nama Siswa</th>
                <th colspan="{{ $days }}">Bulan : {{ strtoupper($month) }} {{ $year }} / Tanggal</th>
                <th rowspan="2" class="width-100">Jumlah</th>
            </tr>
            <tr>
                @for ($i = 1; $i <= $days; $i++)
                    <th style="text-align: center" class="width-40">{{ $i }}</th>
                @endfor
            </tr>
        </thead>
        <tbody>
            @php
                $index = 1;
            @endphp
            @foreach ($students as $student)
                <tr>
                    <td>{{ $index++ }}</td>
                    <td style="width: auto;">{{ $student->name }}</td>
                    @php
                        $depositsByDate = [];

                        // Inisialisasi array dengan nilai 0 untuk setiap tanggal dalam rentang 1 hingga $days
                        for ($i = 1; $i <= $days; $i++) {
                            $depositsByDate[$i] = 0;
                        }

                        // Memasukkan data tabungan siswa ke dalam array berdasarkan tanggal - credit
                        $studentDeposits = $deposits->where('student_id', $student->id);
                        $studentCredits = $credits->where('student_id', $student->id);
                        foreach ($studentDeposits as $item) {
                            $day = $carbon::parse($item->input_date)->format('j');
                            $depositsByDate[$day] = $item->deposit;
                        }
                        foreach ($studentCredits as $item) {
                            $day = $carbon::parse($item->input_date)->format('j');
                            $depositsByDate[$day] -= $item->credit;
                        }
                    @endphp

                    @for ($i = 1; $i <= $days; $i++)
                    @if ($depositsByDate[$i] == 0)
                    <td class="height-90">{{ $depositsByDate[$i] }}</td>
                    @else
                    <td class="height-90" text-rotate="90">{{ $depositsByDate[$i] }}</td>
                    @endif
                    @endfor

                    <td>{{ $studentDeposits->sum('deposit') - $studentCredits->sum('credit') }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="2">JUMLAH</td>
                @for ($i = 1; $i <= $days; $i++)
                    @php
                        $totalDepositsPerDay[$i] = 0;
                        $formattedDay = str_pad($i, 2, '0', STR_PAD_LEFT);
                    @endphp
                    @foreach ($deposits as $deposit)
                        @if ($carbon::parse($deposit->input_date)->format('j') == $formattedDay)
                            @php
                                $totalDepositsPerDay[$i] += $deposit->deposit;
                            @endphp
                        @endif
                    @endforeach
                    @foreach ($credits as $credit)
                        @if ($carbon::parse($credit->input_date)->format('j') == $formattedDay)
                            @php
                                $totalDepositsPerDay[$i] -= $credit->credit;
                            @endphp
                        @endif
                    @endforeach
                    @if ($totalDepositsPerDay[$i] == 0)
                    <td class="height-120">{{ $totalDepositsPerDay[$i] }}</td>
                    @else
                    <td class="height-120" text-rotate="90">{{ $totalDepositsPerDay[$i] }}</td>
                    @endif
                @endfor
                <td>{{ $deposits->sum('deposit') - $credits->sum('credit') }}</td>
            </tr>
            <tr>
                <td colspan="2">TANDA TANGAN<br>BENDAHARA/KEPALA SEKOLAH</td>
                @for ($i = 1; $i <= $days; $i++)
                    <td class="height-120"></td>
                @endfor
                <td></td>
            </tr>
        </tbody>
    </table>
    <div style="margin-top: 30px; width: 100%">
        <div style="float:left; width: 20%;">
            <span>Mengetahui :</span><br>
            <span>Kepala UPTD SDN 4 Margadadi</span>
            <br><br><br><br><hr>
            <span>NIP.</span>
        </div>
        <div style="float: right; width: 20%;">
            <span>Indramayu, 1 {{ Str::ucfirst($month) }} {{ $year }}</span><br>
            <span>Guru {{ $students[0]->grade->name }}</span>
            <br><br><br><br><hr>
            <span>NIP.</span>
        </div>
    </div>
</body>
</html>

