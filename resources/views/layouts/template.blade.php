<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Grocerbee') }}</title>
    <link rel="icon" href="{{ asset('assets/dist/img/full_logo.png')}}" type="image/x-icon">

    {{-- datatables --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.css" />
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dist/css/flyinghub.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dist/css/style.css') }}">
    {{-- Sweetalert and toastr --}}
    <script src="{{ asset('assets/dist/js/sweetalert.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/dist/css/toastr.min.css') }}">
    <script src="{{ asset('assets/dist/js/toastr.min.js') }}"></script>
    {{-- semantic ui cdns --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fomantic-ui/2.8.7/semantic.min.css">
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        <i class="fas fa-user-circle"></i> {{Session::get('user')['name']}}
                    </a>
                    <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#view_profile_details_modal" type="button" id="profile" data-toggle="modal" data-target="#user_profile_details">
                            <i class="fas fa-user-alt"></i> {{ __('Profile') }}
                        </a>
                        <a class="dropdown-item" href="#" id="logout_btn">
                            <i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}
                        </a>
                    </div>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                        <i class="fas fa-th-large"></i>
                    </a>
                </li> -->
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4 bg-blue-light">
            <!-- Brand Logo -->
            <a href="/" class="brand-link">
                <img src="assets/dist/img/IMG_20210528_004813.jpg" alt="Grocerbee" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">{{ config('app.name', 'Grocerbee') }}</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <input type="hidden" value="{{ Session::get('user')['admin_type_id']}}" id="session_user_id">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item " id="dashboard_menu">
                            <a href="/" class="nav-link">
                                <i class="nav-icon fas fa-chart-bar"></i>
                                <p>
                                    Dashboard
                                </p>
                            </a>
                        </li>
                        <li class="nav-item " id="product_menu">
                            <a href="/product" class="nav-link">
                                <i class="nav-icon fas fa-box-open"></i>
                                <p>
                                    Products
                                </p>
                            </a>
                        </li>
                        <li class="nav-item" id="order_menu">
                            <a href="/order_details" class="nav-link">
                                <i class="nav-icon fas fa-cart-plus"></i>
                                <p>
                                    Orders
                                </p>
                            </a>
                        </li>
                        <li class="nav-item" id="report_menu">
                            <a href="/report" class="nav-link" id="report">
                                <i class="nav-icon fas fa-file-signature"></i>
                                <p>
                                    Reports
                                </p>
                            </a>
                        </li>
                        <li class="nav-item" id="users_menu">
                            <a href="/users" class="nav-link" id="users">
                                <i class="nav-icon fas fa-users"></i>
                                <p>
                                    Users
                                </p>
                            </a>
                        </li>
                        <li class="nav-item" id="settings_menu">
                            <a href="/setting" class="nav-link" id="users">
                                <i class="nav-icon fas fa-cog"></i>
                                <p>
                                    Settings
                                </p>
                            </a>
                        </li>
                        <li class="nav-item" id="settings_menu">
                            <a href="/analytics" class="nav-link" id="users">
                                <i class="nav-icon fas fa-chart-line"></i>
                                <p>
                                    Analytics
                                </p>
                            </a>
                        </li>
                        <li class="nav-item" id="settings_menu">
                            <a href="/sales" class="nav-link" id="users">
                                <i class="nav-icon fas fa-rupee-sign"></i>
                                <p>
                                    Sales
                                </p>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <div class="content-wrapper">
            {{-- Profile_moodal --}}
            <div class="modal fade" id="view_profile_details_modal" tabindex="-1">

                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">

                    <div class="modal-content  card">
                        <button type="button" class="btn btn-primary rounded-pill btn-sm view_product_modal_btn_close" data-dismiss="modal">
                            <span class="">&times;</span>
                        </button>
                        <div class="modal-body card-body">
                            <div class="row">
                                <div class="col-md-4 ">
                                    <img src="assets/dist/img/admin_profile_image.png" class="img-thumbnail mt-5 mb-2" height="100px" width="300px" alt="...">
                                    {{-- <div id="product_images_carousel" class="carousel slide mt-5 mb-2" data-ride="carousel">
                                        <ol class="carousel-indicators" id="product_images_carousel_indicators">

                                        </ol>
                                        <div class="carousel-inner" id="view_product_image">

                                        </div>
                                    </div> --}}
                                </div>
                                <div class=" col-md-8 ">
                                    <p class="border-bottom border-dark mt-3"><strong class="h3">Profile Details</strong></p>
                                    <table class="table table-sm table-borderless">
                                        <colgroup>
                                            <col style="width:100px;">
                                            <col style="width:10px;">
                                            <col>
                                        </colgroup>
                                        <tr>
                                            <td><strong>Name</strong></td>
                                            <td>:</td>
                                            <td><span id="profile_name"></span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Email</strong></td>
                                            <td>:</td>
                                            <td><span id="profile_email"></span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Mobile No.</strong></td>
                                            <td>:</td>
                                            <td><span id="profile_mobile_no"></span></td>
                                        </tr>
                                        <tr>
                                            <td><strong>User Type</strong></td>
                                            <td>:</td>
                                            <td><span id="profile_admin_type"></span></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            @yield('content')
        </div>

        <aside class="control-sidebar control-sidebar-dark">

        </aside>
        <footer class="main-footer">
            <strong>Copyright &copy; 2021 <a href="https:\\www.fourous.com"> Fourous Technologies </a>.</strong> All rights reserved.
            <div class="float-right d-none d-sm-inline-block">
                <b>Version</b> 1.0.0
            </div>
        </footer>
    </div>
    @include('sweetalert::alert')

    <script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.js"></script>
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/dist/js/flyinghub.js') }}"></script>
    <script src="{{ asset('assets/dist/js/app.js') }}"></script>
    {{-- semantic ui cdns --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fomantic-ui/2.8.7/semantic.min.js"></script>
    {{-- For highchart --}}
    <script src="{{ asset('assets/dist/js/highcharts.js') }}"></script>
    <script src="https://code.highcharts.com/modules/series-label.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/data.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>



    <script>
        $(document).ready(function() {
            template();
        });

    </script>
</body>

</html>
