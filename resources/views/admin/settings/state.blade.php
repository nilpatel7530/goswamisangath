@extends('layouts.admin')

@section('title', 'State Management')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <style>
    .filter-section {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    .filter-section .form-group {
        margin-bottom: 0.5rem;
    }
    .filter-buttons {
        margin-top: 1rem;
    }
    </style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">State Management</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addStateModal">
                            <i class="fas fa-plus"></i> Add State
                        </button>
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

                    <!-- Filter Section -->
                    <div class="filter-section">
                        <h5 class="mb-3"><i class="fas fa-filter"></i> Filter by Country</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="filter_country">Select Country:</label>
                                    <select class="form-control" id="filter_country">
                                        <option value="">Show All States</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div>
                                        <button type="button" class="btn btn-secondary" id="clear_filter">
                                            <i class="fas fa-times"></i> Clear Filter
                                        </button>
                                        <span id="filter_status" class="ml-2 text-muted"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="statesTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Country</th>
                                    <th>Visible</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add State Modal -->
<div class="modal fade" id="addStateModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New State</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.settings.state.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="country_id">Country</label>
                        <select class="form-control" id="country_id" name="country_id" required>
                            <option value="">Select Country</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="existing_state_id">Existing State (Optional)</label>
                        <select class="form-control" id="existing_state_id" name="existing_state_id" disabled>
                            <option value="">Please select a country first</option>
                        </select>
                        <small class="form-text text-muted">Select a country to see existing states, or create a new state below</small>
                    </div>
                    <div class="form-group">
                        <label for="name">State Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                        <small class="form-text text-muted">Enter a new state name</small>
                    </div>
                    <div class="form-group">
                        <label for="is_visible">Visibility</label>
                        <select class="form-control" id="is_visible" name="is_visible" required>
                            <option value="1">Visible</option>
                            <option value="0">Hidden</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add State</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit State Modal -->
<div class="modal fade" id="editStateModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit State</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="editStateForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_country_id">Country</label>
                        <select class="form-control" id="edit_country_id" name="country_id" required>
                            <option value="">Select Country</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_existing_state_id">Existing State (Optional)</label>
                        <select class="form-control" id="edit_existing_state_id" name="existing_state_id" disabled>
                            <option value="">Please select a country first</option>
                        </select>
                        <small class="form-text text-muted">Select a country to see existing states, or create a new state below</small>
                    </div>
                    <div class="form-group">
                        <label for="edit_name">State Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                        <small class="form-text text-muted">Enter a new state name</small>
                    </div>
                    <div class="form-group">
                        <label for="edit_is_visible">Visibility</label>
                        <select class="form-control" id="edit_is_visible" name="is_visible" required>
                            <option value="1">Visible</option>
                            <option value="0">Hidden</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update State</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete State Modal -->
<div class="modal fade" id="deleteStateModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete State</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this state? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteStateForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script>
    $(document).ready(function() {
        // Initialize DataTable with AJAX
        var table = $('#statesTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            language: {
                processing: "Loading...",
                emptyTable: "No states found",
                zeroRecords: "No matching states found"
            },
            ajax: {
                url: '{{ route('admin.settings.state') }}',
                type: 'GET',
                data: function(d) {
                    var countryFilter = $('#filter_country').val();
                    d.country_filter = countryFilter;
                    console.log('Sending filter data:', { country_filter: countryFilter });
                },
                error: function(xhr, error, thrown) {
                    console.error('DataTables AJAX Error:', error);
                    console.error('Response:', xhr.responseText);
                    console.error('Status:', xhr.status);
                    
                    // Show a user-friendly error message
                    $('#statesTable tbody').html('<tr><td colspan="5" class="text-center text-danger">Error loading data. Please refresh the page or contact administrator.</td></tr>');
                }
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'name', name: 'name' },
                { data: 'country_name', name: 'country_name' },
                {
                    data: 'is_visible',
                    name: 'is_visible',
                    render: function(data, type, row) {
                        return '<span class="badge badge-' + (data ? 'success' : 'warning') + '">' + (data ? 'Visible' : 'Hidden') + '</span>';
                    }
                },
                {
                    data: null,
                    name: 'actions',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return '<button type="button" class="btn btn-sm btn-warning edit-btn" data-id="' + row.id + '" data-name="' + row.name + '" data-country_id="' + row.country_id + '" data-is_visible="' + row.is_visible + '"><i class="fas fa-edit"></i> Edit</button> ' +
                               '<button type="button" class="btn btn-sm btn-danger delete-btn" data-id="' + row.id + '"><i class="fas fa-trash"></i> Delete</button>';
                    }
                }
            ]
        });

        // Update filter status display
        function updateFilterStatus() {
            var selectedValue = $('#filter_country').val();
            var selectedText = $('#filter_country option:selected').text();
            
            if (selectedValue) {
                $('#filter_status').html('<i class="fas fa-filter"></i> Filtered by: <strong>' + selectedText + '</strong>');
                $('#filter_status').removeClass('text-muted').addClass('text-info');
            } else {
                $('#filter_status').html('<i class="fas fa-globe"></i> Showing all states');
                $('#filter_status').removeClass('text-info').addClass('text-muted');
            }
        }

        // Filter functionality
        $('#clear_filter').on('click', function() {
            $('#filter_country').val('');
            console.log('Clear filter clicked - showing all states');
            updateFilterStatus();
            table.ajax.reload();
        });

        // Auto-apply filter when dropdown changes
        $('#filter_country').on('change', function() {
            var selectedValue = $(this).val();
            var selectedText = $(this).find('option:selected').text();
            
            if (selectedValue) {
                console.log('Filtering by: ' + selectedText + ' (ID: ' + selectedValue + ')');
            } else {
                console.log('Showing all states (no filter selected)');
            }
            updateFilterStatus();
            table.ajax.reload();
        });

        // Initialize filter status on page load
        updateFilterStatus();

        // Dynamic dropdown functionality for Create modal
        $('#country_id').on('change', function() {
            var countryId = $(this).val();
            var stateSelect = $('#existing_state_id');
            
            // Clear and disable state dropdown
            stateSelect.empty().prop('disabled', true);
            
            if (countryId) {
                // Show loading state
                stateSelect.append('<option value="">Loading...</option>');
                
                // Fetch states via AJAX
                $.ajax({
                    url: '{{ route("admin.settings.states.by-country", ":id") }}'.replace(':id', countryId),
                    type: 'GET',
                    success: function(response) {
                        stateSelect.empty();
                        
                        if (response.success && response.data.length > 0) {
                            stateSelect.append('<option value="">Select Existing State (Optional)</option>');
                            $.each(response.data, function(index, item) {
                                stateSelect.append('<option value="' + item.id + '">' + item.name + '</option>');
                            });
                            stateSelect.prop('disabled', false);
                        } else {
                            stateSelect.append('<option value="">No states found for this country</option>');
                        }
                    },
                    error: function(xhr, status, error) {
                        stateSelect.empty();
                        stateSelect.append('<option value="">Error loading states</option>');
                        console.error('Error:', error);
                    }
                });
            } else {
                stateSelect.append('<option value="">Please select a country first</option>');
            }
        });

        // Dynamic dropdown functionality for Edit modal
        $('#edit_country_id').on('change', function() {
            var countryId = $(this).val();
            var stateSelect = $('#edit_existing_state_id');
            
            // Clear and disable state dropdown
            stateSelect.empty().prop('disabled', true);
            
            if (countryId) {
                // Show loading state
                stateSelect.append('<option value="">Loading...</option>');
                
                // Fetch states via AJAX
                $.ajax({
                    url: '{{ route("admin.settings.states.by-country", ":id") }}'.replace(':id', countryId),
                    type: 'GET',
                    success: function(response) {
                        stateSelect.empty();
                        
                        if (response.success && response.data.length > 0) {
                            stateSelect.append('<option value="">Select Existing State (Optional)</option>');
                            $.each(response.data, function(index, item) {
                                stateSelect.append('<option value="' + item.id + '">' + item.name + '</option>');
                            });
                            stateSelect.prop('disabled', false);
                        } else {
                            stateSelect.append('<option value="">No states found for this country</option>');
                        }
                    },
                    error: function(xhr, status, error) {
                        stateSelect.empty();
                        stateSelect.append('<option value="">Error loading states</option>');
                        console.error('Error:', error);
                    }
                });
            } else {
                stateSelect.append('<option value="">Please select a country first</option>');
            }
        });

        // Edit button click handler
        $('#statesTable').on('click', '.edit-btn', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var countryId = $(this).data('country_id');
            var isVisible = $(this).data('is_visible');

            $('#edit_name').val(name);
            $('#edit_country_id').val(String(countryId));
            $('#edit_is_visible').val(String(isVisible));
            
            // Trigger change event to load states
            $('#edit_country_id').trigger('change');
            
            $('#editStateForm').attr('action', '/admin/settings/state/' + id);
            $('#editStateModal').modal('show');
        });

        // Delete button click handler
        $('#statesTable').on('click', '.delete-btn', function() {
            var id = $(this).data('id');
            $('#deleteStateForm').attr('action', '/admin/settings/state/' + id);
        $('#deleteStateModal').modal('show');
        });

        // Reset form when modal is closed
        $('#addStateModal').on('hidden.bs.modal', function() {
            $('#addStateModal form')[0].reset();
            $('#existing_state_id').empty().prop('disabled', true).append('<option value="">Please select a country first</option>');
        });

        $('#editStateModal').on('hidden.bs.modal', function() {
            $('#editStateModal form')[0].reset();
            $('#edit_existing_state_id').empty().prop('disabled', true).append('<option value="">Please select a country first</option>');
        });

        // AJAX form handler
        function ajaxSubmit(form, modal) {
            var $form = $(form);
            var url = $form.attr('action');
            $.ajax({
                url: url, type: 'POST', data: $form.serialize(),
                headers: {'X-Requested-With': 'XMLHttpRequest'},
                success: function(res) {
                    if (res.success) { $(modal).modal('hide'); $form[0].reset(); table.ajax.reload(); }
                },
                error: function(xhr) {
                    var errors = xhr.responseJSON?.errors;
                    if (errors) { alert(Object.values(errors).flat().join('\n')); }
                    else { alert(xhr.responseJSON?.message || 'An error occurred.'); }
                }
            });
        }

        $('#addStateModal form').on('submit', function(e) { e.preventDefault(); ajaxSubmit(this, '#addStateModal'); });
        $('#editStateForm').on('submit', function(e) { e.preventDefault(); ajaxSubmit(this, '#editStateModal'); });
        $('#deleteStateForm').on('submit', function(e) { e.preventDefault(); ajaxSubmit(this, '#deleteStateModal'); });
    });
    </script>
@endpush
