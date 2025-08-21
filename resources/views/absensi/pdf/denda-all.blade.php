<!DOCTYPE html>
<html>

<head>
    <title>Laporan Denda Semua Karyawan</title>
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
            font-size: 16px;
            color: #333;
        }

        .stat-item small {
            color: #666;
        }

        .department-section {
            margin-bottom: 30px;
        }

        .department-header {
            background-color: #333;
            color: white;
            padding: 10px;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .department-stats {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }

        .dept-stat {
            display: table-cell;
            text-align: center;
            padding: 10px;
            border: 1px solid #ddd;
            background-color: #f5f5f5;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
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

        .text-primary {
            color: #007bff;
        }

        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            color: white;
        }

        .badge-primary {
            background-color: #007bff;
        }

        .badge-success {
            background-color: #28a745;
        }

        .footer {
            margin-top: 10px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
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
        <h1>REKAP DENDA</h1>
        <p>Periode: {{ $monthName }}</p>
        <p>Tanggal Cetak: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
    </div>

    {{-- Overall Summary --}}
    <div class="summary-stats">
        <div class="stat-item">
            <h3>{{ $penaltyData->count() }}</h3>
            <small>Total Karyawan Terkena Denda</small>
        </div>
        <div class="stat-item">
            <h3>Rp {{ number_format($penaltyData->sum('total_penalty'), 0, ',', '.') }}</h3>
            <small>Total Denda Keseluruhan</small>
        </div>
        <div class="stat-item">
            <h3>{{ $penaltyData->sum('late_days') }}</h3>
            <small>Total Hari Terlambat</small>
        </div>
        <div class="stat-item">
            <h3>Rp {{ number_format($penaltyData->avg('total_penalty'), 0, ',', '.') }}</h3>
            <small>Rata-rata Denda per Karyawan</small>
        </div>
    </div>

    {{-- Department Statistics --}}
    <div class="department-section">
        <h2>Statistik per Departemen</h2>

        {{-- Staff Statistics --}}
        <div class="department-header">STAFF</div>
        <div class="department-stats">
            <div class="dept-stat">
                <strong>{{ $departmentStats['staff']['total_employees'] }}</strong><br>
                <small>Total Karyawan</small>
            </div>
            <div class="dept-stat">
                <strong class="text-danger">Rp
                    {{ number_format($departmentStats['staff']['total_penalty'], 0, ',', '.') }}</strong><br>
                <small>Total Denda</small>
            </div>
            <div class="dept-stat">
                <strong class="text-warning">{{ $departmentStats['staff']['total_late_days'] }}</strong><br>
                <small>Total Hari Telat</small>
            </div>
            <div class="dept-stat">
                <strong class="text-primary">Rp
                    {{ number_format($departmentStats['staff']['avg_penalty'], 0, ',', '.') }}</strong><br>
                <small>Rata-rata Denda</small>
            </div>
        </div>

        {{-- Karyawan Statistics --}}
        <div class="department-header">KARYAWAN</div>
        <div class="department-stats">
            <div class="dept-stat">
                <strong>{{ $departmentStats['karyawan']['total_employees'] }}</strong><br>
                <small>Total Karyawan</small>
            </div>
            <div class="dept-stat">
                <strong class="text-danger">Rp
                    {{ number_format($departmentStats['karyawan']['total_penalty'], 0, ',', '.') }}</strong><br>
                <small>Total Denda</small>
            </div>
            <div class="dept-stat">
                <strong class="text-warning">{{ $departmentStats['karyawan']['total_late_days'] }}</strong><br>
                <small>Total Hari Telat</small>
            </div>
            <div class="dept-stat">
                <strong class="text-primary">Rp
                    {{ number_format($departmentStats['karyawan']['avg_penalty'], 0, ',', '.') }}</strong><br>
                <small>Rata-rata Denda</small>
            </div>
        </div>
    </div>

    {{-- Detailed Table --}}
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Karyawan</th>
                <th>Departemen</th>
                <th class="text-center">Total Hari</th>
                <th class="text-center">Hari Telat</th>
                <th class="text-center">Total Menit Telat</th>
                <th class="text-end">Denda Telat</th>
                <th class="text-end">Denda Istirahat</th>
                <th class="text-end">Denda Absen</th>
                <th class="text-end">Total Denda</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($penaltyData as $index => $data)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        {{ $data->employee->nama ?? '-' }}<br>
                        <small>ID: {{ $data->employee->id ?? '-' }}</small>
                    </td>
                    <td class="text-center">
                        <span
                            class="badge badge-{{ $data->employee->departemen === 'staff' ? 'primary' : 'success' }}">
                            {{ ucfirst($data->employee->departemen ?? '-') }}
                        </span>
                    </td>
                    <td class="text-center">{{ $data->total_days }}</td>
                    <td class="text-center">
                        @if ($data->late_days > 0)
                            {{ $data->late_days }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-center">
                        @if ($data->total_late_minutes > 0)
                            {{ $data->total_late_minutes }} menit
                            <small class="d-block">
                                @
                                Rp{{ number_format($data->total_late_fine > 0 ? round($data->total_late_fine / $data->total_late_minutes) : 0, 0, ',', '.') }}/menit
                            </small>
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-end">
                        @if ($data->total_late_fine > 0)
                            Rp {{ number_format($data->total_late_fine, 0, ',', '.') }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-end">
                        @if ($data->total_break_fine > 0)
                            Rp {{ number_format($data->total_break_fine, 0, ',', '.') }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-end">
                        @if ($data->total_absence_fine > 0)
                            Rp {{ number_format($data->total_absence_fine, 0, ',', '.') }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-end">
                        @if ($data->total_penalty > 0)
                            <strong>Rp {{ number_format($data->total_penalty, 0, ',', '.') }}</strong>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #f2f2f2; font-weight: bold;">
                <td colspan="6" class="text-center">TOTAL KESELURUHAN</td>
                <td class="text-end">Rp {{ number_format($penaltyData->sum('total_late_fine'), 0, ',', '.') }}</td>
                <td class="text-end">Rp {{ number_format($penaltyData->sum('total_break_fine'), 0, ',', '.') }}</td>
                <td class="text-end">Rp {{ number_format($penaltyData->sum('total_absence_fine'), 0, ',', '.') }}</td>
                <td class="text-end text-danger">Rp
                    {{ number_format($penaltyData->sum('total_penalty'), 0, ',', '.') }}</td>
            </tr>
        </tfoot>
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
