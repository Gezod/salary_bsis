<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Slip Gaji Bulanan - {{ $payroll->employee->nama }}</title>
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
        .bpjs-section {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .bpjs-section h4 {
            margin: 0 0 10px 0;
            color: #333;
            font-size: 14px;
        }
        .bpjs-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .bpjs-table td {
            padding: 5px 10px;
            border-bottom: 1px dotted #ccc;
        }
        .bpjs-table .label {
            font-weight: bold;
            width: 60%;
        }
        .signatures {
            margin-top: 40px;
            width: 100%;
            text-align: center;
        }
        .payment-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
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
        <p>Telp: (0851)-0009-0858 | Email: banksampahinduksurabaya@gmail.com</p>
    </div>

    <div class="slip-title">
        SLIP GAJI BULANAN STAFF<br>
        <small>Periode: {{ $payroll->month_name }}</small>
    </div>

    <div class="period-highlight">
        <strong>PERIODE PEMBAYARAN:</strong> {{ \Carbon\Carbon::create($payroll->year, $payroll->month, 1)->translatedFormat('F Y') }}
        <br><small>({{ $payroll->working_days }} hari kerja, {{ $payroll->present_days }} hari hadir)</small>
    </div>

    <table class="info-table">
        <tr>
            <td class="label">Nama Lengkap</td>
            <td>{{ $payroll->employee->nama }}</td>
            <td class="label">NIP</td>
            <td>{{ $payroll->employee->nip }}</td>
        </tr>
        <tr>
            <td class="label">Jabatan</td>
            <td>{{ $payroll->employee->jabatan }}</td>
            <td class="label">Departemen</td>
            <td>{{ ucfirst($payroll->employee->departemen) }}</td>
        </tr>
        <tr>
            <td class="label">Kantor</td>
            <td>{{ $payroll->employee->kantor }}</td>
            <td class="label">Hari Hadir</td>
            <td>{{ $payroll->present_days }} dari {{ $payroll->working_days }} hari kerja</td>
        </tr>
        @if ($payroll->bpjs_setting)
            <tr>
                <td class="label">No. BPJS</td>
                <td>{{ $payroll->bpjs_setting->bpjs_number }}</td>
                <td class="label">BPJS Tunjangan</td>
                <td>Rp {{ number_format($payroll->bpjs_allowance, 0, ',', '.') }}</td>
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
                    @if ($payroll->employee->departemen == 'staff')
                        @php
                            $staffSetting = \App\Models\StaffPayrollSetting::where(
                                'employee_id',
                                $payroll->employee->id,
                            )->first();
                        @endphp
                        Gaji tetap bulanan
                        @if ($staffSetting)
                            (Rp {{ number_format($staffSetting->monthly_salary, 0, ',', '.') }})
                        @endif
                    @else
                        {{ $payroll->present_days }} hari hadir × Rp {{ number_format($payroll->employee->daily_salary ?? 0, 0, ',', '.') }}
                    @endif
                </td>
                <td class="amount">Rp {{ number_format($payroll->basic_salary, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Uang Lembur</td>
                <td>Lembur bulan {{ $payroll->month_name }}</td>
                <td class="amount">Rp {{ number_format($payroll->overtime_pay, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Uang Makan</td>
                <td>
                    @if ($payroll->employee->departemen == 'staff')
                        @php
                            $staffSetting = \App\Models\StaffPayrollSetting::where(
                                'employee_id',
                                $payroll->employee->id,
                            )->first();
                            $dailyMealAllowance = $staffSetting ? $staffSetting->daily_meal_allowance : 0;
                        @endphp
                        {{ $payroll->present_days }} hari hadir × Rp {{ number_format($dailyMealAllowance, 0, ',', '.') }}
                        <br><small style="color: #666;">(Hanya hari masuk kerja)</small>
                    @else
                        {{ $payroll->present_days }} hari hadir × Rp {{ number_format($payroll->employee->meal_allowance ?? 0, 0, ',', '.') }}
                    @endif
                </td>
                <td class="amount">Rp {{ number_format($payroll->meal_allowance, 0, ',', '.') }}</td>
            </tr>
            @if ($payroll->bpjs_allowance > 0)
                <tr>
                    <td>BPJS Tunjangan</td>
                    <td>Tunjangan BPJS dari perusahaan</td>
                    <td class="amount">Rp {{ number_format($payroll->bpjs_allowance, 0, ',', '.') }}</td>
                </tr>
            @endif
            <tr class="total-row">
                <td colspan="2"><strong>TOTAL PENDAPATAN KOTOR</strong></td>
                <td class="amount"><strong>Rp {{ number_format($payroll->gross_salary, 0, ',', '.') }}</strong></td>
            </tr>
            @if ($payroll->employee->departemen != 'staff' && $payroll->total_fines > 0)
                <tr>
                    <td>Denda</td>
                    <td>Denda keterlambatan dan pelanggaran</td>
                    <td class="amount">Rp {{ number_format($payroll->total_fines, 0, ',', '.') }}</td>
                </tr>
            @endif
            @if ($payroll->bpjs_deduction > 0)
                <tr>
                    <td>Total Premi BPJS</td>
                    <td>Total Premi BPJS periode ini</td>
                    <td class="amount">Rp {{ number_format($payroll->bpjs_deduction, 0, ',', '.') }}</td>
                </tr>
            @endif
            <tr class="net-salary-row">
                <td colspan="2"><strong>GAJI BERSIH YANG DITERIMA</strong></td>
                <td class="amount"><strong>Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>

    {{-- BPJS Premium Details --}}
    @if ($payroll->bpjs_premium || $payroll->bpjs_allowance > 0)
        <div class="bpjs-section">
            <h4><i class="fas fa-shield-alt"></i> RINCIAN BPJS BULANAN</h4>
            <table class="bpjs-table">
                @if ($payroll->bpjs_allowance > 0)
                    <tr>
                        <td class="label">Tunjangan BPJS (dari perusahaan):</td>
                        <td><strong>Rp {{ number_format($payroll->bpjs_allowance, 0, ',', '.') }}</strong></td>
                    </tr>
                @endif
                @if ($payroll->bpjs_premium)
                    <tr>
                        <td class="label">Total Premi BPJS periode ini:</td>
                        <td><strong>Rp {{ number_format($payroll->bpjs_premium->premium_amount, 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <td class="label">Langsung dipotong dari gaji:</td>
                        <td><strong>Rp {{ number_format($payroll->bpjs_deduction, 0, ',', '.') }}</strong></td>
                    </tr>
                @else
                    <tr>
                        <td class="label">Total Premi BPJS periode ini:</td>
                        <td><strong>Rp {{ number_format($payroll->bpjs_deduction, 0, ',', '.') }}</strong></td>
                    </tr>
                @endif
            </table>

            @if ($payroll->bpjs_premium && $payroll->bpjs_premium->notes)
                <div style="margin-top: 10px; padding: 8px; background: #f8f9fa; border-radius: 3px;">
                    <strong>Catatan:</strong> {{ $payroll->bpjs_premium->notes }}
                </div>
            @endif
        </div>
    @endif

    <div class="payment-info">
        <h4>Informasi Pembayaran</h4>
        <div style="display: table; width: 100%;">
            <div style="display: table-cell; width: 50%;">
                <strong>Metode Pembayaran:</strong> {{ ucfirst(str_replace('_', ' ', $payroll->payment_method)) }}<br>
                <strong>Tanggal Pembayaran:</strong> {{ $payroll->payment_date->format('d F Y') }}
            </div>
            <div style="display: table-cell; width: 50%;">
                @if ($payroll->payment_method !== 'cash' && $payroll->employee->bank_name)
                    <div class="bank-info" style="background-color: #e3f2fd; padding: 10px; border-radius: 3px;">
                        <strong>Data Bank Penerima:</strong><br>
                        Bank: {{ $payroll->employee->bank_name }}<br>
                        No. Rekening: {{ $payroll->employee->account_number }}
                    </div>
                @endif
            </div>
        </div>
        @if ($payroll->notes)
            <div style="margin-top: 10px;">
                <strong>Catatan:</strong> {{ $payroll->notes }}
            </div>
        @endif
    </div>

    <div class="signatures" style="text-align: right;">
        <div style="display: inline-block; text-align: right;">
            <strong>Mengetahui,</strong>
            <div style="height: 100px; margin: 10px 0; position: relative;">
                <img src="{{ storage_path('app/public/docs/images/ttd-fix.png') }}" alt="Tanda Tangan" style="height: 80px; object-fit: contain;">
                <div class="signature-info">
                    <strong>Nur Ainiya Fariza, S.E.</strong><br>
                    <span>Manajer Dept. HRD & Keuangan</span>
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        <p><strong>PENTING:</strong></p>
        <p>• Total Premi BPJS sudah dipotong langsung dari pendapatan kotor</p>
        <p>• Slip gaji ini adalah bukti resmi pembayaran gaji bulanan</p>
        <p>• Simpan slip gaji ini untuk keperluan administrasi</p>
        <hr>
        <p>Slip gaji ini digenerate secara otomatis pada {{ now()->setTimezone('Asia/Jakarta')->format('d F Y H:i:s') }} WIB</p>
        <p>Bank Sampah Induk Surabaya - Sistem Payroll Terintegrasi</p>
    </div>
</body>

</html>
