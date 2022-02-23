@extends('layouts.template')

@section('content')
<div class="content-header">
    <div class="container-fluid">

        <ul class="nav nav-pills text-center flex-column flex-md-row collapse-nav " role="tablist">
            <li class="nav-item ml-2 mr-2" role="presentation">
                <button class="ui tertiary secondary large active button nav-link" id="customer_tab" data-toggle="pill" href="#customer" role="tab">CUSTOMER</button>
            </li>
            <li class="nav-item ml-2 mr-2" role="presentation">
                <button class="ui tertiary secondary large button nav-link" id="admin_tab" data-toggle="pill" href="#admin" role="tab">ADMIN</button>
            </li>
            <li class="nav-item ml-2 mr-2" role="presentation">
                <button class="ui tertiary secondary large button nav-link" id="aggregator_tab" data-toggle="pill" href="#aggregator" role="tab">AGGREGATOR</button>
            </li>
            <li class="nav-item ml-2 mr-2" role="presentation">
                <button class="ui tertiary secondary large button nav-link" id="salesman_tab" data-toggle="pill" href="#salesman" role="tab">SALESMAN</button>
            </li>
            <li class="nav-item ml-auto" id="change_eable_btn">

            </li>
        </ul>

    </div>
</div>
<div class="content">
    <div class="container-fluid">
        <div class="tab-content">
            <div class="tab-pane fade show active" id="customer" role="tabpanel">
                <div class="row">
                    <div class="col-md-12  card border border-primary">
                        <div class="table-responsive  mt-2 mb-2">
                            <div class="d-flex">
                                <button class="circular ui icon button primary small" id="btn_refresh_customer_table" data-tooltip="Refresh table" data-variation="mini" data-position="right center" data-inverted="">
                                    <i class="sync alternate icon"></i>
                                </button>
                                <div class="ui  icon input ml-auto" style="width:50%">
                                    <input type="text" class="form-control" id="search_customer_filter" placeholder="Search by Customer id, Name, Email, Mobile number" autocomplete="off">
                                    <i class="search icon"></i>
                                </div>
                            </div>
                            <table class="table table-sm table-hover text-center table-bordered " id="customer_table" style="width:100%;" data-page-length='25'>
                                <thead class="thead-light ">
                                    <tr>
                                        {{-- <th scope="col">Sl no.</th> --}}
                                        <th scope="col">Customer id</th>
                                        <th scope="col">Customer name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Mobile no.</th>
                                        <th scope="col">Created at</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div id="customer_details_modal"></div>
                </div>
            </div>
            {{-- admin --}}
            <div class="tab-pane fade" id="admin" role="tabpanel">
                <x-users_modal modalid="admin_modal" modaltitle="Add Admin" formid="admin_form" nameinputid="admin_name" namelabel="Name" emailinputid="admin_email" emaillabel="Email" mobileinputid="admin_mobile" mobilelabel="Mobile number" />
                <div class="row">
                    <div class="col-md-12  card border border-primary">
                        <div class="table-responsive  mt-2 mb-2">
                            <div class="d-flex">
                                <div class="ui  icon input ml-auto" style="width:50%">
                                    <input type="text" class="form-control" id="search_admin_filter" placeholder="Search by Admin id, Name, Email, Mobile number" autocomplete="off">
                                    <i class="search icon"></i>
                                </div>
                            </div>
                            <table class="table table-sm table-hover text-center table-bordered " id="admin_table" style="width:100%;" data-page-length='25'>
                                <thead class="thead-light ">
                                    <tr>
                                        <th scope="col">Sl no.</th>
                                        <th scope="col">Admin id</th>
                                        <th scope="col">Admin name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Mobile No.</th>
                                        <th scope="col">Created at</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="admin_table_tbody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            {{-- aggregator --}}
            <div class="tab-pane fade" id="aggregator" role="tabpanel">
                <x-users_modal modalid="aggregator_modal" modaltitle="Add Aggregator" formid="aggregator_form" nameinputid="aggregator_name" namelabel="Name" emailinputid="aggregator_email" emaillabel="Email" mobileinputid="aggregator_mobile" mobilelabel="Mobile number" />
                <div class="row">
                    <div class="col-md-12  card border border-primary">
                        <div class="table-responsive  mt-2 mb-2">
                            <div class="d-flex">
                                <div class="ui  icon input ml-auto" style="width:50%">
                                    <input type="text" class="form-control" id="search_aggregator_filter" placeholder="Search by Aggregator id, Name, Email, Mobile number" autocomplete="off">
                                    <i class="search icon"></i>
                                </div>
                            </div>
                            <table class="table table-sm table-hover text-center table-bordered " id="aggregator_table" style="width:100%;" data-page-length='25'>
                                <thead class="thead-light ">
                                    <tr>
                                        <th scope="col">Sl no.</th>
                                        <th scope="col">Aggregator id</th>
                                        <th scope="col">Aggregator name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Mobile No.</th>
                                        <th scope="col">Created at</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="aggregator_table_tbody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            {{-- salesman --}}
            <div class="tab-pane fade" id="salesman" role="tabpanel">
                <x-users_modal modalid="salesman_modal" modaltitle="Add Salesman" formid="salesman_form" nameinputid="salesman_name" namelabel="Name" emailinputid="salesman_email" emaillabel="Email" mobileinputid="salesman_mobile" mobilelabel="Mobile number" />
                <div class="row">
                    <div class="col-md-12  card border border-primary">
                        <div class="table-responsive  mt-2 mb-2">
                            <div class="d-flex">
                                <div class="ui  icon input ml-auto" style="width:50%">
                                    <input type="text" class="form-control" id="search_salesman_filter" placeholder="Search by Salesman id, Name, Email, Mobile number" autocomplete="off">
                                    <i class="search icon"></i>
                                </div>
                            </div>
                            <table class="table table-sm table-hover text-center table-bordered " id="salesman_table" style="width:100%;" data-page-length='25'>
                                <thead class="thead-light ">
                                    <tr>
                                        <th scope="col">Sl no.</th>
                                        <th scope="col">Salesman id</th>
                                        <th scope="col">Salesman name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Mobile No.</th>
                                        <th scope="col">Created at</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="salesman_table_tbody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $("#users_menu").addClass("menu-open");
        users();
    });

</script>
@endsection
