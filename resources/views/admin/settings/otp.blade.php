@extends('layouts.admin')

@section('title', 'OTP Settings')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">OTP Settings</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.settings.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Settings
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div id="otpAlert" class="alert d-none"></div>
                    <input type="hidden" id="csrfTokenInput" name="_token" value="{{ csrf_token() }}">

                    <!-- OTP Method Switch (AJAX) -->
                    <div class="card card-outline card-primary mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">OTP Method (Site-wide)</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-3">Choose how OTP is sent for login/signup across the website.</p>
                            <div class="btn-group btn-group-toggle" data-toggle="buttons" id="otpMethodGroup">
                                <label class="btn btn-outline-primary {{ ($settings->otp_method ?? 'sms') == 'sms' ? 'active' : '' }}">
                                    <input type="radio" name="otp_method_radio" value="sms" {{ ($settings->otp_method ?? 'sms') == 'sms' ? 'checked' : '' }}> <i class="fas fa-sms"></i> SMS (Fast2SMS)
                                </label>
                                <label class="btn btn-outline-primary {{ ($settings->otp_method ?? 'sms') == 'email' ? 'active' : '' }}">
                                    <input type="radio" name="otp_method_radio" value="email" {{ ($settings->otp_method ?? 'sms') == 'email' ? 'checked' : '' }}> <i class="fas fa-envelope"></i> Email (uses .env MAIL_*)
                                </label>
                            </div>
                            <p class="mb-0 mt-2 small text-muted" id="otpMethodStatus">Current: <strong>{{ strtoupper($settings->otp_method ?? 'sms') }}</strong></p>
                            <p class="mt-2 small text-muted">When Email is selected, OTP is sent using Laravel's default mailer. Configure <code>MAIL_*</code> and <code>MAIL_FROM_ADDRESS</code> in your <code>.env</code> file.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const alertBox = document.getElementById('otpAlert');
    const methodStatus = document.getElementById('otpMethodStatus');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || document.getElementById('csrfTokenInput')?.value;

    function showAlert(message, type) {
        alertBox.className = 'alert alert-' + type + ' alert-dismissible fade show';
        alertBox.classList.remove('d-none');
        alertBox.innerHTML = message + ' <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>';
        setTimeout(function() { alertBox.classList.add('d-none'); }, 5000);
    }

    // OTP Method switch - AJAX
    document.querySelectorAll('input[name="otp_method_radio"]').forEach(function(radio) {
        radio.addEventListener('change', function() {
            const method = this.value;
            const label = this.closest('label');
            document.querySelectorAll('#otpMethodGroup label').forEach(function(l) { l.classList.remove('active'); });
            label.classList.add('active');

            if (!csrfToken) {
                showAlert('Session token missing. Please refresh the page and try again.', 'danger');
                return;
            }
            fetch('{{ route("admin.settings.otp.update-method") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({ otp_method: method })
            })
            .then(function(r) {
                if (r.status === 419) {
                    throw new Error('Session expired. Please refresh the page and try again.');
                }
                return r.json();
            })
            .then(function(data) {
                if (data.success) {
                    methodStatus.innerHTML = 'Current: <strong>' + method.toUpperCase() + '</strong>';
                    showAlert(data.message, 'success');
                } else {
                    showAlert(data.message || 'Failed to update.', 'danger');
                }
            })
            .catch(function(err) {
                showAlert(err.message || 'Network error. Please try again.', 'danger');
            });
        });
    });
});
</script>
@endsection
