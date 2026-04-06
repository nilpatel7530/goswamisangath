@extends('layouts.admin')

@section('title', 'Payment Settings')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Razorpay Payment Settings</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.settings.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Settings
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.settings.payment.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="key_id">Razorpay Key ID <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('key_id') is-invalid @enderror" 
                                           id="key_id" 
                                           name="key_id" 
                                           value="{{ old('key_id', $settings->key_id ?? '') }}" 
                                           placeholder="rzp_test_xxxxxxxxxxxxx"
                                           required>
                                    @error('key_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Your Razorpay Key ID (starts with rzp_test_ for test mode or rzp_live_ for live mode)</small>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="key_secret">Razorpay Key Secret <span class="text-danger">*</span></label>
                                    <input type="password" 
                                           class="form-control @error('key_secret') is-invalid @enderror" 
                                           id="key_secret" 
                                           name="key_secret" 
                                           value="{{ old('key_secret', $settings->key_secret ?? '') }}" 
                                           placeholder="Enter your Razorpay Key Secret"
                                           required>
                                    @error('key_secret')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Your Razorpay Key Secret (keep this secure)</small>
                                    <button type="button" class="btn btn-sm btn-link mt-1" onclick="togglePasswordVisibility()">
                                        <i class="fas fa-eye" id="toggleIcon"></i> Show/Hide
                                    </button>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="mode">Mode <span class="text-danger">*</span></label>
                                    <select class="form-control @error('mode') is-invalid @enderror" id="mode" name="mode" required>
                                        <option value="test" {{ old('mode', $settings->mode ?? 'test') == 'test' ? 'selected' : '' }}>Test Mode</option>
                                        <option value="live" {{ old('mode', $settings->mode ?? 'test') == 'live' ? 'selected' : '' }}>Live Mode</option>
                                    </select>
                                    @error('mode')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Use Test Mode for testing, Live Mode for production</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Status</label>
                                    <div class="form-check mt-2">
                                        <input type="checkbox" 
                                               class="form-check-input" 
                                               id="is_active" 
                                               name="is_active" 
                                               value="1"
                                               {{ old('is_active', $settings->is_active ?? false) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active
                                        </label>
                                    </div>
                                    <small class="form-text text-muted">Enable or disable payment gateway</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Settings
                            </button>
                            <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>

                    @if($settings && $settings->key_id)
                        <div class="alert alert-info mt-4">
                            <h5><i class="fas fa-info-circle"></i> Current Configuration</h5>
                            <p class="mb-1"><strong>Key ID:</strong> {{ substr($settings->key_id, 0, 10) }}...{{ substr($settings->key_id, -5) }}</p>
                            <p class="mb-1"><strong>Mode:</strong> <span class="badge badge-{{ $settings->mode == 'live' ? 'danger' : 'warning' }}">{{ strtoupper($settings->mode) }}</span></p>
                            <p class="mb-0"><strong>Status:</strong> 
                                @if($settings->is_active)
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-secondary">Inactive</span>
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePasswordVisibility() {
    const passwordField = document.getElementById('key_secret');
    const toggleIcon = document.getElementById('toggleIcon');
    
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordField.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}
</script>
@endsection
