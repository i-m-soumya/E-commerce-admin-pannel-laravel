@extends('layouts.template')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <ul class="nav nav-pills text-center flex-column flex-md-row collapse-nav " role="tablist">
            <li class="nav-item ml-2 mr-2" role="presentation">
                <button class="ui tertiary secondary large active button nav-link  " data-toggle="pill" href="#order" role="tab">ORDER BY PRODUCT</button>
            </li>
            <li class="nav-item ml-2 mr-2" role="presentation">
                <button class="ui tertiary secondary large button nav-link  " data-toggle="pill" href="#aggregator" role="tab">AGGREGATOR</button>
            </li>
            <li class="nav-item ml-2 mr-2" role="presentation">
                <button class="ui tertiary secondary large button nav-link  " data-toggle="pill" href="#village_wise_order" role="tab">VILLAGE WISE ORDER</button>
            </li>
        </ul>
    </div>
</div>
<div class="content">
    <div class="container-fluid">
        <div class="tab-content">
            <div class="tab-pane fade show active" id="order" role="tabpanel">
                <div class="row">
                    <div class="accordion" id="filter_accordion">
                        <button class="btn btn-link btn-block" type="button" data-toggle="collapse" data-target="#order_by_product_date_search">
                            <a class=" ui primary mini button"><span class="p-4"><i class="fas fa-filter ml-1"></i> Filter</span></a>
                        </button>
                        <div id="order_by_product_date_search" class="collapse" data-parent="#filter_accordion">
                            <div class="d-flex flex-column flex-md-row justify-content-center">
                                <div class="p-2 bd-highlight">
                                    <label for="order_by_product_rangestart" class="text-sm">Start date</label>
                                    <div class="ui calendar" id="order_by_product_rangestart">
                                        <div class="ui input mini left icon">
                                            <i class="calendar alternate icon"></i>
                                            <input type="text" placeholder="Start date/time" id="order_by_product_start_date" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="p-2 bd-highlight">
                                    <label for="order_by_product_rangeend" class="text-sm">End date</label>
                                    <div class="ui calendar" id="order_by_product_rangeend">
                                        <div class="ui action input mini left icon">
                                            <i class="calendar alternate icon"></i>
                                            <input type="text" placeholder="End date/time" id="order_by_product_end_date" autocomplete="off">
                                            <button class="ui icon button mini" id="order_by_product_date_range_search"><i class="search icon"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12  card border border-primary">
                        <div class="table-responsive  mt-2 mb-2">
                            <div class="d-flex">
                                <button class="circular ui icon button primary small" id="btn_refresh_order_by_product_table" data-tooltip="Refresh table" data-variation="mini" data-position="right center" data-inverted="">
                                    <i class="sync alternate icon"></i>
                                </button>
                            </div>
                            <table class="table table-sm table-hover text-center table-bordered " id="report_order_by_product_table" style="width:100%;" data-page-length='25'>
                                <thead class="thead-light ">
                                    <tr>
                                        <th scope="col">Sl no.</th>
                                        <th scope="col">Product id</th>
                                        <th scope="col">Product name</th>
                                        <th scope="col">Unit type</th>
                                        <th scope="col">Unit quantity</th>
                                        <th scope="col">Total product order</th>
                                        <th scope="col">Total unit quantity</th>
                                        <th scope="col">Total amount</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row d-flex justify-content-end">
                    <button class="ui mini primary button   right floated " id="btn_print_order_by_product_report"><i class="print icon"></i>Print</button>
                </div>
            </div>
            <div class="tab-pane fade" id="aggregator" role="tabpanel">
                <div class="row">
                    <div class="accordion" id="filter_accordion">
                        <button class="btn btn-link btn-block" type="button" data-toggle="collapse" data-target="#aggregator_date_search">
                            <a class=" ui primary mini button"><span class="p-4"><i class="fas fa-filter ml-1"></i> Filter</span></a>
                        </button>
                        <div id="aggregator_date_search" class="collapse" data-parent="#filter_accordion">
                            <div class="d-flex flex-column flex-md-row justify-content-center">
                                <div class="p-2 bd-highlight">
                                    <label for="aggregator_rangestart" class="text-sm">Start date</label>
                                    <div class="ui calendar" id="aggregator_rangestart">
                                        <div class="ui input mini left icon">
                                            <i class="calendar alternate icon"></i>
                                            <input type="text" placeholder="Start date/time" id="aggregator_start_date" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                                <div class="p-2 bd-highlight">
                                    <label for="aggregator_rangeend" class="text-sm">End date</label>
                                    <div class="ui calendar" id="aggregator_rangeend">
                                        <div class="ui action input mini left icon">
                                            <i class="calendar alternate icon"></i>
                                            <input type="text" placeholder="End date/time" id="aggregator_end_date" autocomplete="off">
                                            <button class="ui icon button mini" id="aggregator_date_range_search"><i class="search icon"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12  card border border-primary">
                    <div class="table-responsive  mt-2 mb-2">
                        <div class="d-flex">
                            <button class="circular ui icon button primary small" id="btn_refresh_aggregator_table" data-tooltip="Refresh table" data-variation="mini" data-position="right center" data-inverted="">
                                <i class="sync alternate icon"></i>
                            </button>
                        </div>
                        <table class="table table-bordered table-sm text-center" id="aggregator_table" width="100%" data-page-length='25'>
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Total order</th>
                                    <th scope="col">Total amount</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <button class="ui tertiary primary button  d-flex ml-auto active right floated" id="btn_print_aggregator_report"><i class="print icon"></i>Print</button>
                <div id="view_aggregator_order_details_modal_placeholder"></div>
            </div>
            <div class="tab-pane fade" id="village_wise_order" role="tabpanel">
                <div class="col-md-12  card border border-primary">
                    <div class="d-flex flex-column flex-md-row">
                        <div class="p-2 bd-highlight">
                            <label for="order_by_product_rangestart" class="text-sm">Start date</label>
                            <div class="ui calendar" id="village_rangestart">
                                <div class="ui input mini left icon">
                                    <i class="calendar alternate icon"></i>
                                    <input type="text" placeholder="Start date" id="village_start_date" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="p-2 bd-highlight">
                            <label for="order_by_product_rangestart" class="text-sm">End date</label>
                            <div class="ui calendar" id="village_rangeend">
                                <div class="ui action input mini left icon">
                                    <i class="calendar alternate icon"></i>
                                    <input type="text" placeholder="Start date" id="village_end_date" autocomplete="off">
                                    <button class="ui icon button mini" id="village_date_range_search"><i class="search icon"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive  mt-2 mb-2">
                        <table class="table table-bordered table-sm" id="aggregator_table" width="100%">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Pincode</th>
                                    <th scope="col">Village</th>
                                    <th scope="col">Total order</th>
                                    <th scope="col">Total amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <Select class="custom-select" id="pincode">
                                        </select>
                                    </td>
                                    <td>
                                        <Select class="custom-select input-sm" id="village">
                                        </select>
                                    </td>
                                    <td id="total_village_order">

                                    </td>
                                    <td id="total_village_order_amount">

                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        report();
    });

</script>
@endsection
