<div class="row g-4">
    <div class="col-md-6">
        <h6 class="text-white mb-3">
            <i class="bi bi-person me-2"></i>Informasi Karyawan
        </h6>
        <table class="table table-dark table-sm">
            <tr>
                <td class="text-muted">Nama:</td>
                <td class="text-white">{{ $attendance->employee->nama }}</td>
            </tr>
            <tr>
                <td class="text-muted">NIP:</td>
                <td class="text-white">{{ $attendance->employee->nip }}</td>
            </tr>
            <tr>
                <td class="text-muted">Departemen:</td>
                <td><span class="badge bg-secondary">{{ ucfirst($attendance->employee->departemen) }}</span></td>
            </tr>
            <tr>
                <td class="text-muted">Jabatan:</td>
                <td class="text-white">{{ $attendance->employee->jabatan }}</td>
            </tr>
        </table>
    </div>

    <div class="col-md-6">
        <h6 class="text-white mb-3">
            <i class="bi bi-calendar me-2"></i>Informasi Absensi
        </h6>
        <table class="table table-dark table-sm">
            <tr>
                <td class="text-muted">Tanggal:</td>
                <td class="text-white">{{ $attendance->tanggal->format('d M Y') }}</td>
            </tr>
            <tr>
                <td class="text-muted">Status:</td>
                <td>
                    <span class="badge {{ $attendance->status_badge }}">
                        {{ $attendance->status_text }}
                    </span>
                </td>
            </tr>
            @if($attendance->is_half_day)
            <tr>
                <td class="text-muted">Jenis Shift:</td>
                <td class="text-white">{{ $attendance->half_day_type_text }}</td>
            </tr>
            @endif
        </table>
    </div>

    <div class="col-12">
        <h6 class="text-white mb-3">
            <i class="bi bi-clock me-2"></i>Waktu Scan
        </h6>
        <div class="row g-3">
            <div class="col-md-3">
                <div class="card bg-dark">
                    <div class="card-body text-center py-2">
                        <small class="text-muted">Masuk</small>
                        <div class="text-white fw-bold">{{ $attendance->getFormattedScan('scan1') ?: '-' }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-dark">
                    <div class="card-body text-center py-2">
                        <small class="text-muted">Istirahat Mulai</small>
                        <div class="text-white fw-bold">{{ $attendance->getFormattedScan('scan2') ?: '-' }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-dark">
                    <div class="card-body text-center py-2">
                        <small class="text-muted">Istirahat Selesai</small>
                        <div class="text-white fw-bold">{{ $attendance->getFormattedScan('scan3') ?: '-' }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-dark">
                    <div class="card-body text-center py-2">
                        <small class="text-muted">Pulang</small>
                        <div class="text-white fw-bold">{{ $attendance->getFormattedScan('scan4') ?: '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($attendance->overtime_minutes > 0)
    <div class="col-12">
        <h6 class="text-white mb-3">
            <i class="bi bi-clock-history me-2"></i>Informasi Lembur
        </h6>
        <div class="card bg-dark">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <small class="text-muted">Durasi Lembur:</small>
                        <div class="text-white fw-bold">{{ $attendance->overtime_text }}</div>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted">Status:</small>
                        <div>
                            <span class="badge {{ $attendance->overtime_status_badge }}">
                                {{ $attendance->formatted_overtime_status }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted">Catatan:</small>
                        <div class="text-white">{{ $attendance->overtime_notes ?: '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($attendance->total_fine > 0)
    <div class="col-12">
        <h6 class="text-white mb-3">
            <i class="bi bi-currency-dollar me-2"></i>Rincian Denda
        </h6>
        <div class="card bg-dark">
            <div class="card-body">
                <div class="row g-3">
                    @if($attendance->late_fine > 0)
                    <div class="col-md-4">
                        <small class="text-muted">Denda Terlambat:</small>
                        <div class="text-warning fw-bold">Rp {{ number_format($attendance->late_fine, 0, ',', '.') }}</div>
                        <small class="text-muted">({{ $attendance->late_minutes }} menit)</small>
                    </div>
                    @endif
                    @if($attendance->break_fine > 0)
                    <div class="col-md-4">
                        <small class="text-muted">Denda Istirahat:</small>
                        <div class="text-warning fw-bold">Rp {{ number_format($attendance->break_fine, 0, ',', '.') }}</div>
                    </div>
                    @endif
                    @if($attendance->absence_fine > 0)
                    <div class="col-md-4">
                        <small class="text-muted">Denda Absen:</small>
                        <div class="text-warning fw-bold">Rp {{ number_format($attendance->absence_fine, 0, ',', '.') }}</div>
                    </div>
                    @endif
                    <div class="col-12">
                        <hr class="border-secondary">
                        <div class="d-flex justify-content-between">
                            <strong class="text-white">Total Denda:</strong>
                            <strong class="text-warning">{{ $attendance->formatted_total_fine }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($attendance->half_day_notes)
    <div class="col-12">
        <h6 class="text-white mb-3">
            <i class="bi bi-chat-text me-2"></i>Catatan
        </h6>
        <div class="card bg-dark">
            <div class="card-body">
                <p class="text-white mb-0">{{ $attendance->half_day_notes }}</p>
            </div>
        </div>
    </div>
    @endif
</div>
