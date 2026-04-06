@extends('layouts.admin')

@section('title', 'Manage Users')

@section('content')

{{-- Success and Error Messages --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-check"></i> Success!</h5>
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-ban"></i> Error!</h5>
        {{ session('error') }}
    </div>
@endif


<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Registered Users</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
        </div>
    </div>
    <!-- /.card-header -->
    <div class="card-body">
        <!-- Search and Filters -->
        <form method="GET" action="{{ route('admin.users.index') }}" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Search by name, email, mobile, ID, city..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Role</label>
                        <select name="role" class="form-control">
                            <option value="">All Roles</option>
                            <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Membership</label>
                        <select name="membership_status" class="form-control">
                            <option value="">All</option>
                            <option value="with_membership" {{ request('membership_status') == 'with_membership' ? 'selected' : '' }}>With Membership</option>
                            <option value="without_membership" {{ request('membership_status') == 'without_membership' ? 'selected' : '' }}>Without Membership</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Date From</label>
                        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Date To</label>
                        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Search & Filter
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                    <span class="ml-2 text-muted">
                        Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} users
                    </span>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>
                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'id', 'sort_order' => request('sort_by') == 'id' && request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" class="text-dark">
                                ID
                                @if(request('sort_by') == 'id')
                                    <i class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }}"></i>
                                @else
                                    <i class="fas fa-sort text-muted"></i>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'full_name', 'sort_order' => request('sort_by') == 'full_name' && request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" class="text-dark">
                                Name
                                @if(request('sort_by') == 'full_name')
                                    <i class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }}"></i>
                                @else
                                    <i class="fas fa-sort text-muted"></i>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'email', 'sort_order' => request('sort_by') == 'email' && request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" class="text-dark">
                                Email
                                @if(request('sort_by') == 'email')
                                    <i class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }}"></i>
                                @else
                                    <i class="fas fa-sort text-muted"></i>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'role', 'sort_order' => request('sort_by') == 'role' && request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" class="text-dark">
                                Role
                                @if(request('sort_by') == 'role')
                                    <i class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }}"></i>
                                @else
                                    <i class="fas fa-sort text-muted"></i>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'created_at', 'sort_order' => request('sort_by') == 'created_at' && request('sort_order') == 'asc' ? 'desc' : 'asc']) }}" class="text-dark">
                                Joined On
                                @if(request('sort_by') == 'created_at' || !request('sort_by'))
                                    <i class="fas fa-sort-{{ (request('sort_order') ?? 'desc') == 'asc' ? 'up' : 'down' }}"></i>
                                @else
                                    <i class="fas fa-sort text-muted"></i>
                                @endif
                            </a>
                        </th>
                        <th>Actions</th>
                    </tr>
                </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->full_name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->role == 'admin')
                                <span class="badge badge-success">Admin</span>
                            @else
                                <span class="badge badge-info">User</span>
                            @endif
                        </td>
                        <td>
                            @if($user->created_at)
                                {{ $user->created_at->format('d M, Y') }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-info">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <!-- /.card-body -->
    <div class="card-footer clearfix">
        {{ $users->links() }}
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    var searchTimeout;
    var form = $('form[method="GET"]');
    
    // Auto-submit on search input with debounce (wait 500ms after typing stops)
    $('input[name="search"]').on('input', function() {
        clearTimeout(searchTimeout);
        var $input = $(this);
        
        searchTimeout = setTimeout(function() {
            form.submit();
        }, 500); // Wait 500ms after user stops typing
    });
    
    // Auto-submit on filter changes (immediate)
    $('select[name="role"], select[name="membership_status"], input[name="date_from"], input[name="date_to"]').on('change', function() {
        form.submit();
    });
    
    // Prevent form submission on Enter key in search box (let debounce handle it)
    $('input[name="search"]').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            clearTimeout(searchTimeout);
            form.submit();
        }
    });
});
</script>
@endpush
@endsection

