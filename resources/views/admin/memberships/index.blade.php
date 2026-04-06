@extends('layouts.admin')

@section('title', 'Manage Memberships')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fas fa-check"></i> Success!</h5>
        {{ session('success') }}
    </div>
@endif

<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Membership Plans</h3>
        <div class="card-tools">
            <a href="{{ route('admin.memberships.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Plan
            </a>
        </div>
    </div>
    <div class="card-body">
        <!-- Search and Filters -->
        <form method="GET" action="{{ route('admin.memberships.index') }}" id="filterForm" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Search</label>
                        <input type="text" name="search" class="form-control" placeholder="Search plans..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Featured</label>
                        <select name="is_featured" class="form-control">
                            <option value="">All</option>
                            <option value="yes" {{ request('is_featured') == 'yes' ? 'selected' : '' }}>Yes</option>
                            <option value="no" {{ request('is_featured') == 'no' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Active Status</label>
                        <select name="is_active" class="form-control">
                            <option value="">All</option>
                            <option value="yes" {{ request('is_active') == 'yes' ? 'selected' : '' }}>Active</option>
                            <option value="no" {{ request('is_active') == 'no' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Min Price (₹)</label>
                        <input type="number" name="price_min" class="form-control" placeholder="Min" value="{{ request('price_min') }}" min="0" step="0.01">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label>Max Price (₹)</label>
                        <input type="number" name="price_max" class="form-control" placeholder="Max" value="{{ request('price_max') }}" min="0" step="0.01">
                    </div>
                </div>
            </div>
            <!-- Hidden fields for sorting -->
            <input type="hidden" name="sort_by" id="sort_by" value="{{ request('sort_by', 'display_order') }}">
            <input type="hidden" name="sort_order" id="sort_order" value="{{ request('sort_order', 'asc') }}">
        </form>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>
                        <a href="javascript:void(0)" class="sort-link text-dark" data-sort="id">
                            ID
                            @if(request('sort_by') == 'id')
                                <i class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }}"></i>
                            @else
                                <i class="fas fa-sort text-muted"></i>
                            @endif
                        </a>
                    </th>
                    <th>
                        <a href="javascript:void(0)" class="sort-link text-dark" data-sort="name">
                            Name
                            @if(request('sort_by') == 'name')
                                <i class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }}"></i>
                            @else
                                <i class="fas fa-sort text-muted"></i>
                            @endif
                        </a>
                    </th>
                    <th>
                        <a href="javascript:void(0)" class="sort-link text-dark" data-sort="price">
                            Price (₹)
                            @if(request('sort_by') == 'price')
                                <i class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }}"></i>
                            @else
                                <i class="fas fa-sort text-muted"></i>
                            @endif
                        </a>
                    </th>
                    <th>
                        <a href="javascript:void(0)" class="sort-link text-dark" data-sort="visits_allowed">
                            Visits
                            @if(request('sort_by') == 'visits_allowed')
                                <i class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }}"></i>
                            @else
                                <i class="fas fa-sort text-muted"></i>
                            @endif
                        </a>
                    </th>
                    <th>
                        <a href="javascript:void(0)" class="sort-link text-dark" data-sort="interest_limit">
                            Interests
                            @if(request('sort_by') == 'interest_limit')
                                <i class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }}"></i>
                            @else
                                <i class="fas fa-sort text-muted"></i>
                            @endif
                        </a>
                    </th>
                    <th>Badge</th>
                    <th>Featured</th>
                    <th>Active</th>
                    <th>
                        <a href="javascript:void(0)" class="sort-link text-dark" data-sort="display_order">
                            Order
                            @if(request('sort_by') == 'display_order' || !request('sort_by'))
                                <i class="fas fa-sort-{{ (request('sort_order', 'asc') == 'asc') ? 'up' : 'down' }}"></i>
                            @else
                                <i class="fas fa-sort text-muted"></i>
                            @endif
                        </a>
                    </th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($memberships as $membership)
                    <tr>
                        <td>{{ $membership->id }}</td>
                        <td>{{ $membership->name }}</td>
                        <td>₹{{ number_format($membership->price) }}</td>
                        <td>{{ $membership->visits_allowed }}</td>
                        <td>{{ $membership->interest_limit }}</td>
                        <td>{{ $membership->badge ?? '-' }}</td>
                        <td>
                            @if($membership->is_featured)
                                <span class="badge badge-success">Yes</span>
                            @else
                                <span class="badge badge-secondary">No</span>
                            @endif
                        </td>
                        <td>
                            @if($membership->is_active ?? true)
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-danger">Inactive</span>
                            @endif
                        </td>
                        <td>{{ $membership->display_order ?? 0 }}</td>
                        <td>
                            <a href="{{ route('admin.memberships.edit', $membership->id) }}" class="btn btn-sm btn-primary">Edit</a>
                            <form action="{{ route('admin.memberships.destroy', $membership->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this plan?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center">No membership plans found. Please add one.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Debounce function
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Auto-apply filters function
    function applyFilters() {
        $('#filterForm').submit();
    }

    // Debounced search input
    const debouncedSearch = debounce(applyFilters, 500);
    
    // Search input change
    $('input[name="search"]').on('input', function() {
        debouncedSearch();
    });

    // Filter dropdowns and inputs change
    $('select[name="is_featured"], select[name="is_active"], input[name="price_min"], input[name="price_max"]').on('change', function() {
        applyFilters();
    });

    // Price inputs - debounced
    const debouncedPriceFilter = debounce(applyFilters, 500);
    $('input[name="price_min"], input[name="price_max"]').on('input', function() {
        debouncedPriceFilter();
    });

    // Sorting functionality
    $('.sort-link').on('click', function() {
        const sortBy = $(this).data('sort');
        const currentSortBy = $('#sort_by').val();
        const currentSortOrder = $('#sort_order').val();
        
        if (sortBy === currentSortBy) {
            // Toggle sort order
            $('#sort_order').val(currentSortOrder === 'asc' ? 'desc' : 'asc');
        } else {
            // New column, default to ascending
            $('#sort_by').val(sortBy);
            $('#sort_order').val('asc');
        }
        
        applyFilters();
    });
});
</script>
@endpush

@endsection

