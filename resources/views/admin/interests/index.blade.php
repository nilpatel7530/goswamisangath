@extends('layouts.admin')

@section('title', 'Interest History')
@section('content_header')
    <h1>Interest History</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Filter Card -->
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Filter Interests</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.interests.index') }}" method="GET" class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="sender">Sender Name</label>
                                <input type="text" name="sender" id="sender" class="form-control" placeholder="Search sender..." value="{{ request('sender') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="receiver">Receiver Name</label>
                                <input type="text" name="receiver" id="receiver" class="form-control" placeholder="Search receiver..." value="{{ request('receiver') }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="">All Statuses</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                                    <option value="declined" {{ request('status') == 'declined' ? 'selected' : '' }}>Declined</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <div class="form-group w-100">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-filter mr-1"></i> Filter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- List Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">All Interests</h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Sender</th>
                                <th>Receiver</th>
                                <th>Status</th>
                                <th>Sent Date</th>
                                <th>Last Updated</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($interests as $interest)
                                <tr>
                                    <td>{{ $interest->id }}</td>
                                    <td>
                                        <a href="{{ route('admin.users.show', encrypt($interest->sender_id)) }}">
                                            {{ $interest->sender_name }}
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.users.show', encrypt($interest->receiver_id)) }}">
                                            {{ $interest->receiver_name }}
                                        </a>
                                    </td>
                                    <td>
                                        @if($interest->status == 'accepted')
                                            <span class="badge badge-success">Accepted</span>
                                        @elseif($interest->status == 'pending')
                                            <span class="badge badge-warning">Pending</span>
                                        @else
                                            <span class="badge badge-danger">Declined</span>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($interest->created_at)->format('d M Y, h:i A') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($interest->updated_at)->format('d M Y, h:i A') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">No interest history found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer clearfix">
                    <div class="float-right">
                        {{ $interests->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
