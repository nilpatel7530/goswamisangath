@extends('layouts.admin')

@section('title', 'Hobby Management')

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header border-0 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title font-weight-bold">Hobby Management</h3>
                        <button type="button" class="btn btn-primary btn-sm rounded-pill px-3" data-toggle="modal" data-target="#addHobbyModal">
                            <i class="fas fa-plus mr-1"></i> Add Hobby
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="hobbies-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Data populated via AJAX --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Hobby Modal -->
<div class="modal fade" id="addHobbyModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Hobby</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.settings.hobby.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Hobby Name</label>
                        <input type="text" class="form-control" id="name" name="name" required placeholder="Enter hobby name">
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Hobby</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Hobby Modal -->
<div class="modal fade" id="editHobbyModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Hobby</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="editHobbyForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_name">Hobby Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_status">Status</label>
                        <select class="form-control" id="edit_status" name="status" required>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Hobby</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Hobby Modal -->
<div class="modal fade" id="deleteHobbyModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">Delete Hobby</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this hobby? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteHobbyForm" method="POST" style="display: inline;">
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
    var table = $('#hobbies-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.settings.hobby') }}",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { 
                data: 'status', 
                name: 'status',
                render: function(data) {
                    return `<span class="badge badge-${data == 1 ? 'success' : 'danger'}">${data == 1 ? 'Active' : 'Inactive'}</span>`;
                }
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function(data) {
                    return `
                        <button type="button" class="btn btn-sm btn-outline-warning edit-btn rounded-pill px-3" 
                            data-id="${data.id}" 
                            data-name="${data.name}" 
                            data-status="${data.status}">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger delete-btn rounded-pill px-3" 
                            data-id="${data.id}">
                            <i class="fas fa-trash mr-1"></i> Delete
                        </button>
                    `;
                }
            }
        ],
        responsive: true,
        autoWidth: false
    });

    function ajaxSubmit(form, modalSelector) {
        var $form = $(form);
        var url = $form.attr('action');
        $.ajax({
            url: url,
            type: 'POST',
            data: $form.serialize(),
            headers: {'X-Requested-With': 'XMLHttpRequest'},
            success: function(res) {
                if (res.success) {
                    $(modalSelector).modal('hide');
                    table.ajax.reload(null, false);
                    toastr.success(res.message);
                }
            },
            error: function(xhr) {
                var errors = xhr.responseJSON?.errors;
                if (errors) {
                    alert(Object.values(errors).flat().join('\n'));
                } else {
                    alert(xhr.responseJSON?.message || 'An error occurred.');
                }
            }
        });
    }

    $('#addHobbyModal form').on('submit', function(e) { e.preventDefault(); ajaxSubmit(this, '#addHobbyModal'); });
    $('#editHobbyForm').on('submit', function(e) { e.preventDefault(); ajaxSubmit(this, '#editHobbyModal'); });
    $('#deleteHobbyForm').on('submit', function(e) { e.preventDefault(); ajaxSubmit(this, '#deleteHobbyModal'); });

    // Edit button click handler
    $('#hobbies-table').on('click', '.edit-btn', function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        var status = $(this).data('status');

        $('#edit_name').val(name);
        $('#edit_status').val(String(status));
        $('#editHobbyForm').attr('action', '/admin/settings/hobby/' + id);
        $('#editHobbyModal').modal('show');
    });

    // Delete button click handler
    $('#hobbies-table').on('click', '.delete-btn', function() {
        var id = $(this).data('id');
        $('#deleteHobbyForm').attr('action', '/admin/settings/hobby/' + id);
        $('#deleteHobbyModal').modal('show');
    });
});
</script>
@endpush
