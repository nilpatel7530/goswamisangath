@extends('layouts.admin')

@section('title', 'Report Details #' . $report->id)

@section('content')

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Report Details</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 200px;">Report ID</th>
                        <td>#{{ $report->id }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @if($report->status == 'pending')
                                <span class="badge badge-warning">Pending</span>
                            @elseif($report->status == 'reviewed')
                                <span class="badge badge-info">Reviewed</span>
                            @elseif($report->status == 'resolved')
                                <span class="badge badge-success">Resolved</span>
                            @elseif($report->status == 'dismissed')
                                <span class="badge badge-secondary">Dismissed</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Reason</th>
                        <td>
                            @php
                                $reasonLabels = [
                                    'spam_scam' => 'Spam/Scam',
                                    'harassment' => 'Harassment',
                                    'inappropriate_photos' => 'Inappropriate Photos',
                                    'underage' => 'Underage',
                                    'other' => 'Other'
                                ];
                            @endphp
                            <span class="badge badge-warning">{{ $reasonLabels[$report->reason] ?? ucfirst($report->reason) }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Details</th>
                        <td>{{ $report->details ?: 'No additional details provided.' }}</td>
                    </tr>
                    <tr>
                        <th>Block User Requested</th>
                        <td>
                            @if($report->block_user)
                                <span class="badge badge-danger">Yes</span>
                            @else
                                <span class="badge badge-secondary">No</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Reported On</th>
                        <td>{{ $report->created_at->format('d M, Y h:i A') }}</td>
                    </tr>
                    <tr>
                        <th>Last Updated</th>
                        <td>{{ $report->updated_at->format('d M, Y h:i A') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Reporter Information -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Reporter Information</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center">
                        <img src="{{ $report->reporter->profile_image ? asset('storage/' . $report->reporter->profile_image) : 'https://placehold.co/150x150/6c757d/ffffff?text=' . substr($report->reporter->full_name, 0, 1) }}" 
                             class="img-circle elevation-2" alt="User Image" style="width: 100px; height: 100px; object-fit: cover;">
                    </div>
                    <div class="col-md-9">
                        <h4>{{ $report->reporter->full_name }}</h4>
                        <p class="text-muted mb-1"><strong>Email:</strong> {{ $report->reporter->email }}</p>
                        <p class="text-muted mb-1"><strong>Mobile:</strong> {{ $report->reporter->mobile_number ?? 'N/A' }}</p>
                        <p class="text-muted mb-0"><strong>Member Since:</strong> {{ $report->reporter->created_at ? $report->reporter->created_at->format('d M, Y') : 'N/A' }}</p>
                        <a href="{{ route('admin.users.show', $report->reporter_id) }}" class="btn btn-sm btn-primary mt-2">
                            <i class="fas fa-user"></i> View Full Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reported User Information -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Reported User Information</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center">
                        <img src="{{ $report->reportedUser->profile_image ? asset('storage/' . $report->reportedUser->profile_image) : 'https://placehold.co/150x150/6c757d/ffffff?text=' . substr($report->reportedUser->full_name, 0, 1) }}" 
                             class="img-circle elevation-2" alt="User Image" style="width: 100px; height: 100px; object-fit: cover;">
                    </div>
                    <div class="col-md-9">
                        <h4>{{ $report->reportedUser->full_name }}</h4>
                        <p class="text-muted mb-1"><strong>Email:</strong> {{ $report->reportedUser->email }}</p>
                        <p class="text-muted mb-1"><strong>Mobile:</strong> {{ $report->reportedUser->mobile_number ?? 'N/A' }}</p>
                        <p class="text-muted mb-0"><strong>Member Since:</strong> {{ $report->reportedUser->created_at ? $report->reportedUser->created_at->format('d M, Y') : 'N/A' }}</p>
                        <a href="{{ route('admin.users.show', $report->reported_user_id) }}" class="btn btn-sm btn-primary mt-2">
                            <i class="fas fa-user"></i> View Full Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Update Status</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.reports.update-status', $report->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control" required>
                            <option value="pending" {{ $report->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="reviewed" {{ $report->status == 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                            <option value="resolved" {{ $report->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="dismissed" {{ $report->status == 'dismissed' ? 'selected' : '' }}>Dismissed</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-save"></i> Update Status
                    </button>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Quick Actions</h3>
            </div>
            <div class="card-body">
                <a href="{{ route('admin.users.edit', $report->reported_user_id) }}" class="btn btn-warning btn-block mb-2">
                    <i class="fas fa-edit"></i> Edit Reported User
                </a>
                <a href="{{ route('admin.users.show', $report->reported_user_id) }}" class="btn btn-info btn-block mb-2">
                    <i class="fas fa-eye"></i> View Reported User
                </a>
                <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary btn-block">
                    <i class="fas fa-arrow-left"></i> Back to Reports
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

