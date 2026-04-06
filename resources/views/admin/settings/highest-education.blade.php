@extends('layouts.admin')

@section('title', 'Highest Education Management')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Highest Education Management</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addHighestEducationModal">
                            <i class="fas fa-plus"></i> Add Highest Education
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
                        <table class="table table-bordered table-striped" id="highest-education-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
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

<!-- Add Highest Education Modal -->
<div class="modal fade" id="addHighestEducationModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Highest Education</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.settings.highest-education.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Education Name</label>
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
                    <button type="submit" class="btn btn-primary">Add Education</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Highest Education Modal -->
<div class="modal fade" id="editHighestEducationModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Highest Education</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="editHighestEducationForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_name">Education Name</label>
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
                    <button type="submit" class="btn btn-primary">Update Education</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Highest Education Modal -->
<div class="modal fade" id="deleteHighestEducationModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Highest Education</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this highest education? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteHighestEducationForm" method="POST" style="display: inline;">
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
    var table = $('#highest-education-table').DataTable({
        processing: true, serverSide: true,
        ajax: '{{ route('admin.settings.highest-education') }}',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'status', name: 'status', render: function(data) { return '<span class="badge badge-' + (data ? 'success' : 'danger') + '">' + (data ? 'Active' : 'Inactive') + '</span>'; }},
            { data: 'is_visible', name: 'is_visible', render: function(data) { return '<span class="badge badge-' + (data ? 'success' : 'warning') + '">' + (data ? 'Visible' : 'Hidden') + '</span>'; }},
            { data: null, name: 'actions', orderable: false, searchable: false, render: function(data, type, row) {
                return '<button type="button" class="btn btn-sm btn-warning edit-btn" data-id="' + row.id + '" data-name="' + row.name + '" data-status="' + row.status + '" data-is_visible="' + row.is_visible + '"><i class="fas fa-edit"></i> Edit</button> ' +
                       '<button type="button" class="btn btn-sm btn-danger delete-btn" data-id="' + row.id + '"><i class="fas fa-trash"></i> Delete</button>';
            }}
        ]
    });

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

    $('#addHighestEducationModal form').on('submit', function(e) { e.preventDefault(); ajaxSubmit(this, '#addHighestEducationModal'); });
    $('#editHighestEducationForm').on('submit', function(e) { e.preventDefault(); ajaxSubmit(this, '#editHighestEducationModal'); });
    $('#deleteHighestEducationForm').on('submit', function(e) { e.preventDefault(); ajaxSubmit(this, '#deleteHighestEducationModal'); });

    $('#highest-education-table').on('click', '.edit-btn', function() {
        $('#edit_name').val($(this).data('name'));
        $('#edit_status').val(String($(this).data('status')));
        $('#edit_is_visible').val(String($(this).data('is_visible')));
        $('#editHighestEducationForm').attr('action', '/admin/settings/highest-education/' + $(this).data('id'));
        $('#editHighestEducationModal').modal('show');
    });

    $('#highest-education-table').on('click', '.delete-btn', function() {
        $('#deleteHighestEducationForm').attr('action', '/admin/settings/highest-education/' + $(this).data('id'));
        $('#deleteHighestEducationModal').modal('show');
    });
});
</script>
@endpush
@endsection
