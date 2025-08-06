<!DOCTYPE html>
<html>

<head>
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

        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }

        .header p {
            margin: 5px 0;
            color: #666;
        }

        .employee-info {
            margin-bottom: 30px;
            display: table;
            width: 100%;
        }

        .employee-data {
            display: table-cell;
            width: 60%;
            vertical-align: top;
        }

        .employee-summary {
            display: table-cell;
            width: 40%;
            text-align: center;
            vertical-align: top;
            border-left: 1px solid #ddd;
            padding-left: 20px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 5px 10px;
            border-bottom: 1px solid #eee;
        }

        .info-table td:first-child {
            font-weight: bold;
            width: 30%;
        }

        .summary-stats {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }

        .stat-item {
            display: table-cell;
            text-align: center;
            padding: 15px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }

        .stat-item h3 {
            margin: 0;
            font-size: 14px;
        }

        .stat-item small {
            color: #666;
        }

        .penalty-breakdown {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }

        .penalty-item {
            display: table-cell;
            text-align: center;
            padding: 15px;
            border: 1px solid #ddd;
        }

        .penalty-late {
            background-color: #ffebee;
        }

        .penalty-break {
            background-color: #fff8e1;
        }

        .penalty-absence {
            background-color: #e3f2fd;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            font-size: 10px;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .text-center {
            text-align: center;
        }

        .text-end {
            text-align: right;
        }

        .text-danger {
            color: #dc3545;
        }

        .text-warning {
            color: #ffc107;
        }

        .text-success {
            color: #28a745;
        }

        .text-info {
            color: #17a2b8;
        }

        .badge {
            padding: 2px 4px;
            border-radius: 3px;
            font-size: 9px;
            color: white;
        }

        .badge-primary {
            background-color: #007bff;
        }

        .badge-success {
            background-color: #28a745;
        }

        .badge-info {
            background-color: #17a2b8;
        }

        .badge-warning {
            background-color: #ffc107;
            color: #000;
        }

        .badge-danger {
            background-color: #dc3545;
        }

        .footer {
            margin-top: 10px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .total-fine {
            font-size: 18px;
            font-weight: bold;
            color: #dc3545;
        }

        .penalty-detail {
            font-size: 9px;
            color: #666;
        }
        .logo {
            width: 80px;
            height: auto;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="header">
        <img src="{{ public_path('images/Logo-Bank-Sampah-Surabaya-bank-sampah-induk-surabaya-v2 (1).png') }}" alt="Bank Sampah Logo" class="logo">
        <h1>LAPORAN DENDA INDIVIDUAL</h1>
        <p>{{ $employee->nama }}</p>
        <p>Periode: {{ $monthName }}</p>
        <p>Tanggal Cetak: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
    </div>

    {{-- Employee Information --}}
    <div class="employee-info">
        <div class="employee-data">
            <h3>Informasi Karyawan</h3>
            <table class="info-table">
                <tr>
                    <td>Nama</td>
                    <td>{{ $employee->nama }}</td>
                </tr>
                <tr>
                    <td>NIP</td>
                    <td>{{ $employee->nip }}</td>
                </tr>
                <tr>
                    <td>PIN</td>
                    <td>{{ $employee->pin }}</td>
                </tr>
                <tr>
                    <td>Departemen</td>
                    <td>
                        <span class="badge badge-{{ $employee->departemen === 'staff' ? 'primary' : 'success' }}">
                            {{ ucfirst($employee->departemen) }}
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>Jabatan</td>
                    <td>{{ $employee->jabatan }}</td>
                </tr>
                <tr>
                    <td>Kantor</td>
                    <td>{{ $employee->kantor }}</td>
                </tr>
            </table>
        </div>
        <div class="employee-summary">
            <h3>Total Denda</h3>
            <div class="total-fine">
                Rp {{ number_format($summary['total_penalty'], 0, ',', '.') }}
            </div>
            <small>Bulan {{ $monthName }}</small>
        </div>
    </div>

    {{-- Summary Statistics --}}
    <div class="summary-stats">
        <div class="stat-item">
            <h3 class="text-info">{{ $summary['total_days'] }}</h3>
            <small>Total Hari Kerja</small>
        </div>
        <div class="stat-item">
            <h3 class="text-warning">{{ $summary['late_days'] }}</h3>
            <small>Hari Terlambat</small>
        </div>
        <div class="stat-item">
            <h3 class="text-danger">{{ $summary['total_late_minutes'] }}</h3>
            <small>Total Menit Telat</small>
        </div>
        <div class="stat-item">
            <h3>{{ number_format($summary['avg_late_minutes'], 1) }}</h3>
            <small>Rata-rata Telat/Hari</small>
        </div>
    </div>

    {{-- Penalty Breakdown --}}
    <div class="penalty-breakdown">
        <div class="penalty-item penalty-late">
            <h3 class="text-danger">Rp {{ number_format($summary['total_late_fine'], 0, ',', '.') }}</h3>
            <small>Denda Keterlambatan</small>
        </div>
        <div class="penalty-item penalty-break">
            <h3 class="text-warning">Rp {{ number_format($summary['total_break_fine'], 0, ',', '.') }}</h3>
            <small>Denda Istirahat</small>
        </div>
        <div class="penalty-item penalty-absence">
            <h3 class="text-info">Rp {{ number_format($summary['total_absence_fine'], 0, ',', '.') }}</h3>
            <small>Denda Absensi</small>
        </div>
    </div>

    {{-- Detailed Attendance Table --}}
    <h3>Detail Absensi Harian</h3>
    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Hari</th>
                <th>Status</th>
                <th>Masuk</th>
                <th>Pulang</th>
                <th class="text-center">Telat (mnt)</th>
                <th class="text-end">Denda Telat</th>
                <th class="text-end">Denda Istirahat</th>
                <th class="text-end">Denda Absen</th>
                <th class="text-end">Total Denda</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($attendanceData as $attendance)
                <tr>
                    <td>{{ $attendance->tanggal->format('d/m/Y') }}</td>
                    <td>{{ $attendance->tanggal->translatedFormat('D') }}</td>
                    <td>
                        @if ($attendance->is_half_day)
                            <span class="badge badge-info">Setengah</span>
                        @elseif(!$attendance->scan1)
                            <span class="badge badge-danger">Tidak Hadir</span>
                        @elseif($attendance->late_minutes > 0)
                            <span class="badge badge-warning">Terlambat</span>
                        @else
                            <span class="badge badge-success">Tepat Waktu</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if ($attendance->scan1)
                            <span class="@if ($attendance->late_minutes > 0) text-danger @endif">
                                {{ $attendance->scan1->format('H:i') }}
                            </span>
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-center">
                        @if ($attendance->scan4)
                            {{ $attendance->scan4->format('H:i') }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-center">
                        @if ($attendance->late_minutes > 0)
                            <span class="@if ($attendance->late_minutes > 0) text-danger @endif">
                                {{ $attendance->late_minutes }}
                            </span>
                            <small class="d-block">
                                @
                                Rp{{ number_format($attendance->late_fine > 0 ? round($attendance->late_fine / $attendance->late_minutes) : 0, 0, ',', '.') }}/menit
                            </small>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td class="text-end">
                        @if ($attendance->late_fine > 0)
                            Rp {{ number_format($attendance->late_fine, 0, ',', '.') }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-end">
                        @if ($attendance->break_fine > 0)
                            Rp {{ number_format($attendance->break_fine, 0, ',', '.') }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-end">
                        @if ($attendance->absence_fine > 0)
                            Rp {{ number_format($attendance->absence_fine, 0, ',', '.') }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-end">
                        @if ($attendance->total_fine > 0)
                            <strong>Rp {{ number_format($attendance->total_fine, 0, ',', '.') }}</strong>
                        @else
                            -
                        @endif
                    </td>
                    <td class="penalty-detail">
                        @if ($attendance->is_half_day)
                            Bebas denda (setengah hari)
                        @else
                            @php $penalties = []; @endphp
                            @if ($attendance->late_minutes > 0)
                                @php $penalties[] = "Telat {$attendance->late_minutes} mnt"; @endphp
                            @endif
                            @if ($attendance->break_fine > 0)
                                @if (!$attendance->scan2 && !$attendance->scan3)
                                    @php $penalties[] = "Tidak absen istirahat 2x"; @endphp
                                @elseif(!$attendance->scan2 || !$attendance->scan3)
                                    @php $penalties[] = "Tidak absen istirahat 1x"; @endphp
                                @else
                                    @php $penalties[] = "Telat istirahat"; @endphp
                                @endif
                            @endif
                            @if ($attendance->absence_fine > 0)
                                @if (!$attendance->scan1 && !$attendance->scan4)
                                    @php $penalties[] = "Lupa absen masuk & pulang"; @endphp
                                @elseif(!$attendance->scan1)
                                    @php $penalties[] = "Lupa absen masuk"; @endphp
                                @elseif(!$attendance->scan4)
                                    @php $penalties[] = "Lupa absen pulang"; @endphp
                                @endif
                            @endif
                            {{ empty($penalties) ? 'Tidak ada pelanggaran' : implode(', ', $penalties) }}
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
        @if ($attendanceData->count() > 0)
            <tfoot>
                <tr style="background-color: #f2f2f2; font-weight: bold;">
                    <td colspan="6" class="text-center">TOTAL</td>
                    <td class="text-end">Rp {{ number_format($summary['total_late_fine'], 0, ',', '.') }}</td>
                    <td class="text-end">Rp {{ number_format($summary['total_break_fine'], 0, ',', '.') }}</td>
                    <td class="text-end">Rp {{ number_format($summary['total_absence_fine'], 0, ',', '.') }}</td>
                    <td class="text-end text-danger">Rp {{ number_format($summary['total_penalty'], 0, ',', '.') }}
                    </td>
                    <td></td>
                </tr>
            </tfoot>
        @endif
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y H:i') }} WIB</p>
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

    </div>
</body>

</html>
