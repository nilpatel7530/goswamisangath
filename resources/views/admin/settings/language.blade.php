@extends('layouts.admin')

@section('title', 'Language Management')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="card-title">Language Management</h3>
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addLanguageModal">
                            <i class="fas fa-plus"></i> Add Language
                        </button>
                    </div>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <label for="entriesPerPage" class="mr-2">Show</label>
                            <select id="entriesPerPage" class="form-control d-inline-block w-auto mr-2">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            <span>entries</span>
                        </div>
                        <div class="d-flex align-items-center">
                            <label for="searchInput" class="mr-2">Search:</label>
                            <input type="text" id="searchInput" class="form-control d-inline-block w-auto" placeholder="Search languages...">
                        </div>
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
                        <table id="languageTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($languages as $language)
                                <tr>
                                    <td>{{ $language->id }}</td>
                                    <td>{{ $language->title }}</td>
                                    <td>
                                        <span class="badge badge-{{ $language->status ? 'success' : 'danger' }}">
                                            {{ $language->status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>{{ $language->created_at ? \Carbon\Carbon::parse($language->created_at)->format('Y-m-d H:i:s') : 'N/A' }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-warning edit-btn" 
                                            data-id="{{ $language->id }}" 
                                            data-title="{{ $language->title }}" 
                                            data-status="{{ $language->status }}">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger delete-btn" 
                                            data-id="{{ $language->id }}">
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

<!-- Add Language Modal -->
<div class="modal fade" id="addLanguageModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Language</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form action="{{ route('admin.settings.language.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="title">Language Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
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
                    <button type="submit" class="btn btn-primary">Add Language</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Language Modal -->
<div class="modal fade" id="editLanguageModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Language</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <form id="editLanguageForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_title">Language Title</label>
                        <input type="text" class="form-control" id="edit_title" name="title" required>
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
                    <button type="submit" class="btn btn-primary">Update Language</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Language Modal -->
<div class="modal fade" id="deleteLanguageModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Language</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this language? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteLanguageForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- jQuery DataTables -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script>
$(document).ready(function() {
    var table = $('#languageTable').DataTable({
        "pageLength": 10, "ordering": true, "searching": false,
        "lengthChange": false, "info": true, "paging": true
    });
    $('#searchInput').on('keyup', function () { table.search(this.value).draw(); });
    $('#entriesPerPage').on('change', function () { table.page.len(parseInt(this.value)).draw(); });

    // AJAX form handler
    function ajaxSubmit(form, modal) {
        var $form = $(form);
        var url = $form.attr('action');
        $.ajax({
            url: url, type: 'POST', data: $form.serialize(),
            headers: {'X-Requested-With': 'XMLHttpRequest'},
            success: function(res) {
                if (res.success) {
                    $(modal).modal('hide');
                    location.reload();
                }
            },
            error: function(xhr) {
                var errors = xhr.responseJSON?.errors;
                if (errors) { alert(Object.values(errors).flat().join('\n')); }
                else { alert(xhr.responseJSON?.message || 'An error occurred.'); }
            }
        });
    }

    // Add form
    $('#addLanguageModal form').on('submit', function(e) {
        e.preventDefault();
        ajaxSubmit(this, '#addLanguageModal');
    });
    // Edit form
    $('#editLanguageForm').on('submit', function(e) {
        e.preventDefault();
        ajaxSubmit(this, '#editLanguageModal');
    });
    // Delete form
    $('#deleteLanguageForm').on('submit', function(e) {
        e.preventDefault();
        ajaxSubmit(this, '#deleteLanguageModal');
    });

    // Edit button click handler
    $('#languageTable').on('click', '.edit-btn', function() {
        var id = $(this).data('id');
        var title = $(this).data('title');
        var status = $(this).data('status');

        $('#edit_title').val(title);
        $('#edit_status').val(String(status));
        $('#editLanguageForm').attr('action', '/admin/settings/language/' + id);
        $('#editLanguageModal').modal('show');
    });

    // Delete button click handler
    $('#languageTable').on('click', '.delete-btn', function() {
        var id = $(this).data('id');
        $('#deleteLanguageForm').attr('action', '/admin/settings/language/' + id);
        $('#deleteLanguageModal').modal('show');
    });
});
</script>
@endpush
@endsection
