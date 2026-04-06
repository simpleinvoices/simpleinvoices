@php
    $isSuccess = (bool) ($success ?? false);
    $alertClass = $isSuccess ? 'alert-success' : 'alert-warning';
    $title = $title ?? ($isSuccess ? ($LANG['save'] ?? 'Saved') : ($LANG['error'] ?? 'Something went wrong'));
    $message = $message ?? '';
    $redirectNote = $redirectNote ?? null;
@endphp

<div class="card si-save-card">
    <div class="card-body">
        <div class="alert {{ $alertClass }} alert-dismissible si-save-alert" role="alert">
            <div class="d-flex align-items-start gap-3">
                <span class="si-save-alert-icon" aria-hidden="true">
                    @if($isSuccess)
                        <svg class="icon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12l5 5l10 -10"></path>
                        </svg>
                    @else
                        <i class="ti ti-alert-triangle"></i>
                    @endif
                </span>
                <div class="flex-fill min-w-0">
                    <h3 class="alert-title mb-1">{{ $title }}</h3>
                    @if(!empty($message))
                        <div class="text-secondary">{!! $message !!}</div>
                    @endif
                    @if(!empty($redirectNote))
                        <div class="si-save-alert-note">{{ $redirectNote }}</div>
                    @endif
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ $LANG['close'] ?? 'Close' }}"></button>
            </div>
        </div>
    </div>
</div>
