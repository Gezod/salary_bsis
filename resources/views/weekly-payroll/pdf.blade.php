<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Payroll Mingguan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #333;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .filters {
            background: #f5f5f5;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .filters h3 {
            margin: 0 0 10px 0;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 10px;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            color: #333;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .summary {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .summary h3 {
            margin: 0 0 10px 0;
            color: #333;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #666;
            font-size: 10px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN PAYROLL MINGGUAN</h1>
        <p>Bank Sampah Surabaya</p>
        <p>Periode: {{ now()->format('d F Y') }}</p>
    </div>

    @if($request->filled('date_range') || $request->filled('employee') || $request->filled('department') || $request->filled('status'))
    <div class="filters">
        <h3>Filter yang Diterapkan:</h3>
        @if($request->filled('date_range'))
            <p><strong>Rentang Tanggal:</strong> {{ $request->date_range }}</p>
        @endif
        @if($request->filled('employee'))
            <p><strong>Karyawan:</strong> {{ $request->employee }}</p>
        @endif
        @if($request->filled('department'))
            <p><strong>Departemen:</strong> {{ ucfirst($request->department) }}</p>
        @endif
        @if($request->filled('status'))
            <p><strong>Status:</strong> {{ ucfirst($request->status) }}</p>
        @endif
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Dept</th>
                <th>Periode</th>
                <th>Hadir</th>
                <th class="text-right">Gaji Pokok</th>
                <th class="text-right">Lembur</th>
                <th class="text-right">U.Makan</th>
                <th class="text-right">Denda</th>
                <th class="text-right">BPJS</th>
                <th class="text-right">Total</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($weeklyPayrolls as $index => $payroll)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $payroll->employee->nama }}</td>
                <td class="text-center">{{ ucfirst($payroll->employee->departemen) }}</td>
                <td class="text-center">{{ $payroll->period_name }}</td>
                <td class="text-center">{{ $payroll->present_days }}/{{ $payroll->working_days }}</td>
                <td class="text-right">Rp {{ number_format($payroll->basic_salary, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($payroll->overtime_pay, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($payroll->meal_allowance, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($payroll->total_fines, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($payroll->bpjs_deduction, 0, ',', '.') }}</td>
                <td class="text-right"><strong>Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}</strong></td>
                <td class="text-center">{{ ucfirst($payroll->status) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="12" class="text-center">Tidak ada data payroll mingguan</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    @if($weeklyPayrolls->count() > 0)
    <div class="summary">
        <h3>Ringkasan</h3>
        <div class="summary-row">
            <span>Total Karyawan/Staff:</span>
            <span>{{ $weeklyPayrolls->count() }} orang</span>
        </div>
        <div class="summary-row">
            <span>Total Gaji Pokok:</span>
            <span>Rp {{ number_format($weeklyPayrolls->sum('basic_salary'), 0, ',', '.') }}</span>
        </div>
        <div class="summary-row">
            <span>Total Lembur:</span>
            <span>Rp {{ number_format($weeklyPayrolls->sum('overtime_pay'), 0, ',', '.') }}</span>
        </div>
        <div class="summary-row">
            <span>Total Uang Makan:</span>
            <span>Rp {{ number_format($weeklyPayrolls->sum('meal_allowance'), 0, ',', '.') }}</span>
        </div>
        <div class="summary-row">
            <span>Total Denda:</span>
            <span>Rp {{ number_format($weeklyPayrolls->sum('total_fines'), 0, ',', '.') }}</span>
        </div>
        <div class="summary-row">
            <span>Total BPJS:</span>
            <span>Rp {{ number_format($weeklyPayrolls->sum('bpjs_deduction'), 0, ',', '.') }}</span>
        </div>
        <div class="summary-row">
            <span><strong>Total Gaji Bersih:</strong></span>
            <span><strong>Rp {{ number_format($weeklyPayrolls->sum('net_salary'), 0, ',', '.') }}</strong></span>
        </div>
        <div class="summary-row">
            <span>Sudah Dibayar:</span>
            <span>{{ $weeklyPayrolls->where('status', 'paid')->count() }} orang</span>
        </div>
        <div class="summary-row">
            <span>Belum Dibayar:</span>
            <span>{{ $weeklyPayrolls->where('status', 'pending')->count() }} orang</span>
        </div>
    </div>
    @endif

    <div class="footer">
        <p>Laporan ini digenerate secara otomatis pada {{ now()->format('d F Y H:i:s') }}</p>
        <p>Bank Sampah Surabaya - Sistem Payroll Mingguan</p>
    </div>
</body>
</html>
