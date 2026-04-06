@extends('layouts.admin')

@section('title', 'Education Details Management')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
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
                    <h3 class="card-title">Education Details Management</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addEducationDetailsModal">
                            <i class="fas fa-plus"></i> Add Education Details
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
                        <h5 class="mb-3"><i class="fas fa-filter"></i> Filter by Highest Qualification</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="filter_highest_qualification">Select Highest Qualification:</label>
                                    <select class="form-control" id="filter_highest_qualification">
                                        <option value="">Show All Education Details</option>
                                        @foreach($highestQualifications as $qualification)
                                            <option value="{{ $qualification->id }}">{{ $qualification->name }}</option>
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
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="education-details-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Highest Qualification</th>
                                    <th>Status</th>
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

<!-- Add Education Details Modal -->
<div class="modal fade" id="addEducationDetailsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Education Details</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.settings.education-details.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="highest_education_id">Highest Education</label>
                        <select class="form-control" id="highest_education_id" name="highest_education_id" required>
                            <option value="">Select Highest Education</option>
                            @foreach($highestQualifications as $qualification)
                                <option value="{{ $qualification->id }}">{{ $qualification->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="education_details_id">Education Details</label>
                        <select class="form-control" id="education_details_id" name="education_details_id" disabled>
                            <option value="">Please select Highest Education first</option>
                        </select>
                        <small class="form-text text-muted">Select a Highest Education to see available Education Details</small>
                    </div>
                    <div class="form-group">
                        <label for="name">Education Details Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
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
                    <button type="submit" class="btn btn-primary">Add Education Details</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Education Details Modal -->
<div class="modal fade" id="editEducationDetailsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Education Details</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="editEducationDetailsForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_highest_education_id">Highest Education</label>
                        <select class="form-control" id="edit_highest_education_id" name="highest_education_id" required>
                            <option value="">Select Highest Education</option>
                            @foreach($highestQualifications as $qualification)
                                <option value="{{ $qualification->id }}">{{ $qualification->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_education_details_id">Education Details</label>
                        <select class="form-control" id="edit_education_details_id" name="education_details_id" disabled>
                            <option value="">Please select Highest Education first</option>
                        </select>
                        <small class="form-text text-muted">Select a Highest Education to see available Education Details</small>
                    </div>
                    <div class="form-group">
                        <label for="edit_name">Education Details Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_status">Status</label>
                        <select class="form-control" id="edit_status" name="status" required>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
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
                    <button type="submit" class="btn btn-primary">Update Education Details</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Education Details Modal -->
<div class="modal fade" id="deleteEducationDetailsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Education Details</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this education details? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteEducationDetailsForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    var table = $('#education-details-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('admin.settings.education-details') }}',
            data: function(d) {
                d.highest_qualification_filter = $('#filter_highest_qualification').val();
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'highest_qualification_name', name: 'highest_qualification_name' },
            {
                data: 'status',
                name: 'status',
                render: function(data, type, row) {
                    return '<span class="badge badge-' + (data ? 'success' : 'danger') + '">' + (data ? 'Active' : 'Inactive') + '</span>';
                }
            },
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
                    return '<button type="button" class="btn btn-sm btn-warning edit-btn" data-id="' + row.id + '" data-name="' + row.name + '" data-highest_qualification_id="' + row.highest_qualification_id + '" data-status="' + row.status + '" data-is_visible="' + row.is_visible + '"><i class="fas fa-edit"></i> Edit</button> ' +
                           '<button type="button" class="btn btn-sm btn-danger delete-btn" data-id="' + row.id + '"><i class="fas fa-trash"></i> Delete</button>';
                }
            }
        ]
    });

    // Filter functionality - Single filter for Highest Qualification
    $('#clear_filter').on('click', function() {
        $('#filter_highest_qualification').val('');
        table.ajax.reload();
    });

    // Auto-apply filter when dropdown changes
    $('#filter_highest_qualification').on('change', function() {
        var selectedText = $(this).find('option:selected').text();
        if ($(this).val()) {
            console.log('Filtering by: ' + selectedText);
        } else {
            console.log('Showing all education details');
        }
        table.ajax.reload();
    });

    // Dynamic dropdown functionality for Create modal
    $('#highest_education_id').on('change', function() {
        var qualificationId = $(this).val();
        var educationDetailsSelect = $('#education_details_id');
        
        // Clear and disable education details dropdown
        educationDetailsSelect.empty().prop('disabled', true);
        
        if (qualificationId) {
            // Show loading state
            educationDetailsSelect.append('<option value="">Loading...</option>');
            
            // Fetch education details via AJAX
            $.ajax({
                url: '{{ route("admin.settings.education-details.by-qualification", ":id") }}'.replace(':id', qualificationId),
                type: 'GET',
                success: function(response) {
                    educationDetailsSelect.empty();
                    
                    if (response.success && response.data.length > 0) {
                        educationDetailsSelect.append('<option value="">Select Education Details</option>');
                        $.each(response.data, function(index, item) {
                            educationDetailsSelect.append('<option value="' + item.id + '">' + item.name + '</option>');
                        });
                        educationDetailsSelect.prop('disabled', false);
                    } else {
                        educationDetailsSelect.append('<option value="">No education details found</option>');
                    }
                },
                error: function(xhr, status, error) {
                    educationDetailsSelect.empty();
                    educationDetailsSelect.append('<option value="">Error loading education details</option>');
                    console.error('Error:', error);
                }
            });
        } else {
            educationDetailsSelect.append('<option value="">Please select Highest Education first</option>');
        }
    });

    // Dynamic dropdown functionality for Edit modal
    $('#edit_highest_education_id').on('change', function() {
        var qualificationId = $(this).val();
        var educationDetailsSelect = $('#edit_education_details_id');
        
        // Clear and disable education details dropdown
        educationDetailsSelect.empty().prop('disabled', true);
        
        if (qualificationId) {
            // Show loading state
            educationDetailsSelect.append('<option value="">Loading...</option>');
            
            // Fetch education details via AJAX
            $.ajax({
                url: '{{ route("admin.settings.education-details.by-qualification", ":id") }}'.replace(':id', qualificationId),
                type: 'GET',
                success: function(response) {
                    educationDetailsSelect.empty();
                    
                    if (response.success && response.data.length > 0) {
                        educationDetailsSelect.append('<option value="">Select Education Details</option>');
                        $.each(response.data, function(index, item) {
                            educationDetailsSelect.append('<option value="' + item.id + '">' + item.name + '</option>');
                        });
                        educationDetailsSelect.prop('disabled', false);
                    } else {
                        educationDetailsSelect.append('<option value="">No education details found</option>');
                    }
                },
                error: function(xhr, status, error) {
                    educationDetailsSelect.empty();
                    educationDetailsSelect.append('<option value="">Error loading education details</option>');
                    console.error('Error:', error);
                }
            });
        } else {
            educationDetailsSelect.append('<option value="">Please select Highest Education first</option>');
        }
    });

    // Edit button click handler
    $('#education-details-table').on('click', '.edit-btn', function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        var highestQualificationId = $(this).data('highest_qualification_id');
        var status = $(this).data('status');
        var isVisible = $(this).data('is_visible');

        $('#edit_name').val(name);
        $('#edit_highest_education_id').val(String(highestQualificationId));
        $('#edit_status').val(String(status));
        $('#edit_is_visible').val(String(isVisible));
        
        // Trigger change event to load education details
        $('#edit_highest_education_id').trigger('change');
        
        $('#editEducationDetailsForm').attr('action', '/admin/settings/education-details/' + id);
        $('#editEducationDetailsModal').modal('show');
    });

    // Delete button click handler
    $('#education-details-table').on('click', '.delete-btn', function() {
        var id = $(this).data('id');
        $('#deleteEducationDetailsForm').attr('action', '/admin/settings/education-details/' + id);
        $('#deleteEducationDetailsModal').modal('show');
    });

    // Reset form when modal is closed
    $('#addEducationDetailsModal').on('hidden.bs.modal', function() {
        $('#addEducationDetailsModal form')[0].reset();
        $('#education_details_id').empty().prop('disabled', true).append('<option value="">Please select Highest Education first</option>');
    });

    $('#editEducationDetailsModal').on('hidden.bs.modal', function() {
        $('#editEducationDetailsModal form')[0].reset();
        $('#edit_education_details_id').empty().prop('disabled', true).append('<option value="">Please select Highest Education first</option>');
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

    // Form validation + AJAX submit for Create modal
    $('#addEducationDetailsModal form').on('submit', function(e) {
        e.preventDefault();
        var highestEducationId = $('#highest_education_id').val();
        var name = $('#name').val().trim();
        if (!highestEducationId) { alert('Please select a Highest Education.'); return false; }
        if (!name) { alert('Please enter Education Details Name.'); return false; }
        ajaxSubmit(this, '#addEducationDetailsModal');
    });

    // Form validation + AJAX submit for Edit modal
    $('#editEducationDetailsModal form').on('submit', function(e) {
        e.preventDefault();
        var highestEducationId = $('#edit_highest_education_id').val();
        var name = $('#edit_name').val().trim();
        if (!highestEducationId) { alert('Please select a Highest Education.'); return false; }
        if (!name) { alert('Please enter Education Details Name.'); return false; }
        ajaxSubmit(this, '#editEducationDetailsModal');
    });

    // Delete form AJAX
    $('#deleteEducationDetailsForm').on('submit', function(e) {
        e.preventDefault();
        ajaxSubmit(this, '#deleteEducationDetailsModal');
    });
});
</script>
@endpush
@endsection