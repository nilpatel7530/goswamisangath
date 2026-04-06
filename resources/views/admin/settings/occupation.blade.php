@extends('layouts.admin')

@section('title', 'Occupation Management')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Occupation Management</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addOccupationModal">
                            <i class="fas fa-plus"></i> Add Occupation
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

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="occupations-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Visible</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($occupations as $occupation)
                                <tr>
                                    <td>{{ $occupation->id }}</td>
                                    <td>{{ $occupation->name }}</td>
                                    <td>
                                        <span class="badge badge-{{ $occupation->status ? 'success' : 'danger' }}">
                                            {{ $occupation->status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $occupation->is_visible ? 'success' : 'warning' }}">
                                            {{ $occupation->is_visible ? 'Visible' : 'Hidden' }}
                                        </span>
                                    </td>
                                    <td>{{ isset($occupation->created_at) && $occupation->created_at ? \Carbon\Carbon::parse($occupation->created_at)->format('Y-m-d H:i:s') : 'N/A' }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-warning edit-btn" 
                                            data-id="{{ $occupation->id }}" 
                                            data-name="{{ $occupation->name }}" 
                                            data-status="{{ $occupation->status }}" 
                                            data-is_visible="{{ $occupation->is_visible }}">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger delete-btn" 
                                            data-id="{{ $occupation->id }}">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Occupation Modal -->
<div class="modal fade" id="addOccupationModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Occupation</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.settings.occupation.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Occupation Name</label>
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
                    <button type="submit" class="btn btn-primary">Add Occupation</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Occupation Modal -->
<div class="modal fade" id="editOccupationModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Occupation</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="editOccupationForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_name">Occupation Name</label>
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
                    <button type="submit" class="btn btn-primary">Update Occupation</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Occupation Modal -->
<div class="modal fade" id="deleteOccupationModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Occupation</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this occupation? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteOccupationForm" method="POST" style="display: inline;">
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
    $('#occupations-table').DataTable({
        "paging": true, "lengthChange": true, "searching": true,
        "ordering": true, "info": true, "autoWidth": false, "responsive": true
    });

    function ajaxSubmit(form, modal) {
        var $form = $(form);
        var url = $form.attr('action');
        $.ajax({
            url: url, type: 'POST', data: $form.serialize(),
            headers: {'X-Requested-With': 'XMLHttpRequest'},
            success: function(res) {
                if (res.success) { $(modal).modal('hide'); location.reload(); }
            },
            error: function(xhr) {
                var errors = xhr.responseJSON?.errors;
                if (errors) { alert(Object.values(errors).flat().join('\n')); }
                else { alert(xhr.responseJSON?.message || 'An error occurred.'); }
            }
        });
    }

    $('#addOccupationModal form').on('submit', function(e) { e.preventDefault(); ajaxSubmit(this, '#addOccupationModal'); });
    $('#editOccupationForm').on('submit', function(e) { e.preventDefault(); ajaxSubmit(this, '#editOccupationModal'); });
    $('#deleteOccupationForm').on('submit', function(e) { e.preventDefault(); ajaxSubmit(this, '#deleteOccupationModal'); });

    // Edit button click handler
    $('#occupations-table').on('click', '.edit-btn', function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        var status = $(this).data('status');
        var isVisible = $(this).data('is_visible');

        $('#edit_name').val(name);
        $('#edit_status').val(String(status));
        $('#edit_is_visible').val(String(isVisible));
        $('#editOccupationForm').attr('action', '/admin/settings/occupation/' + id);
        $('#editOccupationModal').modal('show');
    });

    // Delete button click handler
    $('#occupations-table').on('click', '.delete-btn', function() {
        var id = $(this).data('id');
        $('#deleteOccupationForm').attr('action', '/admin/settings/occupation/' + id);
        $('#deleteOccupationModal').modal('show');
    });
});
</script>
@endpush
