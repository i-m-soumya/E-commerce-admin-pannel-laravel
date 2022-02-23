@extends('layouts.template')
@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-12">

                <!-- Modal -->
                <div class="modal fade" id="add_product_modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Create Sale</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form class="" id="add_product_form" enctype="multipart/form-data">
                                    @csrf
                                    <H3>Customer Details</H3>
                                    <div class="form-row mt-3">
                                        <div class="form-group col-md-4">
                                            <label for="customer_name">Customer Name<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="customer_name" placeholder="Enter Name" autocomplete="off">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="customer_mobile">Customer Mobile Number<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="customer_mobile" placeholder="Enter Name" autocomplete="off">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="customer_email">Customer Email Address<span class="text-danger"></span></label>
                                            <input type="text" class="form-control" id="customer_email" placeholder="Enter Name" autocomplete="off">
                                        </div>
                                    </div>
                                    <H3>Customer Address</H3>
                                    <div class="form-row mt-3">
                                        <div class="form-group col-md-4">
                                            <label for="country">Country<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="country" placeholder="Enter Name" autocomplete="off">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="state">State<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="state" placeholder="Enter Name" autocomplete="off">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="district">District<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="district" placeholder="Enter Name" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="pin">Pin Code<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="pin" placeholder="Enter Name" autocomplete="off">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="village">Village<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="village" placeholder="Enter Name" autocomplete="off">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="area">Road Name/Area/Colony<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="area" placeholder="Enter Name" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="locality">House No/Building Name/Locality<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="locality" placeholder="Enter Name" autocomplete="off">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="landmark">Landmark<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="landmark" placeholder="Enter Name" autocomplete="off">
                                        </div>
                                    </div>
                                    <H3>Products</H3>
                                    <ol class="pr-list">
                                        <li>
                                            <div class="form-row">
                                                <div class="form-group col-md-4">

                                                    <input type="text" class="form-control" id="product_name_1" placeholder="Enter Name" autocomplete="off">
                                                </div>
                                                <div class="form-group col-md-2">

                                                    <input type="number" class="form-control" id="product_qty_1" placeholder="Enter Quantity" autocomplete="off">
                                                </div>
                                                <div class="form-group col-md-2">

                                                    <input type="number" class="form-control" id="price_1" placeholder="Enter Price" autocomplete="off">
                                                </div>
                                            </div>
                                        </li>
                                    </ol>
                                    <div class="ui warning button" onclick="addNewProductRow()">Add New</div>
                                    <br><br>
                                    <div class="form-row">
                                    <div class="form-group col-md-6">
                                    <input type="checkbox" id="ch1" name="ch1">
                                    <label for="ch1"> Delivery Charge</label><br>
                                    </div>
                                    </div>
                                    <div class="form-row">
                                    <div class="form-group col-md-6">
                                    <input type="checkbox" id="ch2" name="ch2">
                                    <label for="ch2"> Offline</label><br>
                                    </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6 d-flex">
                                            <span class="mt-2">NOTE:(<span class="text-danger">*</span>)Marked Items Are mandatory To fill</span>
                                        </div>

                                        <div class="form-group col-md-6 d-flex flex-row-reverse">
                                            <div class="ui buttons">
                                                <button class="ui button" data-dismiss="modal">Cancel</button>
                                                <div class="or"></div>
                                                <button type="button" class="ui positive button" id="add_product" onclick="orderPlaced()">Order</button>
                                                <button type="submit" hidden="hidden" style="display: none;" id="add_product" onclick="orderPlaced()">Order</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
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
        <div class="accordion mb-2 mt-0" id="filter_accordion">
            <div class="d-flex">
                <div class=" ml-auto">
                    <button class="ui primary mini button mt-2" data-toggle="modal" data-target="#add_product_modal"><small><i class="fas fa-plus  ml-2"></i></small> New Sale</button>
                </div>
            </div>
        </div>
        <div class="col-md-12  card border border-primary">
            <div class="table-responsive  mt-2 mb-2">
                <table class="table table-sm table-hover text-center table-bordered " id="product_list_table" style="width:100%">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">Order Id</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
<script>
    var flag = 1;

    function addNewProductRow() {
        let prList = document.querySelector(".pr-list");
        flag += 1;
        prList.innerHTML +=
            "<li>" +
            "<div class='form-row mb-1'>" +
            "<div class='form-group col-md-4'>" +

            "<input type='text' class='form-control' id='product_name_" + flag + "' placeholder='Enter Name' autocomplete='off'>" +
            "</div>" +
            "<div class='form-group col-md-2'>" +

            "<input type='number' class='form-control' id='product_qty_" + flag + "' placeholder='Enter Quantity' autocomplete='off'>" +
            "</div>" +
            "<div class='form-group col-md-2'>" +

            "<input type='number' class='form-control' id='price_" + flag + "' placeholder='Enter Price' autocomplete='off'>" +
            "</div>" +
            "</div>" +
            "</li>";
    }
    function orderPlaced() {
        let orderdata = {
            customerDetails : {
                name : document.getElementById("customer_name").value,
                mobile : document.getElementById("customer_mobile").value,
                email : document.getElementById("customer_email").value,
            },
            customerAddress : {
                country : document.getElementById("country").value,
                state : document.getElementById("state").value,
                district : document.getElementById("district").value,
                pin : document.getElementById("pin").value,
                village : document.getElementById("village").value,
                area : document.getElementById("area").value,
                locality : document.getElementById("locality").value,
                landmark : document.getElementById("landmark").value,
            },
            products : [],
            isDelivery : document.getElementById("ch1").checked,
            isOffline : document.getElementById("ch2").checked,
        }
        console.log(orderdata);
        for(let i = 0 ; i < flag ; i++) {
            let j = i+1;
            console.log(document.getElementById("product_name_"+j).value);
        }
    }
</script>
