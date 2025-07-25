<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
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
        .department-stats {
            margin-bottom: 30px;
        }
        .dept-card {
            display: inline-block;
            width: 48%;
            background-color: #f8f9fa;
            padding: 15px;
            margin: 1%;
            border-radius: 5px;
            border: 1px solid #dee2e6;
            vertical-align: top;
        }
        .dept-title {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        .dept-stats {
            font-size: 11px;
        }
        .dept-stats .stat-row {
            margin-bottom: 5px;
        }
        .dept-stats .stat-label {
            display: inline-block;
            width: 120px;
        }
        .dept-stats .stat-value {
            font-weight: bold;
        }
        .summary-table {
            margin-bottom: 20px;
        }
        .summary-table table {
            width: 100%;
            border-collapse: collapse;
        }
        .summary-table th,
        .summary-table td {
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: left;
            font-size: 10px;
        }
        .summary-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .amount {
            text-align: right;
            font-weight: bold;
        }
        .penalty-high {
            background-color: #f8d7da;
            color: #721c24;
        }
        .penalty-medium {
            background-color: #fff3cd;
            color: #856404;
        }
        .dept-staff {
            background-color: #cce5ff;
        }
        .dept-karyawan {
            background-color: #ccffcc;
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
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">BANK SAMPAH SURABAYA</div>
        <div class="report-title">LAPORAN DENDA SEMUA KARYAWAN</div>
        <div class="report-period">Periode: {{ $monthName }}</div>
    </div>

    <div class="department-stats">
        <h3>Statistik Departemen</h3>
        <div class="dept-card">
            <div class="dept-title">Staff</div>
            <div class="dept-stats">
                <div class="stat-row">
                    <span class="stat-label">Karyawan Terdenda:</span>
                    <span class="stat-value">{{ $departmentStats['staff']['total_employees'] }} orang</span>
                </div>
                <div class="stat-row">
                    <span class="stat-label">Total Denda:</span>
                    <span class="stat-value">Rp {{ number_format($departmentStats['staff']['total_penalty']) }}</span>
                </div>
                <div class="stat-row">
                    <span class="stat-label">Rata-rata Denda:</span>
                    <span class="stat-value">Rp {{ number_format($departmentStats['staff']['avg_penalty']) }}</span>
                </div>
                <div class="stat-row">
                    <span class="stat-label">Total Hari Terlambat:</span>
                    <span class="stat-value">{{ $departmentStats['staff']['total_late_days'] }} hari</span>
                </div>
            </div>
        </div>
        <div class="dept-card">
            <div class="dept-title">Karyawan</div>
            <div class="dept-stats">
                <div class="stat-row">
                    <span class="stat-label">Karyawan Terdenda:</span>
                    <span class="stat-value">{{ $departmentStats['karyawan']['total_employees'] }} orang</span>
                </div>
                <div class="stat-row">
                    <span class="stat-label">Total Denda:</span>
                    <span class="stat-value">Rp {{ number_format($departmentStats['karyawan']['total_penalty']) }}</span>
                </div>
                <div class="stat-row">
                    <span class="stat-label">Rata-rata Denda:</span>
                    <span class="stat-value">Rp {{ number_format($departmentStats['karyawan']['avg_penalty']) }}</span>
                </div>
                <div class="stat-row">
                    <span class="stat-label">Total Hari Terlambat:</span>
                    <span class="stat-value">{{ $departmentStats['karyawan']['total_late_days'] }} hari</span>
                </div>
            </div>
        </div>
    </div>

    <div class="summary-table">
        <h3>Rincian Denda Per Karyawan</h3>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Karyawan</th>
                    <th>NIP</th>
                    <th>Departemen</th>
                    <th>Total Hari</th>
                    <th>Hari Terlambat</th>
                    <th>Total Menit Terlambat</th>
                    <th>Denda Terlambat</th>
                    <th>Denda Istirahat</th>
                    <th>Denda Absensi</th>
                    <th>Total Denda</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($penaltyData as $index => $data)
                    <tr class="{{ $data->employee->departemen === 'staff' ? 'dept-staff' : 'dept-karyawan' }}">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $data->employee->nama }}</td>
                        <td>{{ $data->employee->nip }}</td>
                        <td>{{ ucfirst($data->employee->departemen) }}</td>
                        <td>{{ $data->total_days }}</td>
                        <td>{{ $data->late_days }}</td>
                        <td>{{ number_format($data->total_late_minutes) }}</td>
                        <td class="amount">Rp {{ number_format($data->total_late_fine) }}</td>
                        <td class="amount">Rp {{ number_format($data->total_break_fine) }}</td>
                        <td class="amount">Rp {{ number_format($data->total_absence_fine) }}</td>
                        <td class="amount {{ $data->total_penalty > 50000 ? 'penalty-high' : ($data->total_penalty > 20000 ? 'penalty-medium' : '') }}">
                            Rp {{ number_format($data->total_penalty) }}
                        </td>
                    </tr>
                @endforeach
                <tr style="background-color: #f8f9fa; font-weight: bold;">
                    <td colspan="7">TOTAL KESELURUHAN</td>
                    <td class="amount">Rp {{ number_format($penaltyData->sum('total_late_fine')) }}</td>
                    <td class="amount">Rp {{ number_format($penaltyData->sum('total_break_fine')) }}</td>
                    <td class="amount">Rp {{ number_format($penaltyData->sum('total_absence_fine')) }}</td>
                    <td class="amount">Rp {{ number_format($penaltyData->sum('total_penalty')) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="page-break">
        <h3>Analisis dan Rekomendasi</h3>
        <div style="background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            <h4>Ringkasan Periode {{ $monthName }}</h4>
            <ul style="font-size: 11px; line-height: 1.5;">
                <li><strong>Total Karyawan Terdenda:</strong> {{ $penaltyData->count() }} orang</li>
                <li><strong>Total Denda Keseluruhan:</strong> Rp {{ number_format($penaltyData->sum('total_penalty')) }}</li>
                <li><strong>Rata-rata Denda per Karyawan:</strong> Rp {{ number_format($penaltyData->avg('total_penalty')) }}</li>
                <li><strong>Total Hari Terlambat:</strong> {{ $penaltyData->sum('late_days') }} hari</li>
                <li><strong>Total Menit Keterlambatan:</strong> {{ number_format($penaltyData->sum('total_late_minutes')) }} menit</li>
            </ul>
        </div>

        <div style="background-color: #fff3cd; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
            <h4>Karyawan dengan Denda Tertinggi</h4>
            <ol style="font-size: 11px; line-height: 1.5;">
                @foreach ($penaltyData->take(5) as $data)
                    <li>{{ $data->employee->nama }} ({{ ucfirst($data->employee->departemen) }}) - Rp {{ number_format($data->total_penalty) }}</li>
                @endforeach
            </ol>
        </div>

        <div style="background-color: #f8d7da; padding: 15px; border-radius: 5px;">
            <h4>Rekomendasi</h4>
            <ul style="font-size: 11px; line-height: 1.5;">
                <li>Lakukan pembinaan khusus untuk karyawan dengan denda tinggi</li>
                <li>Evaluasi sistem absensi dan sosialisasi ulang aturan kehadiran</li>
                <li>Pertimbangkan program reward untuk karyawan dengan kehadiran terbaik</li>
                <li>Monitor trend keterlambatan untuk identifikasi pola masalah</li>
            </ul>
        </div>
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
