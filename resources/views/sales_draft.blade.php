@extends('layouts.template')
@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h3 class="modal-title" id="exampleModalLabel">Sell Manually</h3>
    </div>
</div>
<div class="content">
    <div class="container-fluid">
        <div class="tab-content" id="pills-tabContent">
            <form class="" id="add_product_form" methode="post" enctype="multipart/form-data">
                @csrf
                <H3>Customer Details</H3>
                <div class="form-row mt-3">
                    <div class="form-group col-md-4">
                        <label for="product_name">Customer Name<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="product_name" placeholder="Enter Name" autocomplete="off">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="product_name">Customer Mobile Number<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="product_name" placeholder="Enter Name" autocomplete="off">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="product_name">Customer Email Address<span class="text-danger"></span></label>
                        <input type="text" class="form-control" id="product_name" placeholder="Enter Name" autocomplete="off">
                    </div>
                </div>
                <H3>Customer Address</H3>
                <div class="form-row mt-3">
                    <div class="form-group col-md-4">
                        <label for="product_name">Country<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="product_name" placeholder="Enter Name" autocomplete="off">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="product_name">State<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="product_name" placeholder="Enter Name" autocomplete="off">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="product_name">District<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="product_name" placeholder="Enter Name" autocomplete="off">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="product_name">Pin Code<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="product_name" placeholder="Enter Name" autocomplete="off">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="product_name">Village<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="product_name" placeholder="Enter Name" autocomplete="off">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="product_name">Road Name/Area/Colony<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="product_name" placeholder="Enter Name" autocomplete="off">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="product_name">House No/Building Name/Locality<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="product_name" placeholder="Enter Name" autocomplete="off">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="product_name">Landmark<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="product_name" placeholder="Enter Name" autocomplete="off">
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
                    <div class="ui positive button" onclick="addNewProductRow()">Add New</div>
                    <div class="form-row">
                        <div class="form-group col-md-6 d-flex">
                            <span class="mt-2">NOTE:(<span class="text-danger">*</span>)Marked Items Are mandatory To fill</span>
                        </div>

                        <div class="form-group col-md-6 d-flex flex-row-reverse">
                            <div class="ui buttons">
                                <button class="ui button" data-dismiss="modal">Cancel</button>
                                <div class="or"></div>
                                <button type="submit" class="ui positive button" id="add_product">Order</button>
                            </div>
                        </div>
                    </div>
            </form>
        </div>
    </div>
</div>
@endsection
<script>
    var flag = 1;
    function addNewProductRow() {
        let prList = document.querySelector(".pr-list");
        flag+=1;
        prList.innerHTML +=
        "<li>"+
            "<div class='form-row mb-1'>"+
                "<div class='form-group col-md-4'>"+

                    "<input type='text' class='form-control' id='product_name_"+flag+"' placeholder='Enter Name' autocomplete='off'>"+
                "</div>"+
                "<div class='form-group col-md-2'>"+

                    "<input type='number' class='form-control' id='product_qty_"+flag+"' placeholder='Enter Quantity' autocomplete='off'>"+
                "</div>"+
                "<div class='form-group col-md-2'>"+

                    "<input type='number' class='form-control' id='price_"+flag+"' placeholder='Enter Price' autocomplete='off'>"+
                "</div>"+
            "</div>"+
        "</li>";
    }
</script>

<style>
    .scrollbar
{
	overflow-y: scroll;
    overflow-x: hidden;
}
</style>
