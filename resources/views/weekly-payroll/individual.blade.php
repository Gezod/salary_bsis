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
        .bpjs-note {
            background-color: #e7f3ff;
            border: 1px solid #b8daff;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
            font-size: 11px;
            color: #0056b3;
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
        SLIP GAJI MINGGUAN KARYAWAN<br>
        <small>Periode: {{ $weeklyPayroll->period_name }}</small>
    </div>

    <div class="period-highlight">
        <strong>PERIODE PEMBAYARAN:</strong> {{ $weeklyPayroll->start_date->format('d F Y') }} s/d {{ $weeklyPayroll->end_date->format('d F Y') }}
        <br><small>({{ $weeklyPayroll->start_date->diffInDays($weeklyPayroll->end_date) + 1 }} hari kalender, {{ $weeklyPayroll->working_days }} hari kerja)</small>
        @if($weeklyPayroll->isEndOfMonthPeriod())
            <br><span style="color: #856404; font-weight: bold;">⚡ GAJI AKHIR BULAN - TERMASUK BPJS (Tanggal {{ $weeklyPayroll->end_date->day }})</span>
        @endif
    </div>

    @if(!$weeklyPayroll->isEndOfMonthPeriod())
    <div class="bpjs-note">
        <strong>CATATAN:</strong> BPJS hanya dipotong dari gaji mingguan di akhir bulan (tanggal 28-31) atau gaji bulanan.
    </div>
    @endif

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
        @if ($weeklyPayroll->hasActiveBpjs())
            <tr>
                <td class="label">No. BPJS</td>
                <td>{{ $weeklyPayroll->bpjs_setting->bpjs_number }}</td>
                <td class="label">BPJS Tunjangan Bulanan</td>
                <td>{{ $weeklyPayroll->formatted_bpjs_allowance }}</td>
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
                    @if ($weeklyPayroll->employee->departemen == 'staff')
                        @php
                            $staffSetting = \App\Models\StaffPayrollSetting::where('employee_id', $weeklyPayroll->employee->id)->first();
                            $daysInPeriod = $weeklyPayroll->start_date->diffInDays($weeklyPayroll->end_date) + 1;
                            $dailySalary = $staffSetting ? $staffSetting->monthly_salary / 30 : 0;
                        @endphp
                        {{ $daysInPeriod }} hari × Rp {{ number_format($dailySalary, 0, ',', '.') }} (pro-rata dari gaji bulanan)
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
                    @if ($weeklyPayroll->employee->departemen == 'staff')
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
            @if ($weeklyPayroll->bpjs_allowance > 0)
                <tr>
                    <td>BPJS Tunjangan</td>
                    <td>Tunjangan BPJS dari perusahaan (gaji akhir bulan)</td>
                    <td class="amount">Rp {{ number_format($weeklyPayroll->bpjs_allowance, 0, ',', '.') }}</td>
                </tr>
            @endif
            <tr class="total-row">
                <td colspan="2"><strong>TOTAL PENDAPATAN KOTOR</strong></td>
                <td class="amount"><strong>Rp {{ number_format($weeklyPayroll->gross_salary, 0, ',', '.') }}</strong></td>
            </tr>
            @if($weeklyPayroll->total_fines > 0)
            <tr>
                <td>Denda</td>
                <td>Denda keterlambatan dan pelanggaran</td>
                <td class="amount">Rp {{ number_format($weeklyPayroll->total_fines, 0, ',', '.') }}</td>
            </tr>
            @endif
            @if ($weeklyPayroll->bpjs_deduction > 0)
                <tr>
                    <td>Total Premi BPJS</td>
                    <td>Total Premi BPJS periode ini (gaji akhir bulan)</td>
                    <td class="amount">Rp {{ number_format($weeklyPayroll->bpjs_deduction, 0, ',', '.') }}</td>
                </tr>
            @endif
            <tr class="net-salary-row">
                <td colspan="2"><strong>GAJI BERSIH YANG DITERIMA</strong></td>
                <td class="amount"><strong>Rp {{ number_format($weeklyPayroll->net_salary, 0, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>

    {{-- BPJS Details for End of Month --}}
    @if($weeklyPayroll->shouldShowBpjsInfo())
        <div class="bpjs-section">
            <h4><i class="fas fa-shield-alt"></i> RINCIAN BPJS BULANAN</h4>
            <table class="bpjs-table">
                <tr>
                    <td class="label">Tunjangan BPJS (dari perusahaan):</td>
                    <td><strong>Rp {{ number_format($weeklyPayroll->bpjs_allowance, 0, ',', '.') }}</strong></td>
                </tr>
                @if($weeklyPayroll->bpjs_premium_for_period)
                    <tr>
                        <td class="label">Total Premi BPJS periode ini:</td>
                        <td><strong>Rp {{ number_format($weeklyPayroll->bpjs_premium_for_period->premium_amount, 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <td class="label">Langsung dipotong dari gaji:</td>
                        <td><strong>Rp {{ number_format($weeklyPayroll->bpjs_deduction, 0, ',', '.') }}</strong></td>
                    </tr>
                @else
                    <tr>
                        <td class="label">Total Premi BPJS periode ini:</td>
                        <td><strong>Rp {{ number_format($weeklyPayroll->bpjs_deduction, 0, ',', '.') }}</strong></td>
                    </tr>
                @endif
            </table>

            @if($weeklyPayroll->bpjs_premium_for_period && $weeklyPayroll->bpjs_premium_for_period->notes)
                <div style="margin-top: 10px; padding: 8px; background: #f8f9fa; border-radius: 3px;">
                    <strong>Catatan BPJS:</strong> {{ $weeklyPayroll->bpjs_premium_for_period->notes }}
                </div>
            @endif
        </div>
    @endif

    <div class="payment-info">
        <h4>Informasi Pembayaran</h4>
        <div style="display: table; width: 100%;">
            <div style="display: table-cell; width: 50%;">
                <strong>Metode Pembayaran:</strong> {{ ucfirst(str_replace('_', ' ', $weeklyPayroll->payment_method)) }}<br>
                <strong>Tanggal Pembayaran:</strong> {{ $weeklyPayroll->payment_date->format('d F Y') }}
            </div>
            <div style="display: table-cell; width: 50%;">
                @if($weeklyPayroll->payment_method !== 'cash' && $weeklyPayroll->employee->bank_name)
                    <div class="bank-info" style="background-color: #e3f2fd; padding: 10px; border-radius: 3px;">
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
                <div class="signature-info">
                    <strong>Nur Ainiya Fariza, S.E.</strong><br>
                    <span>Manajer Dept. HRD & Keuangan</span>
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        <p><strong>PENTING:</strong></p>
        @if($weeklyPayroll->isEndOfMonthPeriod())
            <p>• Total Premi BPJS sudah dipotong langsung dari pendapatan kotor</p>
            <p>• Ini adalah gaji mingguan akhir bulan yang sudah termasuk perhitungan BPJS</p>
        @else
            <p>• BPJS hanya dipotong dari gaji mingguan di akhir bulan (tanggal 28-31) atau gaji bulanan</p>
        @endif
        <p>• Slip gaji ini adalah bukti resmi pembayaran gaji mingguan karyawan</p>
        <p>• Simpan slip gaji ini untuk keperluan administrasi</p>
        <hr>
        <p>Slip gaji ini digenerate secara otomatis pada {{ now()->setTimezone('Asia/Jakarta')->format('d F Y H:i:s') }} WIB</p>
        <p>Bank Sampah Induk Surabaya - Sistem Payroll Terintegrasi</p>
    </div>
</body>
</html>
