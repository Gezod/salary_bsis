@if ($attendance->hasOvertime())
    <div>
        <span class="badge {{ $attendance->overtime_status_badge }}">
            {{ $attendance->overtime_text }}
        </span>
        @if ($attendance->overtime_status === 'pending')
            <div class="btn-group btn-group-sm mt-1" role="group">
                <button type="button" class="btn btn-success btn-sm"
                    onclick="updateOvertimeStatus({{ $attendance->id }}, 'approved', event)">
                    <i class="bi bi-check"></i>
                </button>
                <button type="button" class="btn btn-danger btn-sm"
                    onclick="updateOvertimeStatus({{ $attendance->id }}, 'rejected', event)">
                    <i class="bi bi-x"></i>
                </button>
            </div>
        @endif
    </div>
@else
    <span class="text-muted">-</span>
@endif
