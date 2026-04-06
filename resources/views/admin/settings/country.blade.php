@extends('layouts.admin')

@section('title', 'Country Management')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Country Management</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addCountryModal">
                            <i class="fas fa-plus"></i> Add Country
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
                        <table class="table table-bordered table-striped" id="countriesTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Sort Name</th>
                                    <th>Phone Code</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($countries as $country)
                                <tr>
                                    <td>{{ $country->id }}</td>
                                    <td>{{ $country->name }}</td>
                                    <td>{{ $country->sortname }}</td>
                                    <td>{{ $country->phone_code }}</td>
                                    <td>
                                        <span class="badge badge-{{ $country->status ? 'success' : 'danger' }}">
                                            {{ $country->status ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>{{ isset($country->created_at) && $country->created_at ? \Carbon\Carbon::parse($country->created_at)->format('Y-m-d H:i:s') : 'N/A' }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-warning edit-btn" 
                                            data-id="{{ $country->id }}" 
                                            data-name="{{ $country->name }}" 
                                            data-sortname="{{ $country->sortname }}" 
                                            data-phone_code="{{ $country->phone_code }}" 
                                            data-status="{{ $country->status }}">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger delete-btn" 
                                            data-id="{{ $country->id }}">
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

<!-- Add Country Modal -->
<div class="modal fade" id="addCountryModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Country</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.settings.country.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Country Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="sortname">Sort Name</label>
                        <input type="text" class="form-control" id="sortname" name="sortname" maxlength="3" required>
                    </div>
                    <div class="form-group">
                        <label for="phone_code">Phone Code</label>
                        <input type="text" class="form-control" id="phone_code" name="phone_code" required>
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
                    <button type="submit" class="btn btn-primary">Add Country</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Country Modal -->
<div class="modal fade" id="editCountryModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Country</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="editCountryForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_name">Country Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_sortname">Sort Name</label>
                        <input type="text" class="form-control" id="edit_sortname" name="sortname" maxlength="3" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_phone_code">Phone Code</label>
                        <input type="text" class="form-control" id="edit_phone_code" name="phone_code" required>
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
                    <button type="submit" class="btn btn-primary">Update Country</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Country Modal -->
<div class="modal fade" id="deleteCountryModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Country</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this country? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteCountryForm" method="POST" style="display: inline;">
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
            $('#countriesTable').DataTable();

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

            $('#addCountryModal form').on('submit', function(e) { e.preventDefault(); ajaxSubmit(this, '#addCountryModal'); });
            $('#editCountryForm').on('submit', function(e) { e.preventDefault(); ajaxSubmit(this, '#editCountryModal'); });
            $('#deleteCountryForm').on('submit', function(e) { e.preventDefault(); ajaxSubmit(this, '#deleteCountryModal'); });

            // Edit button click handler
            $('#countriesTable').on('click', '.edit-btn', function() {
                var id = $(this).data('id');
                var name = $(this).data('name');
                var sortname = $(this).data('sortname');
                var phoneCode = $(this).data('phone_code');
                var status = $(this).data('status');

                $('#edit_name').val(name);
                $('#edit_sortname').val(sortname);
                $('#edit_phone_code').val(phoneCode);
                $('#edit_status').val(String(status));
                $('#editCountryForm').attr('action', '/admin/settings/country/' + id);
                $('#editCountryModal').modal('show');
            });

            // Delete button click handler
            $('#countriesTable').on('click', '.delete-btn', function() {
                var id = $(this).data('id');
                $('#deleteCountryForm').attr('action', '/admin/settings/country/' + id);
                $('#deleteCountryModal').modal('show');
            });
        });
    </script>
@endpush
