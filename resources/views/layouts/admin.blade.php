<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Admin GoswamiSangath</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <!-- Global Theme System -->
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
    <!-- Global Theme System -->
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    <style>
        /* Ensure sidebar is fixed and stays in place */
        .main-sidebar {
            position: fixed !important;
            top: 0;
            left: 0;
            height: 100vh;
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 1030;
        }
        
        /* Ensure sidebar stays fixed on scroll */
        .sidebar {
            height: calc(100vh - 57px);
            overflow-y: auto;
            overflow-x: hidden;
        }
        
        /* Ensure navbar stays fixed */
        .main-header {
            position: fixed !important;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1031;
        }
        
        /* Adjust content wrapper margin for fixed sidebar */
        .content-wrapper {
            margin-left: 250px;
        }
        
        /* Adjust footer margin for fixed sidebar */
        .main-footer {
            margin-left: 250px;
        }
        
        /* When sidebar is collapsed */
        body.sidebar-collapse .content-wrapper,
        body.sidebar-collapse .main-footer {
            margin-left: 78px;
        }
        
        /* Responsive adjustments */
        @media (max-width: 767.98px) {
            .main-sidebar {
                margin-left: -250px;
            }
            
            body.sidebar-open .main-sidebar {
                margin-left: 0;
            }
            
            .content-wrapper,
            .main-footer {
                margin-left: 0 !important;
            }
        }
    </style>
    @stack('styles')
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{ route('home') }}" class="nav-link">View Site</a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                 <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-link nav-link">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="{{ route('admin.dashboard') }}" class="brand-link relative" style="height: 57px; display: flex; items-center;">
            <div class="brand-logo-container relative h-full flex items-center" style="width: 250px;">
                @if(isset($siteSettings->site_logo) && $siteSettings->site_logo)
                    @if(isset($siteSettings->site_logo_dark) && $siteSettings->site_logo_dark)
                        <img src="{{ asset('storage/' . $siteSettings->site_logo) }}" alt="Logo" class="brand-image img-circle elevation-3 transition-opacity duration-500 ease-in-out dark:opacity-0 absolute left-4 !opacity-80">
                        <img src="{{ asset('storage/' . $siteSettings->site_logo_dark) }}" alt="Logo Dark" class="brand-image img-circle elevation-3 transition-opacity duration-500 ease-in-out opacity-0 dark:opacity-100 absolute left-4 !opacity-80">
                    @else
                        <img src="{{ asset('storage/' . $siteSettings->site_logo) }}" alt="Logo" class="brand-image img-circle elevation-3 !opacity-80">
                    @endif
                @endif
                
                @if(isset($siteSettings->site_name_type) && $siteSettings->site_name_type === 'image' && isset($siteSettings->site_name_image))
                    <div class="relative h-full flex items-center" style="margin-left: 3.5rem;">
                        @if(isset($siteSettings->site_name_image_dark) && $siteSettings->site_name_image_dark)
                            <img src="{{ asset('storage/' . $siteSettings->site_name_image) }}" alt="{{ $siteSettings->site_name ?? 'GoswamiSangath' }}" class="transition-opacity duration-500 ease-in-out dark:opacity-0 absolute !opacity-80" style="height: 33px; width: auto;">
                            <img src="{{ asset('storage/' . $siteSettings->site_name_image_dark) }}" alt="{{ $siteSettings->site_name ?? 'GoswamiSangath' }} Dark" class="transition-opacity duration-500 ease-in-out opacity-0 dark:opacity-100 absolute !opacity-80" style="height: 33px; width: auto;">
                        @else
                            <img src="{{ asset('storage/' . $siteSettings->site_name_image) }}" alt="{{ $siteSettings->site_name ?? 'GoswamiSangath' }}" class="!opacity-80" style="height: 33px; width: auto;">
                        @endif
                    </div>
                @else
                    <span class="brand-text font-weight-light transition-colors duration-500 ease-in-out" style="margin-left: 3.5rem;">{{ $siteSettings->site_name ?? 'GoswamiSangath' }} Admin</span>
                @endif
            </div>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="{{ Auth::user()->profile_image ? asset('storage/' . Auth::user()->profile_image) : 'https://placehold.co/150x150/6c757d/ffffff?text=U' }}" class="img-circle elevation-2" alt="User Image">
                </div>
                <div class="info">
                    <a href="#" class="d-block">{{ Auth::user()->full_name }}</a>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Users</p>
                        </a>
                    </li>
                    {{-- THIS IS THE NEW LINK --}}
                    <li class="nav-item">
                        <a href="{{ route('admin.memberships.index') }}" class="nav-link {{ request()->routeIs('admin.memberships.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-gem"></i>
                            <p>Memberships</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-flag"></i>
                            <p>Reports</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.interests.index') }}" class="nav-link {{ request()->routeIs('admin.interests.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-history"></i>
                            <p>Interest History</p>
                        </a>
                    </li>
                    
                    {{-- Settings Menu --}}
                    <li class="nav-item {{ request()->routeIs('admin.settings.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cogs"></i>
                            <p>
                                Settings
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.index') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>General Settings</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.settings.language') }}" class="nav-link {{ request()->routeIs('admin.settings.language*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Language</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admin.settings.highest-education') }}" class="nav-link {{ request()->routeIs('admin.settings.highest-education*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Highest Education</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.settings.education-details') }}" class="nav-link {{ request()->routeIs('admin.settings.education-details*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Education Details</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.settings.occupation') }}" class="nav-link {{ request()->routeIs('admin.settings.occupation*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Occupation</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.settings.country') }}" class="nav-link {{ request()->routeIs('admin.settings.country*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Country</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.settings.state') }}" class="nav-link {{ request()->routeIs('admin.settings.state*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>State</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.settings.city') }}" class="nav-link {{ request()->routeIs('admin.settings.city*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>City</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.settings.payment') }}" class="nav-link {{ request()->routeIs('admin.settings.payment*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Payment</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.settings.otp') }}" class="nav-link {{ request()->routeIs('admin.settings.otp*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>OTP</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.settings.hobby') }}" class="nav-link {{ request()->routeIs('admin.settings.hobby*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Hobby</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">@yield('title')</h1>
                    </div>
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                @yield('content')
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Main Footer -->
    <footer class="main-footer">
        <strong>Copyright &copy; {{ date('Y') }} <a href="{{ route('home') }}">{{ $siteSettings->site_name ?? 'GoswamiSangath' }}</a>.</strong> All rights reserved.
    </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.1/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/js/adminlte.min.js"></script>
<!-- Global Theme Manager -->
<script src="{{ asset('js/theme.js') }}"></script>
@stack('scripts')

<script>
    // Auto-hide success alerts after 5 seconds
    $(document).ready(function() {
        $('.alert-success').each(function() {
            var $alert = $(this);
            setTimeout(function() {
                $alert.fadeOut('slow', function() {
                    $(this).remove();
                });
            }, 5000); // 5 seconds
        });
    });
</script>
</body>
</html>

