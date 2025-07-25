<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Denda Individual - {{ $employee->nama }}</title>
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
        .company-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .report-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .report-period {
            font-size: 14px;
            color: #666;
        }
        .employee-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .employee-info table {
            width: 100%;
        }
        .employee-info td {
            padding: 5px;
            vertical-align: top;
        }
        .employee-info .label {
            font-weight: bold;
            width: 120px;
        }
        .summary-cards {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .summary-card {
            flex: 1;
            background-color: #f8f9fa;
            padding: 15px;
            margin: 0 5px;
            text-align: center;
            border-radius: 5px;
            border: 1px solid #dee2e6;
        }
        .summary-card .value {
            font-size: 18px;
            font-weight: bold;
            color: #dc3545;
        }
        .summary-card .label {
            font-size: 11px;
            color: #666;
            margin-top: 5px;
        }
        .penalty-breakdown {
            margin-bottom: 20px;
        }
        .penalty-breakdown table {
            width: 100%;
            border-collapse: collapse;
        }
        .penalty-breakdown th,
        .penalty-breakdown td {
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: left;
        }
        .penalty-breakdown th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .penalty-breakdown .amount {
            text-align: right;
            font-weight: bold;
        }
        .attendance-table {
            margin-top: 20px;
        }
        .attendance-table table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }
        .attendance-table th,
        .attendance-table td {
            border: 1px solid #dee2e6;
            padding: 6px;
            text-align: center;
        }
        .attendance-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .late {
            background-color: #fff3cd;
            color: #856404;
        }
        .penalty {
            background-color: #f8d7da;
            color: #721c24;
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 11px;
            color: #666;
        }
        .signature {
            margin-top: 40px;
            text-align: right;
        }
        .signature-box {
            display: inline-block;
            text-align: center;
            margin-left: 50px;
        }
        .signature-line {
            border-top: 1px solid #333;
            width: 200px;
            margin-top: 60px;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">BANK SAMPAH SURABAYA</div>
        <div class="report-title">LAPORAN DENDA INDIVIDUAL</div>
        <div class="report-period">Periode: {{ $monthName }}</div>
    </div>

    <div class="employee-info">
        <table>
            <tr>
                <td class="label">Nama Karyawan:</td>
                <td>{{ $employee->nama }}</td>
                <td class="label">NIP:</td>
                <td>{{ $employee->nip }}</td>
            </tr>
            <tr>
                <td class="label">Departemen:</td>
                <td>{{ ucfirst($employee->departemen) }}</td>
                <td class="label">PIN:</td>
                <td>{{ $employee->pin }}</td>
            </tr>
            <tr>
                <td class="label">Jabatan:</td>
                <td>{{ $employee->jabatan }}</td>
                <td class="label">Kantor:</td>
                <td>{{ $employee->kantor }}</td>
            </tr>
        </table>
    </div>

    <div class="summary-cards">
        <div class="summary-card">
            <div class="value">{{ $summary['total_days'] }}</div>
            <div class="label">Total Hari Kerja</div>
        </div>
        <div class="summary-card">
            <div class="value">{{ $summary['late_days'] }}</div>
            <div class="label">Hari Terlambat</div>
        </div>
        <div class="summary-card">
            <div class="value">{{ number_format($summary['total_late_minutes']) }}</div>
            <div class="label">Total Menit Terlambat</div>
        </div>
        <div class="summary-card">
            <div class="value">Rp {{ number_format($summary['total_penalty']) }}</div>
            <div class="label">Total Denda</div>
        </div>
    </div>

    <div class="penalty-breakdown">
        <h3>Rincian Denda</h3>
        <table>
            <thead>
                <tr>
                    <th>Jenis Denda</th>
                    <th>Jumlah</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Denda Keterlambatan</td>
                    <td class="amount">Rp {{ number_format($summary['total_late_fine']) }}</td>
                    <td>{{ $summary['late_days'] }} hari terlambat, rata-rata {{ number_format($summary['avg_late_minutes'], 1) }} menit/hari</td>
                </tr>
                <tr>
                    <td>Denda Istirahat</td>
                    <td class="amount">Rp {{ number_format($summary['total_break_fine']) }}</td>
                    <td>Pelanggaran waktu istirahat</td>
                </tr>
                <tr>
                    <td>Denda Absensi</td>
                    <td class="amount">Rp {{ number_format($summary['total_absence_fine']) }}</td>
                    <td>Lupa absen masuk/pulang</td>
                </tr>
                <tr style="background-color: #f8d7da; font-weight: bold;">
                    <td>TOTAL DENDA</td>
                    <td class="amount">Rp {{ number_format($summary['total_penalty']) }}</td>
                    <td>-</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="attendance-table">
        <h3>Detail Absensi Harian</h3>
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Scan Masuk</th>
                    <th>Scan Pulang</th>
                    <th>Terlambat (menit)</th>
                    <th>Denda Terlambat</th>
                    <th>Denda Istirahat</th>
                    <th>Denda Absensi</th>
                    <th>Total Denda</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($attendanceData as $attendance)
                    <tr class="{{ $attendance->total_fine > 0 ? 'late' : '' }}">
                        <td>{{ $attendance->tanggal->format('d/m/Y') }}</td>
                        <td>{{ $attendance->scan1 ? $attendance->scan1->format('H:i') : '-' }}</td>
                        <td>{{ $attendance->scan4 ? $attendance->scan4->format('H:i') : '-' }}</td>
                        <td class="{{ $attendance->late_minutes > 0 ? 'late' : '' }}">
                            {{ $attendance->late_minutes > 0 ? $attendance->late_minutes : '-' }}
                        </td>
                        <td class="{{ $attendance->late_fine > 0 ? 'penalty' : '' }}">
                            {{ $attendance->late_fine > 0 ? 'Rp ' . number_format($attendance->late_fine) : '-' }}
                        </td>
                        <td class="{{ $attendance->break_fine > 0 ? 'penalty' : '' }}">
                            {{ $attendance->break_fine > 0 ? 'Rp ' . number_format($attendance->break_fine) : '-' }}
                        </td>
                        <td class="{{ $attendance->absence_fine > 0 ? 'penalty' : '' }}">
                            {{ $attendance->absence_fine > 0 ? 'Rp ' . number_format($attendance->absence_fine) : '-' }}
                        </td>
                        <td class="{{ $attendance->total_fine > 0 ? 'penalty' : '' }}">
                            {{ $attendance->total_fine > 0 ? 'Rp ' . number_format($attendance->total_fine) : 'Rp 0' }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="signature">
        <div class="signature-box">
            <div>Surabaya, {{ now()->format('d F Y') }}</div>
            <div>HRD</div>
            <div class="signature-line"></div>
            <div>{{ Auth::user()->name ?? 'Admin' }}</div>
        </div>
    </div>

    <div class="footer">
        Dicetak pada: {{ now()->format('d F Y H:i:s') }}
    </div>
</body>
</html>
