@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
{{-- Stat Cards --}}
<div class="row">
    <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $totalUsers }}</h3>
                <p>Total Users</p>
            </div>
            <div class="icon">
                <i class="fas fa-users"></i>
            </div>
            <a href="{{ route('admin.users.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
     <div class="col-lg-3 col-6">
        <!-- small box -->
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $newUsersToday }}</h3>
                <p>New Registrations Today</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-plus"></i>
            </div>
            <a href="{{ route('admin.users.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <!-- ./col -->
</div>

{{-- Charts --}}
<div class="row">
    <!-- Line Chart Card -->
    <div class="col-md-8">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Website Visits (Last 7 Days)</h3>
            </div>
            <div class="card-body">
                <div class="chart">
                    <canvas id="visitsChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>
    <!-- Pie Chart Card -->
    <div class="col-md-4">
        <div class="card card-danger">
            <div class="card-header">
                <h3 class="card-title">Membership Distribution</h3>
            </div>
            <div class="card-body">
                <canvas id="pieChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Push Chart.js script to the layout --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        //-------------
        //- VISITS CHART -
        //-------------
        var visitsChartCanvas = document.getElementById('visitsChart').getContext('2d');
        var visitsChartData = {
            labels: @json($visitLabels),
            datasets: [
                {
                    label: 'Daily Visits',
                    backgroundColor: 'rgba(60,141,188,0.2)',
                    borderColor: 'rgba(60,141,188,1)',
                    pointRadius: 5,
                    pointColor: '#3b8bba',
                    pointStrokeColor: 'rgba(60,141,188,1)',
                    pointHighlightFill: '#fff',
                    pointHighlightStroke: 'rgba(60,141,188,1)',
                    data: @json($visitCounts),
                    fill: true,
                    tension: 0.1
                }
            ]
        };
        new Chart(visitsChartCanvas, {
            type: 'line',
            data: visitsChartData,
            options: {
                maintainAspectRatio: false,
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });

        //-------------
        //- PIE CHART -
        //-------------
        var pieChartCanvas = document.getElementById('pieChart').getContext('2d');
        var pieData = {
            labels: @json($pieChartLabels),
            datasets: [{
                data: @json($pieChartData),
                backgroundColor: @json($pieChartColors),
            }]
        };
        new Chart(pieChartCanvas, {
            type: 'pie',
            data: pieData,
            options: {
                maintainAspectRatio: false,
                responsive: true,
            }
        });
    });
</script>
@endpush

