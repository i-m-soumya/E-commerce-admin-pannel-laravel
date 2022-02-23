@extends('layouts.template')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
            </div>
        </div>
    </div>
</div>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-chart-line"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Total yearly sales</span>
                        <span class="info-box-number" id="total_sales_yearly">₹0.00</span>
                    </div><!-- /.info-box-content -->
                </div><!-- /.info-box -->
            </div><!-- /.col -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-chart-line"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Total monthly sales</span>
                        <span class="info-box-number" id="total_sales">₹0.00</span>
                    </div><!-- /.info-box-content -->
                </div><!-- /.info-box -->
            </div><!-- /.col -->
            <!-- /.col -->



            <!-- fix for small devices only -->
            <div class="clearfix hidden-md-up"></div>
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-cart-plus"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Total orders this month</span>
                        <span class="info-box-number" id="total_orders">0</span>
                    </div><!-- /.info-box-content -->
                </div><!-- /.info-box -->
            </div><!-- /.col -->

            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                    <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-users"></i></span>

                    <div class="info-box-content">
                        <span class="info-box-text">New members this month</span>
                        <span class="info-box-number" id="total_customers">0</span>
                    </div><!-- /.info-box-content -->
                </div><!-- /.info-box -->
            </div><!-- /.col -->
        </div>
        <!-- /.row1 -->

        <div class="row">
            <!--order-yearly-->
            <div class="col-md-6">
                <div class="card">
                    <!--card header-->
                    <div class="card-header border-transparent">
                        <h2 class="card-title"><strong>Orders status today</strong></h2>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div id="order_status_today">

                        </div>
                    </div><!-- /.card-body -->
                </div><!-- /.card -->
            </div>
            <!--order monthly-->
            <div class="col-md-6">
                <div class="card">
                    <!--card header-->
                    <div class="card-header border-transparent">
                        <h2 class="card-title"><strong>Orders status this month</strong></h2>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body ">
                        <div id="order_status_monthly">

                        </div>
                    </div><!-- /.card-body -->
                </div><!-- /.card -->
            </div>
            <!--sales chart-->
            <div class="col-md-6">
                <div class="card">
                    <!--card header-->
                    <div class="card-header border-transparent">
                        <h3 class="card-title"><strong>Sales chart yearly</strong></h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0">
                        <div id="sales_chart" style=""></div>
                        <table class="table table-sm" id="sales_chart_table" data-page-length='25'>
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Sales</th>
                                </tr>
                            </thead>
                            <tbody id="sales_chart_table_tbody">
                            </tbody>
                        </table>
                    </div><!-- /.card-body -->
                </div><!-- /.card -->
            </div>
            <!--Pin wise order-->
            <div class="col-md-6">
                <div class="card">
                    <!--card header-->
                    <div class="card-header border-transparent">
                        <h3 class="card-title"><strong>Pin code wise orders this month </strong></h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0 mb-5 mt-2">
                        <div id="pin_wise_pie" style="height:350px; width:100%;"></div>
                    </div><!-- /.card-body -->
                </div><!-- /.card -->
            </div>
            <!-- TABLE: LATEST ORDERS -->
            <div class="col-md-6">
                <div class="card">
                    <!--card header-->
                    <div class="card-header border-transparent">
                        <h2 class="card-title"><strong>Leatest orders</strong></h2>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <h4 class="ml-2"> Top five</h4>
                        <div class="table-responsive">
                            <table class="table table-sm" id="latest_orders_table">
                                <thead>
                                    <tr class="thead-light">
                                        <th>ID</th>
                                        <th>Pincode</th>
                                        <th>Village</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody id="latest_orders_table_tbody">

                                </tbody>
                            </table>
                        </div><!-- /.table-responsive -->
                    </div><!-- /.card-body -->
                </div><!-- /.card -->
            </div>
            <!--col-md-6(order)>-->
            <div class="col-md-6">
                <!-- TABLE: Top selling Products -->
                <div class="card ">
                    <!--card header-->
                    <div class="card-header border-transparent">
                        <h3 class="card-title"><strong>Best selling products</strong></h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <h4 class="ml-2"> Top five</h4>
                        <div class="table-responsive">
                            <table class="table text-center table-sm " id="top_selling_products_table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Quantity</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody id="top_selling_products_table_tbody">
                                </tbody>
                            </table>
                        </div><!-- /.table-responsive -->
                    </div><!-- /.card-body -->
                </div><!-- /.card -->
            </div>
            <!--Top selling Products-->
            <!--chart Customer-->
            <div class="col-md-6">
                <div class="card">
                    <!--card header-->
                    <div class="card-header border-transparent">
                        <h3 class="card-title"><strong>Customer chart yearly</strong></h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body p-0 mt-2">
                        <div id="customers_chart"></div>
                    </div><!-- /.card-body -->
                </div><!-- /.card -->
            </div>
            <!--Customer feedbacks-->
            <div class="col-md-6">
                <div class="card">
                    <!--card header-->
                    <div class="card-header border-transparent">
                        <h3 class="card-title"><strong>Customer feedbacks till now</strong></h3>

                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="table-responsive">
                            <div class="d-flex">
                                <button class="circular ui icon button primary small  mb-1" id="btn_refresh_feedback_table" data-tooltip="Refresh table" data-variation="mini" data-position="right center" data-inverted="">
                                    <i class="sync alternate icon"></i>
                                </button>
                                {{-- <div class="ui  icon input ml-auto" style="width:50%">
                                    <input type="text" class="form-control" id="search_product_filter" placeholder="Search by Product name, Category name, Sub-catgory name, Brands name" autocomplete="off">
                                    <i class="search icon"></i>
                                </div> --}}
                            </div>
                            <table class="table text-center table-sm " id="customer_feedbacks_table" style="width:100%" data-page-length='6'>
                                <thead class="thead-light">
                                    <tr>
                                        <th>Customer</th>
                                        <th>Subject</th>
                                        <th>Date/Time</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div id="feedback_modal"></div>
                    </div><!-- /.card-body -->
                </div><!-- /.card -->
            </div>
        </div>
        <!--row2-->

    </div>
    <!--container-fluid-->
</div>
<script>
    $(document).ready(function() {
        $("#dashboard_menu").addClass("menu-open");
        dashboard();
    });

</script>
@endsection
