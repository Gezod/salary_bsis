<div class="row g-4">
    <div class="col-12">
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>
            <strong>{{ $attendance->employee->nama }}</strong> - {{ $attendance->tanggal->format('d M Y') }}
        </div>
    </div>

    <div class="col-12">
        <h6 class="text-white mb-3">
            <i class="bi bi-clock me-2"></i>Waktu Scan
        </h6>
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label text-white small">Scan 1 (Masuk)</label>
                <input type="time" name="scan1" class="form-control"
                       value="{{ $attendance->scan1 ? $attendance->scan1->format('H:i') : '' }}">
            </div>
            <div class="col-md-3">
                <label class="form-label text-white small">Scan 2 (Istirahat/Pulang)</label>
                <input type="time" name="scan2" class="form-control"
                       value="{{ $attendance->scan2 ? $attendance->scan2->format('H:i') : '' }}">
            </div>
            <div class="col-md-3">
                <label class="form-label text-white small">Scan 3 (Kembali)</label>
                <input type="time" name="scan3" class="form-control"
                       value="{{ $attendance->scan3 ? $attendance->scan3->format('H:i') : '' }}">
            </div>
            <div class="col-md-3">
                <label class="form-label text-white small">Scan 4 (Pulang)</label>
                <input type="time" name="scan4" class="form-control"
                       value="{{ $attendance->scan4 ? $attendance->scan4->format('H:i') : '' }}">
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <h6 class="text-white mb-3">
            <i class="bi bi-info-circle me-2"></i>Status Absensi
        </h6>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="is_half_day" id="isHalfDay"
                   {{ $attendance->is_half_day ? 'checked' : '' }}>
            <label class="form-check-label text-white" for="isHalfDay">
                Setengah Hari
            </label>
        </div>

        <div id="halfDayOptions" style="{{ $attendance->is_half_day ? '' : 'display: none;' }}">
            <label class="form-label text-white small">Jenis Shift</label>
            <select name="half_day_type" class="form-control">
                <option value="">Pilih Shift</option>
                <option value="shift_1" {{ $attendance->half_day_type === 'shift_1' ? 'selected' : '' }}>
                    Shift 1 (Pagi)
                </option>
                <option value="shift_2" {{ $attendance->half_day_type === 'shift_2' ? 'selected' : '' }}>
                    Shift 2 (Siang)
                </option>
            </select>
        </div>
    </div>

    @if($attendance->overtime_minutes > 0)
    <div class="col-md-6">
        <h6 class="text-white mb-3">
            <i class="bi bi-clock-history me-2"></i>Status Lembur
        </h6>
        <label class="form-label text-white small">Status Approval</label>
        <select name="overtime_status" class="form-control">
            <option value="pending" {{ $attendance->overtime_status === 'pending' ? 'selected' : '' }}>
                Menunggu Approval
            </option>
            <option value="approved" {{ $attendance->overtime_status === 'approved' ? 'selected' : '' }}>
                Disetujui
            </option>
            <option value="rejected" {{ $attendance->overtime_status === 'rejected' ? 'selected' : '' }}>
                Ditolak
            </option>
        </select>
    </div>
    @endif

    <div class="col-12">
        <h6 class="text-white mb-3">
            <i class="bi bi-chat-text me-2"></i>Catatan
        </h6>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label text-white small">Catatan Absensi</label>
                <textarea name="half_day_notes" class="form-control" rows="3"
                          placeholder="Catatan untuk absensi ini...">{{ $attendance->half_day_notes }}</textarea>
            </div>
            @if($attendance->overtime_minutes > 0)
            <div class="col-md-6">
                <label class="form-label text-white small">Catatan Lembur</label>
                <textarea name="overtime_notes" class="form-control" rows="3"
                          placeholder="Catatan untuk lembur...">{{ $attendance->overtime_notes }}</textarea>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
document.getElementById('isHalfDay').addEventListener('change', function() {
    const halfDayOptions = document.getElementById('halfDayOptions');
    if (this.checked) {
        halfDayOptions.style.display = 'block';
    } else {
        halfDayOptions.style.display = 'none';
    }
});
</script>
