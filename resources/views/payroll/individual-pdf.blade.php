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

        .salary-table th,
        .salary-table td {
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

        .payment-proof-section {
            margin-top: 40px;
            page-break-before: always;
            background-color: #f8f9fa;
            border: 2px solid #007bff;
            border-radius: 8px;
            padding: 25px;
        }

        .payment-proof-section h3 {
            color: #007bff;
            text-align: center;
            margin: 0 0 15px 0;
            font-size: 16px;
            text-transform: uppercase;
            font-weight: bold;
        }

        .payment-details {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .payment-details td {
            padding: 8px 12px;
            border-bottom: 1px solid #dee2e6;
            font-size: 11px;
        }

        .payment-details .label {
            background-color: #e9ecef;
            font-weight: bold;
            width: 30%;
            color: #495057;
        }

        .payment-details .value {
            color: #212529;
            font-weight: 600;
        }

        .bank-info {
            background-color: #e3f2fd;
            border: 1px solid #90caf9;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .bank-info h4 {
            margin: 0 0 8px 0;
            color: #1565c0;
            font-size: 12px;
            font-weight: bold;
        }

        .payment-proof-image {
            text-align: center;
            margin: 25px auto;
            padding: 15px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 90%;
        }

        .payment-proof-image img {
            max-width: 100%;
            height: auto;
            max-height: 500px;
            border: 1px solid #eee;
            border-radius: 4px;
        }

        .payment-notes {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
        }

        .payment-notes strong {
            color: #856404;
        }

        .payment-status {
            text-align: center;
            margin-bottom: 15px;
        }

        .status-paid {
            background-color: #d4edda;
            color: #155724;
            padding: 8px 16px;
            border: 1px solid #c3e6cb;
            border-radius: 20px;
            font-weight: bold;
            display: inline-block;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
            padding: 8px 16px;
            border: 1px solid #ffeaa7;
            border-radius: 20px;
            font-weight: bold;
            display: inline-block;
        }
    </style>
</head>

<body>
    <div class="header">
        <img src="{{ public_path('images/Logo-Bank-Sampah-Surabaya-bank-sampah-induk-surabaya-v2 (1).png') }}"
            alt="Bank Sampah Logo" class="logo">
        <h1>BANK SAMPAH INDUK SURABAYA</h1>
        <p>Jl. Raya Menur No.31-A, Manyar Sabrangan, Kec. Mulyorejo, Surabaya, Jawa Timur 60116</p>
        <p>Telp: (0851)-0009-0858 | Email: banksampahinduksurabaya@gmail.com</p>
    </div>

    <div class="slip-title">
        SLIP GAJI BULANAN STAFF<br>
        <small>Periode: {{ $payroll->month_name }}</small>
    </div>

    <div class="period-highlight">
        <strong>PERIODE PEMBAYARAN:</strong>
        {{ \Carbon\Carbon::create($payroll->year, $payroll->month, 1)->translatedFormat('F Y') }}
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
                        {{ $payroll->present_days }} hari hadir × Rp
                        {{ number_format($payroll->employee->daily_salary ?? 0, 0, ',', '.') }}
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
                        {{ $payroll->present_days }} hari hadir × Rp
                        {{ number_format($dailyMealAllowance, 0, ',', '.') }}
                        <br><small style="color: #666;">(Hanya hari masuk kerja)</small>
                    @else
                        {{ $payroll->present_days }} hari hadir × Rp
                        {{ number_format($payroll->employee->meal_allowance ?? 0, 0, ',', '.') }}
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

    <div class="signatures" style="text-align: right;">
        <div style="display: inline-block; text-align: right;">
            <strong>Mengetahui,</strong>
            <div style="height: 100px; margin: 10px 0; position: relative;">
                <img src="{{ storage_path('app/public/docs/images/ttd-fix.png') }}" alt="Tanda Tangan"
                    style="height: 80px; object-fit: contain;">
                <div class="signature-info">
                    <strong>Nur Ainiya Fariza, S.E.</strong><br>
                    <span>Manajer Dept. HRD & Keuangan</span>
                </div>
            </div>
        </div>
    </div>



    {{-- BUKTI PEMBAYARAN SECTION --}}
    @if ($payroll->status === 'paid')
        <div class="payment-proof-section">
            <h3>Bukti Pembayaran Gaji</h3>

            <div class="payment-status">
                <span class="status-paid">TELAH DIBAYAR</span>
            </div>

            <table class="payment-details">
                <tr>
                    <td class="label">Metode Pembayaran:</td>
                    <td class="value">{{ ucfirst(str_replace('_', ' ', $payroll->payment_method)) }}</td>
                </tr>
                <tr>
                    <td class="label">Tanggal Pembayaran:</td>
                    <td class="value">{{ $payroll->payment_date ? $payroll->payment_date->format('d F Y') : '-' }}
                    </td>
                </tr>
                <tr>
                    <td class="label">Jumlah Dibayar:</td>
                    <td class="value">Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}</td>
                </tr>
                @if ($payroll->payment_method !== 'cash' && $payroll->employee->bank_name)
                    <tr>
                        <td class="label">Nama Bank:</td>
                        <td class="value">{{ $payroll->employee->bank_name }}</td>
                    </tr>
                    <tr>
                        <td class="label">No. Rekening:</td>
                        <td class="value">{{ $payroll->employee->account_number ?? '-' }}</td>
                    </tr>
                @endif
            </table>

            @if ($payroll->payment_method !== 'cash' && $payroll->employee->bank_name)
                <div class="bank-info">
                    <h4>Informasi Transfer Bank</h4>
                    <strong>Penerima:</strong> {{ $payroll->employee->nama }}<br>
                    <strong>Bank:</strong> {{ $payroll->employee->bank_name }}<br>
                    <strong>No. Rekening:</strong> {{ $payroll->employee->account_number ?? 'Tidak tersedia' }}
                </div>
            @endif

            @if ($payroll->notes)
                <div class="payment-notes">
                    <strong> Catatan Pembayaran:</strong><br>
                    {{ $payroll->notes }}
                </div>
            @endif

            @if ($payroll->payment_proof)
                <div class="payment-proof-image" style="text-align: center; margin: 20px 0; page-break-inside: avoid;">
                    <h4 style="margin-bottom: 15px; color: #495057; font-size: 14px;">Bukti Transfer/Pembayaran:</h4>
                    <div
                        style="display: inline-block; border: 1px solid #ddd; padding: 10px; background: white; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
                        <img src="{{ storage_path('app/public/' . $payroll->payment_proof) }}" alt="Bukti Pembayaran"
                            style="max-width: 100%; height: auto; max-height: 400px; display: block; margin: 0 auto;">
                    </div>
                </div>
            @endif

            {{-- <div
                style="text-align: center; margin-top: 20px; padding: 10px; background-color: #d1ecf1; border: 1px solid #bee5eb; border-radius: 5px;">
                <strong style="color: #0c5460;">Pembayaran Telah Berhasil Diproses</strong><br>
                <small style="color: #0c5460;">Terima kasih atas dedikasi dan kerja keras Anda</small>
            </div> --}}
        </div>
    @else
        <div class="payment-proof-section">
            <h3>Status Pembayaran</h3>

            <div class="payment-status">
                <span class="status-pending"> MENUNGGU PEMBAYARAN</span>
            </div>

            <div
                style="text-align: center; padding: 20px; background-color: #fff3cd; border: 1px solid #ffeaa7; border-radius: 5px;">
                <strong style="color: #856404;">Pembayaran akan segera diproses</strong><br>
                <small style="color: #856404;">Slip gaji ini akan diperbarui setelah pembayaran selesai</small>
            </div>
        </div>
    @endif

</body>

</html>
