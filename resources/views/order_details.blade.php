@extends('layouts.template')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="accordion" id="accordionExample">
            <div class="row">
                <div class="col-md-6">
                    <button class="btn btn-link btn-block" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        <a class=" ui secondary tertiary large button "><span class="p-4"><i class="fas fa-bars ml-1"></i> MENU </span></a>
                    </button>
                </div>
                <div class="col-md-6">
                    <button class="btn btn-link btn-block" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        <a class=" ui secondary tertiary large button "><span class="p-4"><i class="fas fa-filter ml-1"></i> FILTER </span></a>
                    </button>
                </div>
            </div>
            <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                <ul class="nav nav-pills text-center flex-column flex-md-row collapse-nav justify-content-center">
                    <li class="nav-item ">
                        <button class="active ui secondary button small tertiary  mt-2 " id="all_order">
                            ALL
                        </button>
                    </li>
                    <li class="nav-item ">
                        <button class="ui button secondary small tertiary  mt-2 " id="active_order">
                            ACTIVE
                        </button>
                    </li>
                    <li class="nav-item ">
                        <button class="ui button secondary small tertiary  mt-2 " id="ready_to_delivery_order">
                            READY TO DELIVER
                        </button>
                    </li>
                    <li class="nav-item ">
                        <button class="ui button secondary small tertiary  mt-2 " id="out_for_delivery_order">
                            OUT FOR DELIVERY
                        </button>
                    </li>
                    <li class="nav-item ">
                        <button class=" ui button secondary small tertiary  mt-2 " id="delivered_order">
                            DELIVERED
                        </button>
                    </li>
                    <li class="nav-item ">
                        <button class=" ui button secondary small tertiary  mt-2 " id="canceled_order">
                            CANCELED
                        </button>
                    </li>
                    <li class="nav-item ">
                        <button class=" ui button secondary small tertiary  mt-2 " id="rejected_order">
                            REJECTED
                        </button>
                    </li>
                </ul>
            </div>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                <div class="d-flex flex-column flex-md-row justify-content-center">
                    <div class="p-2 bd-highlight">
                        <label for="rangestart">Start date</label>
                        <div class="ui calendar" id="rangestart">
                            <div class="ui input left icon">
                                <i class="calendar alternate icon"></i>
                                <input type="text" placeholder="Start" id="start_date" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="p-2 bd-highlight">
                        <label for="rangeend">End date</label>
                        <div class="ui calendar" id="rangeend">
                            <div class="ui action input left icon">
                                <i class="calendar alternate icon"></i>
                                <input type="text" placeholder="End" id="end_date" autocomplete="off">
                                <button class="ui button" id="date_range_search">Search</button>
                            </div>
                        </div>
                    </div>
                    <div class="p-2 bd-highlight">
                        <label for="example2">Date filter</label>
                        <div class="ui calendar" id="date_filter_calender">
                            <div class="ui action input left icon">
                                <i class="calendar alternate icon"></i>
                                <input type="text" placeholder="Date" id="date_filter" autocomplete="off">
                                <button class="ui button" id="date_filter_btn">Filter</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="content">
    <div class="container-fluid">
        <div class="tab-content" id="pills-tabContent">
            {{--============================== all_order ===============================--}}
            <div class="tab-pane fade show active" id="all_order" role="tabpanel">
                <div class="col-md-12  card border border-primary">
                    <div class="table-responsive  mt-2 mb-2">
                        <div class="d-flex">
                            <button class="circular ui icon button primary small" id="btn_refresh_order_table" data-tooltip="Refresh table" data-variation="mini" data-position="right center" data-inverted="">
                                <i class="sync alternate icon"></i>
                            </button>
                            <div class="ui  icon input ml-auto" style="width:50%">
                                <input type="text" class="form-control" id="search_order_filter" placeholder="Search by Customer name, Village name, Pincode, Delivery partner name" autocomplete="off">
                                <i class="search icon"></i>
                            </div>
                        </div>
                        <table class="table table-sm table-hover text-center table-bordered " id="all_order_table" style="width:100%;">
                            <thead class="thead-light ">
                                <tr>
                                    <th scope="col">Id</th>
                                    {{-- <th scope="col">Invoice</th> --}}
                                    <th scope="col">Customer</th>
                                    <th scope="col">Date & Time</th>
                                    <th scope="col">Pin code</th>
                                    <th scope="col">Village</th>
                                    <th scope="col">Total discount</th>
                                    <th scope="col">Payable amount</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Delivery partner</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <!-- Modal -->
                <div id="create_assign_modal"></div>
                <div id="view_order_modal_content"></div>
                <div id="reject_order_modal_content"></div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        order_details();
    });

</script>
@endsection
