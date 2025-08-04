<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Slip Gaji Mingguan - {{ $weeklyPayroll->employee->nama }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .logo {
            width: 80px;
            height: auto;
            margin-bottom: 10px;
        }
        .header h1 {
            margin: 10px 0 5px 0;
            color: #333;
            font-size: 20px;
            font-weight: bold;
        }
        .header p {
            margin: 5px 0;
            color: #666;
            font-size: 11px;
        }
        .slip-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin: 20px 0;
            color: #333;
        }
        .employee-info {
            margin-bottom: 20px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 5px 10px;
            border: 1px solid #ddd;
        }
        .info-table .label {
            background-color: #f5f5f5;
            font-weight: bold;
            width: 30%;
        }
        .salary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .salary-table th, .salary-table td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
        }
        .salary-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        .salary-table .amount {
            text-align: right;
            font-weight: bold;
        }
        .total-row {
            background-color: #e8f4f8;
            font-weight: bold;
        }
        .net-salary-row {
            background-color: #d4edda;
            font-weight: bold;
            font-size: 14px;
        }
        .signatures {
            margin-top: 40px;
            width: 100%;
            text-align: center;
        }
        .signature-name {
            font-weight: bold;
            margin-top: 5px;
        }
        .signature-title {
            font-size: 11px;
            color: #666;
            margin-top: 2px;
        }
        .nip {
            font-size: 10px;
            color: #888;
            margin-top: 3px;
        }
        .payment-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .payment-info h4 {
            margin: 0 0 10px 0;
            color: #333;
        }
        .bank-info {
            background-color: #e3f2fd;
            padding: 10px;
            border-radius: 3px;
            margin-top: 10px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #666;
            font-size: 10px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .period-highlight {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ public_path('images/Logo-Bank-Sampah-Surabaya-bank-sampah-induk-surabaya-v2 (1).png') }}" alt="Bank Sampah Logo" class="logo">
        <h1>BANK SAMPAH INDUK SURABAYA</h1>
        <p>Jl. Raya Menur No.31-A, Manyar Sabrangan, Kec. Mulyorejo, Surabaya, Jawa Timur 60116</p>
        <p>Telp: (0851)-0009-0858 | Email: https://banksampahinduksurabaya.id/</p>
    </div>

    <div class="slip-title">
        SLIP GAJI MINGGUAN KARYAWAN/STAFF<br>
        <small>Periode: {{ $weeklyPayroll->period_name }}</small>
    </div>

    <div class="period-highlight">
        <strong>PERIODE PEMBAYARAN:</strong> {{ $weeklyPayroll->start_date->format('d F Y') }} s/d {{ $weeklyPayroll->end_date->format('d F Y') }}
        <br><small>({{ $weeklyPayroll->start_date->diffInDays($weeklyPayroll->end_date) + 1 }} hari kalender, {{ $weeklyPayroll->working_days }} hari kerja)</small>
    </div>

    <table class="info-table">
        <tr>
            <td class="label">Nama Lengkap</td>
            <td>{{ $weeklyPayroll->employee->nama }}</td>
            <td class="label">NIP</td>
            <td>{{ $weeklyPayroll->employee->nip }}</td>
        </tr>
        <tr>
            <td class="label">Jabatan</td>
            <td>{{ $weeklyPayroll->employee->jabatan }}</td>
            <td class="label">Departemen</td>
            <td>{{ ucfirst($weeklyPayroll->employee->departemen) }}</td>
        </tr>
        <tr>
            <td class="label">Kantor</td>
            <td>{{ $weeklyPayroll->employee->kantor }}</td>
            <td class="label">Hari Hadir</td>
            <td>{{ $weeklyPayroll->present_days }} dari {{ $weeklyPayroll->working_days }} hari kerja</td>
        </tr>
        @php
            $bpjsSetting = \App\Models\BpjsSetting::where('employee_id', $weeklyPayroll->employee->id)->first();
        @endphp
        @if($bpjsSetting)
        <tr>
            <td class="label">No. BPJS</td>
            <td>{{ $bpjsSetting->bpjs_number }}</td>
            <td class="label">BPJS Mingguan</td>
            <td>Rp {{ number_format($bpjsSetting->bpjs_weekly_amount, 0, ',', '.') }}</td>
        </tr>
        @endif
    </table>

    <table class="salary-table">
        <thead>
            <tr>
                <th>KOMPONEN GAJI</th>
                <th>KETERANGAN</th>
                <th>JUMLAH</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Gaji Pokok</td>
                <td>
                    @if($weeklyPayroll->employee->departemen == 'staff')
                        Proporsional dari gaji bulanan ({{ $weeklyPayroll->present_days }} hari)
                    @else
                        {{ $weeklyPayroll->present_days }} hari hadir × Rp {{ number_format($weeklyPayroll->employee->daily_salary ?? 0, 0, ',', '.') }}
                    @endif
                </td>
                <td class="amount">Rp {{ number_format($weeklyPayroll->basic_salary, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Uang Lembur</td>
                <td>Lembur periode {{ $weeklyPayroll->period_name }}</td>
                <td class="amount">Rp {{ number_format($weeklyPayroll->overtime_pay, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Uang Makan</td>
                <td>
                    @if($weeklyPayroll->employee->departemen == 'staff')
                        @php
                            $staffSetting = \App\Models\StaffPayrollSetting::where('employee_id', $weeklyPayroll->employee->id)->first();
                            $dailyMealAllowance = $staffSetting ? $staffSetting->daily_meal_allowance : 0;
                        @endphp
                        {{ $weeklyPayroll->present_days }} hari hadir × Rp {{ number_format($dailyMealAllowance, 0, ',', '.') }}
                        <br><small style="color: #666;">(Hanya hari masuk kerja)</small>
                    @else
                        {{ $weeklyPayroll->present_days }} hari hadir × Rp {{ number_format($weeklyPayroll->employee->meal_allowance ?? 0, 0, ',', '.') }}
                    @endif
                </td>
                <td class="amount">Rp {{ number_format($weeklyPayroll->meal_allowance, 0, ',', '.') }}</td>
            </tr>
            <tr class="total-row">
                <td colspan="2"><strong>TOTAL PENDAPATAN</strong></td>
                <td class="amount"><strong>Rp {{ number_format($weeklyPayroll->gross_salary, 0, ',', '.') }}</strong></td>
            </tr>
            @if($weeklyPayroll->employee->departemen != 'staff' && $weeklyPayroll->total_fines > 0)
            <tr>
                <td>Denda</td>
                <td>Denda keterlambatan dan pelanggaran</td>
                <td class="amount">Rp {{ number_format($weeklyPayroll->total_fines, 0, ',', '.') }}</td>
            </tr>
            @endif
            @if($weeklyPayroll->bpjs_deduction > 0)
            <tr>
                <td>BPJS</td>
                <td>Iuran BPJS mingguan</td>
                <td class="amount">Rp {{ number_format($weeklyPayroll->bpjs_deduction, 0, ',', '.') }}</td>
            </tr>
            @endif
            <tr class="net-salary-row">
                <td colspan="2"><strong>GAJI BERSIH YANG DITERIMA</strong></td>
                <td class="amount"><strong>Rp {{ number_format($weeklyPayroll->net_salary, 0, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="payment-info">
        <h4>Informasi Pembayaran</h4>
        <div style="display: table; width: 100%;">
            <div style="display: table-cell; width: 50%;">
                <strong>Metode Pembayaran:</strong> {{ ucfirst(str_replace('_', ' ', $weeklyPayroll->payment_method)) }}<br>
                <strong>Tanggal Pembayaran:</strong> {{ $weeklyPayroll->payment_date->format('d F Y') }}
            </div>
            <div style="display: table-cell; width: 50%;">
                @if($weeklyPayroll->payment_method !== 'cash' && $weeklyPayroll->employee->bank_name)
                    <div class="bank-info">
                        <strong>Data Bank Penerima:</strong><br>
                        Bank: {{ $weeklyPayroll->employee->bank_name }}<br>
                        No. Rekening: {{ $weeklyPayroll->employee->account_number }}
                    </div>
                @endif
            </div>
        </div>
        @if($weeklyPayroll->notes)
            <div style="margin-top: 10px;">
                <strong>Catatan:</strong> {{ $weeklyPayroll->notes }}
            </div>
        @endif
    </div>

    <div class="signatures" style="text-align: right;">
        <div style="display: inline-block; text-align: right;">
            <strong>Mengetahui,</strong>
            <div style="height: 100px; margin: 10px 0; position: relative;">
                <img src="{{ storage_path('app/public/docs/images/ttd-fix.png') }}" alt="Tanda Tangan" style="height: 80px; object-fit: contain;">
            </div>
            <div class="signature-name">Nur Ainiya Fariza, S.E.</div>
            <div class="signature-title">Manajer Dept. HRD & Keuangan</div>
        </div>
    </div>

</body>
</html>
