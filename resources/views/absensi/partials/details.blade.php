<div class="row">
    <div class="col-md-8">
        <h6 class="mb-3">Informasi Karyawan</h6>
        <table class="table table-sm">
            <tr>
                <td width="150"><strong>Nama:</strong></td>
                <td>{{ $attendance->employee->nama }}</td>
            </tr>
            <tr>
                <td><strong>Departemen:</strong></td>
                <td>
                    <span class="badge bg-{{ $attendance->employee->departemen === 'staff' ? 'primary' : 'success' }}">
                        {{ ucfirst($attendance->employee->departemen) }}
                    </span>
                </td>
            </tr>
            <tr>
                <td><strong>Tanggal:</strong></td>
                <td>{{ $attendance->tanggal->format('d F Y') }}</td>
            </tr>
            <tr>
                <td><strong>Hari:</strong></td>
                <td>
                    <span class="badge bg-info">{{ $attendance->tanggal->translatedFormat('l') }}</span>
                    @if($attendance->tanggal->format('l') === 'Friday')
                        <small class="text-muted d-block">Jam masuk: 07:00</small>
                    @else
                        <small class="text-muted d-block">Jam masuk: 07:30</small>
                    @endif
                </td>
            </tr>
            <tr>
                <td><strong>Status:</strong></td>
                <td>
                    <span class="badge {{ $attendance->status_badge }}">
                        {{ $attendance->status_text }}
                    </span>
                </td>
            </tr>
        </table>

        <h6 class="mb-3 mt-4">Detail Waktu</h6>
        <table class="table table-sm">
            <tr>
                <td width="150"><strong>Scan Masuk:</strong></td>
                <td>
                    {{ $attendance->getFormattedScan('scan1') ?: '-' }}
                    @if($attendance->late_minutes > 0)
                        <span class="badge bg-danger ms-2">Telat {{ $attendance->late_minutes }} menit</span>
                    @endif
                </td>
            </tr>
            <tr>
                <td><strong>Scan Istirahat:</strong></td>
                <td>{{ $attendance->getFormattedScan('scan2') ?: '-' }}</td>
            </tr>
            <tr>
                <td><strong>Scan Kembali:</strong></td>
                <td>{{ $attendance->getFormattedScan('scan3') ?: '-' }}</td>
            </tr>
            <tr>
                <td><strong>Scan Pulang:</strong></td>
                <td>{{ $attendance->getFormattedScan('scan4') ?: '-' }}</td>
            </tr>
            @if($attendance->getFormattedScan('scan5'))
            <tr>
                <td><strong>Scan 5:</strong></td>
                <td>{{ $attendance->getFormattedScan('scan5') }}</td>
            </tr>
            @endif
        </table>

        @if($attendance->hasOvertime())
        <h6 class="mb-3 mt-4">Informasi Lembur</h6>
        <table class="table table-sm">
            <tr>
                <td width="150"><strong>Durasi Lembur:</strong></td>
                <td>{{ $attendance->overtime_minutes }} menit</td>
            </tr>
            <tr>
                <td><strong>Status:</strong></td>
                <td>
                    <span class="badge {{ $attendance->overtime_status_badge }}">
                        {{ $attendance->formatted_overtime_status }}
                    </span>
                </td>
            </tr>
            @if($attendance->overtime_notes)
            <tr>
                <td><strong>Catatan:</strong></td>
                <td>{{ $attendance->overtime_notes }}</td>
            </tr>
            @endif
        </table>
        @endif
    </div>

    <div class="col-md-4">
        <h6 class="mb-3">Detail Denda & Perhitungan</h6>
        @if($attendance->is_half_day)
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Bebas Denda</strong><br>
                Status setengah hari tidak dikenakan denda
            </div>
        @else
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-8"><strong>Telat (menit):</strong></div>
                        <div class="col-4 text-end">
                            @if($attendance->late_minutes > 0)
                                <span class="text-danger fw-bold">{{ $attendance->late_minutes }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </div>
                    </div>

                    @if($attendance->late_minutes > 0 && $attendance->late_fine > 0)
                        @php
                            $lateBreakdown = $attendance->late_fine_breakdown;
                        @endphp
                        <div class="alert alert-warning p-2 mb-3">
                            <small><strong>Perhitungan Denda Telat:</strong></small><br>
                            <small><strong>Range:</strong> {{ $lateBreakdown['range_text'] }}</small><br>
                            <small><strong>Kalkulasi:</strong> {{ $lateBreakdown['calculation_text'] }}</small><br>

                            @if(isset($lateBreakdown['base_fine']))
                                <small>Base: Rp{{ number_format($lateBreakdown['base_fine'], 0, ',', '.') }}</small><br>
                                <small>Extra: {{ $lateBreakdown['extra_minutes'] }} menit × Rp200</small><br>
                                <small>Extra: Rp{{ number_format($lateBreakdown['extra_fine'], 0, ',', '.') }}</small><br>
                            @endif

                            <small><strong>Total: Rp{{ number_format($lateBreakdown['fine'], 0, ',', '.') }}</strong></small>
                        </div>
                    @endif

                    <div class="row mb-2">
                        <div class="col-8"><strong>Denda Telat:</strong></div>
                        <div class="col-4 text-end">
                            @if($attendance->late_fine > 0)
                                <span class="text-danger fw-bold">Rp {{ number_format($attendance->late_fine, 0, ',', '.') }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-8"><strong>Denda Istirahat:</strong></div>
                        <div class="col-4 text-end">
                            @if($attendance->break_fine > 0)
                                <span class="text-warning fw-bold">Rp {{ number_format($attendance->break_fine, 0, ',', '.') }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-8"><strong>Denda Absen:</strong></div>
                        <div class="col-4 text-end">
                            @if($attendance->absence_fine > 0)
                                <span class="text-info fw-bold">Rp {{ number_format($attendance->absence_fine, 0, ',', '.') }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-8"><strong>Total Denda:</strong></div>
                        <div class="col-4 text-end">
                            @if($attendance->total_fine > 0)
                                <span class="fw-bold text-danger fs-5">Rp {{ number_format($attendance->total_fine, 0, ',', '.') }}</span>
                            @else
                                <span class="fw-bold text-success fs-5">Rp 0</span>
                            @endif
                        </div>
                    </div>

                    @if($attendance->total_fine > 0)
                        <hr>
                        <div class="small text-muted">
                            <strong>Rincian:</strong>
                            @if($attendance->late_fine > 0)
                                <div>• Denda Telat: Rp {{ number_format($attendance->late_fine, 0, ',', '.') }}</div>
                            @endif
                            @if($attendance->break_fine > 0)
                                <div>• Denda Istirahat: Rp {{ number_format($attendance->break_fine, 0, ',', '.') }}</div>
                            @endif
                            @if($attendance->absence_fine > 0)
                                <div>• Denda Absen: Rp {{ number_format($attendance->absence_fine, 0, ',', '.') }}</div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
