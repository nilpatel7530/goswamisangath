@extends('layouts.admin')

@section('title', 'City Management')

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
                    <h3 class="card-title">City Management</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addCityModal">
                            <i class="fas fa-plus"></i> Add City
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
                        <h5 class="mb-3"><i class="fas fa-filter"></i> Filters</h5>
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="filter_country">Filter by Country:</label>
                                    <select class="form-control" id="filter_country">
                                        <option value="">Show All Countries</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="filter_state">Filter by State:</label>
                                    <select class="form-control" id="filter_state" disabled>
                                        <option value="">Select a Country first</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <div>
                                        <button type="button" class="btn btn-secondary w-100" id="clear_filter">
                                            <i class="fas fa-times"></i> Clear
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="citiesTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>City Name</th>
                                    <th>State</th>
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

<!-- Add City Modal -->
<div class="modal fade" id="addCityModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New City</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.settings.city.store') }}" method="POST">
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
                        <label for="state_id">State</label>
                        <select class="form-control" id="state_id" name="state_id" required disabled>
                            <option value="">Please select a country first</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="existing_city_id">Existing City (Optional)</label>
                        <select class="form-control" id="existing_city_id" name="existing_city_id" disabled>
                            <option value="">Please select a state first</option>
                        </select>
                        <small class="form-text text-muted">Select a state to see existing cities, or create a new city below</small>
                    </div>
                    <div class="form-group">
                        <label for="city_master">City Name</label>
                        <input type="text" class="form-control" id="city_master" name="city_master" required>
                        <small class="form-text text-muted">Enter a new city name</small>
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
                    <button type="submit" class="btn btn-primary">Add City</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit City Modal -->
<div class="modal fade" id="editCityModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit City</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="editCityForm" method="POST">
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
                        <label for="edit_state_id">State</label>
                        <select class="form-control" id="edit_state_id" name="state_id" required disabled>
                            <option value="">Please select a country first</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_existing_city_id">Existing City (Optional)</label>
                        <select class="form-control" id="edit_existing_city_id" name="existing_city_id" disabled>
                            <option value="">Please select a state first</option>
                        </select>
                        <small class="form-text text-muted">Select a state to see existing cities, or create a new city below</small>
                    </div>
                    <div class="form-group">
                        <label for="edit_city_master">City Name</label>
                        <input type="text" class="form-control" id="edit_city_master" name="city_master" required>
                        <small class="form-text text-muted">Enter a new city name</small>
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
                    <button type="submit" class="btn btn-primary">Update City</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete City Modal -->
<div class="modal fade" id="deleteCityModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete City</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this city? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form id="deleteCityForm" method="POST" style="display: inline;">
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
        var table = $('#citiesTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            language: {
                processing: "Loading...",
                emptyTable: "No cities found",
                zeroRecords: "No matching cities found"
            },
            ajax: {
                url: '{{ route('admin.settings.city') }}',
                type: 'GET',
                data: function(d) {
                    var stateFilter = $('#filter_state').val();
                    d.state_filter = stateFilter;
                    console.log('Sending filter data:', { state_filter: stateFilter });
                },
                error: function(xhr, error, thrown) {
                    console.error('DataTables AJAX Error:', error);
                    console.error('Response:', xhr.responseText);
                    console.error('Status:', xhr.status);
                    
                    // Show a user-friendly error message
                    $('#citiesTable tbody').html('<tr><td colspan="6" class="text-center text-danger">Error loading data. Please refresh the page or contact administrator.</td></tr>');
                }
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'city_master', name: 'city_master' },
                { data: 'state_name', name: 'state_name' },
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
                        return '<button type="button" class="btn btn-sm btn-warning edit-btn" data-id="' + row.id + '" data-city_master="' + row.city_master + '" data-state_id="' + row.state_id + '" data-country_id="' + row.country_id + '" data-is_visible="' + row.is_visible + '"><i class="fas fa-edit"></i> Edit</button> ' +
                               '<button type="button" class="btn btn-sm btn-danger delete-btn" data-id="' + row.id + '"><i class="fas fa-trash"></i> Delete</button>';
                    }
                }
            ]
        });

        // Shared function to load states by country
        function loadStates(countryId, stateSelectId, selectedStateId = null, callback = null) {
            var stateSelect = $(stateSelectId);
            stateSelect.empty().prop('disabled', true);

            if (countryId) {
                stateSelect.append('<option value="">Loading states...</option>');
                $.ajax({
                    url: '{{ route("admin.settings.states.by-country", ":id") }}'.replace(':id', countryId),
                    type: 'GET',
                    success: function(response) {
                        stateSelect.empty();
                        if (response.success && response.data.length > 0) {
                            stateSelect.append('<option value="">Select State</option>');
                            $.each(response.data, function(index, item) {
                                stateSelect.append('<option value="' + item.id + '">' + item.name + '</option>');
                            });
                            stateSelect.prop('disabled', false);
                            if (selectedStateId) {
                                stateSelect.val(String(selectedStateId));
                            }
                        } else {
                            stateSelect.append('<option value="">No states found</option>');
                        }
                        if (callback) callback();
                    },
                    error: function() {
                        stateSelect.empty().append('<option value="">Error loading states</option>');
                    }
                });
            } else {
                stateSelect.append('<option value="">Select a country first</option>');
            }
        }

        // Initialize DataTable with AJAX
        var table = $('#citiesTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                url: '{{ route('admin.settings.city') }}',
                type: 'GET',
                data: function(d) {
                    d.country_filter = $('#filter_country').val();
                    d.state_filter = $('#filter_state').val();
                }
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'city_master', name: 'city_master' },
                { data: 'state_name', name: 'state_name' },
                { data: 'country_name', name: 'country_name' },
                {
                    data: 'is_visible',
                    name: 'is_visible',
                    render: function(data) {
                        return '<span class="badge badge-' + (data ? 'success' : 'warning') + '">' + (data ? 'Visible' : 'Hidden') + '</span>';
                    }
                },
                {
                    data: null,
                    name: 'actions',
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return '<button type="button" class="btn btn-sm btn-warning edit-btn" data-id="' + row.id + '" data-city_master="' + row.city_master + '" data-state_id="' + row.state_id + '" data-country_id="' + row.country_id + '" data-is_visible="' + row.is_visible + '"><i class="fas fa-edit"></i> Edit</button> ' +
                               '<button type="button" class="btn btn-sm btn-danger delete-btn" data-id="' + row.id + '"><i class="fas fa-trash"></i> Delete</button>';
                    }
                }
            ]
        });

        // Filter functionality
        $('#filter_country').on('change', function() {
            var countryId = $(this).val();
            loadStates(countryId, '#filter_state', null, function() {
                table.ajax.reload();
            });
        });

        $('#filter_state').on('change', function() {
            table.ajax.reload();
        });

        $('#clear_filter').on('click', function() {
            $('#filter_country').val('');
            $('#filter_state').empty().prop('disabled', true).append('<option value="">Select a Country first</option>');
            table.ajax.reload();
        });

        // Modal dependent dropdowns
        $('#country_id').on('change', function() {
            loadStates($(this).val(), '#state_id');
        });

        $('#state_id').on('change', function() {
            var stateId = $(this).val();
            var citySelect = $('#existing_city_id');
            citySelect.empty().prop('disabled', true);
            if (stateId) {
                citySelect.append('<option value="">Loading...</option>');
                $.ajax({
                    url: '{{ route("admin.settings.cities.by-state", ":id") }}'.replace(':id', stateId),
                    type: 'GET',
                    success: function(response) {
                        citySelect.empty();
                        if (response.success && response.data.length > 0) {
                            citySelect.append('<option value="">Select Existing City (Optional)</option>');
                            $.each(response.data, function(index, item) {
                                citySelect.append('<option value="' + item.id + '">' + item.city_master + '</option>');
                            });
                            citySelect.prop('disabled', false);
                        } else {
                            citySelect.append('<option value="">No cities found</option>');
                        }
                    }
                });
            } else {
                citySelect.append('<option value="">Please select a state first</option>');
            }
        });

        $('#edit_country_id').on('change', function(e, selectedStateId) {
            loadStates($(this).val(), '#edit_state_id', selectedStateId);
        });

        $('#edit_state_id').on('change', function() {
            var stateId = $(this).val();
            var citySelect = $('#edit_existing_city_id');
            citySelect.empty().prop('disabled', true);
            if (stateId) {
                citySelect.append('<option value="">Loading...</option>');
                $.ajax({
                    url: '{{ route("admin.settings.cities.by-state", ":id") }}'.replace(':id', stateId),
                    type: 'GET',
                    success: function(response) {
                        citySelect.empty();
                        if (response.success && response.data.length > 0) {
                            citySelect.append('<option value="">Select Existing City (Optional)</option>');
                            $.each(response.data, function(index, item) {
                                citySelect.append('<option value="' + item.id + '">' + item.city_master + '</option>');
                            });
                            citySelect.prop('disabled', false);
                        } else {
                            citySelect.append('<option value="">No cities found</option>');
                        }
                    }
                });
            } else {
                citySelect.append('<option value="">Please select a state first</option>');
            }
        });

        // Edit button
        $('#citiesTable').on('click', '.edit-btn', function() {
            var id = $(this).data('id');
            var cityMaster = $(this).data('city_master');
            var countryId = $(this).data('country_id');
            var stateId = $(this).data('state_id');
            var isVisible = $(this).data('is_visible');

            $('#edit_city_master').val(cityMaster);
            $('#edit_is_visible').val(String(isVisible));
            $('#edit_country_id').val(String(countryId)).trigger('change', [stateId]);
            
            $('#editCityForm').attr('action', '/admin/settings/city/' + id);
            $('#editCityModal').modal('show');
        });

        // Delete button
        $('#citiesTable').on('click', '.delete-btn', function() {
            var id = $(this).data('id');
            $('#deleteCityForm').attr('action', '/admin/settings/city/' + id);
            $('#deleteCityModal').modal('show');
        });

        // Form handlers
        function ajaxSubmit(form, modal) {
            var $form = $(form);
            $.ajax({
                url: $form.attr('action'), type: 'POST', data: $form.serialize(),
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

        $('#addCityModal form').on('submit', function(e) { e.preventDefault(); ajaxSubmit(this, '#addCityModal'); });
        $('#editCityForm').on('submit', function(e) { e.preventDefault(); ajaxSubmit(this, '#editCityModal'); });
        $('#deleteCityForm').on('submit', function(e) { e.preventDefault(); ajaxSubmit(this, '#deleteCityModal'); });

        // Reset forms
        $('#addCityModal').on('hidden.bs.modal', function() {
            $(this).find('form')[0].reset();
            $('#state_id').empty().prop('disabled', true).append('<option value="">Please select a country first</option>');
            $('#existing_city_id').empty().prop('disabled', true).append('<option value="">Please select a state first</option>');
        });

        $('#editCityModal').on('hidden.bs.modal', function() {
            $(this).find('form')[0].reset();
            $('#edit_state_id').empty().prop('disabled', true).append('<option value="">Please select a country first</option>');
            $('#edit_existing_city_id').empty().prop('disabled', true).append('<option value="">Please select a state first</option>');
        });
    });
    </script>
@endpush
