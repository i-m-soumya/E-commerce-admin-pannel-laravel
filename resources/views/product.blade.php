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
                                <h5 class="modal-title" id="exampleModalLabel">Add Product</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form class="" id="add_product_form" methode="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12 d-flex justify-content-center">
                                            <div class="">
                                                <div class="avatar-upload">
                                                    <div class="avatar-edit">
                                                        <input type='file' id="image1" name="image1" accept=".png, .jpg, .jpeg" />
                                                        <label for="image1"><i class="fas fa-plus ml-1"></i></label>
                                                    </div>
                                                    <div class="avatar-preview">
                                                        <div id="imagePreview1" style="background-image: url(assets/dist/img/product_default_image.png);">
                                                        </div>
                                                    </div>
                                                </div>
                                                <span class="ml-4 mb-2">Image 1<span class="text-danger">*</span></span>
                                            </div>
                                            <div class="ml-3">
                                                <div class="avatar-upload">
                                                    <div class="avatar-edit">
                                                        <input type='file' id="image2" name="image2" accept=".png, .jpg, .jpeg" />
                                                        <label for="image2"><i class="fas fa-plus ml-1"></i></label>
                                                    </div>
                                                    <div class="avatar-preview">
                                                        <div id="imagePreview2" style="background-image: url(assets/dist/img/product_default_image.png);">
                                                        </div>
                                                    </div>
                                                </div>
                                                <span class="ml-4 mb-2">Image 2</span>
                                            </div>
                                            <div class="ml-3">
                                                <div class="avatar-upload">
                                                    <div class="avatar-edit">
                                                        <input type='file' id="image3" name="image3" accept=".png, .jpg, .jpeg" />
                                                        <label for="image3"><i class="fas fa-plus ml-1"></i></label>
                                                    </div>
                                                    <div class="avatar-preview">
                                                        <div id="imagePreview3" style="background-image: url(assets/dist/img/product_default_image.png);">
                                                        </div>
                                                    </div>
                                                </div>
                                                <span class="ml-4 mb-2">Image 3</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row mt-2">
                                        <div class="form-group col-md-6">
                                            <label for="product_name">Name<span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="product_name" placeholder="Enter Name" autocomplete="off">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="category">Category<span class="text-danger">*</span></label>
                                            <select class="custom-select" id="category">
                                            </select>
                                        </div>
                                    </div>
                                    {{-- =========================================================================== --}}
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="sub_category">Sub category<span class="text-danger">*</span></label>
                                            <select class="custom-select" id="sub_category">
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="brand_search">Brand<span class="text-danger">*</span></label>
                                            <input type="hidden" id="selected_brand_id" name="selected_brand_id" value="">
                                            <div id="brand_search_loader" class="ui left icon  input purple double fluid">
                                                <input type="text" class="form-control" id="brand_search" placeholder="Search & select brand" autocomplete="off">
                                                <i class="search icon"></i>
                                            </div>
                                            <div class="list-group" id="show_brand_list">

                                            </div>
                                        </div>
                                    </div>
                                    {{-- =========================================================================== --}}
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="minimum_order">Minimum order<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" id="minimum_order" placeholder="Enter minimum order" autocomplete="off">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="maximum_order">Maximum order<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" id="maximum_order" placeholder="Enter maximum order" autocomplete="off">
                                        </div>
                                    </div>
                                    {{-- =========================================================================== --}}
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="unit_type">Unit type<span class="text-danger">*</span></label>
                                            <select class="custom-select" id="unit_type">
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="quantity">Quantity<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" id="quantity" placeholder="Enter quantity" autocomplete="off">
                                        </div>
                                    </div>
                                    {{-- =========================================================================== --}}
                                    <div class="form-row">
                                        <div class="form-group col-md-4">
                                            <label for="mrp">MRP(₹)<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" id="mrp" placeholder="Enter MRP" autocomplete="off">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="discount_percentage">Discount(%)<span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" id="discount_percentage" step="any" placeholder="Enter discount percentage" autocomplete="off">
                                        </div>
                                        <div class="form-group col-md-4">
                                            <label for="selling_price">Selling price(₹)</label>
                                            <input type="number" class="form-control" id="selling_price" disabled>
                                        </div>
                                    </div>
                                    {{-- =========================================================================== --}}
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label for="product_description">Product description<span class="text-danger">*</span></label>
                                            <textarea class="form-control" aria-label="With textarea" placeholder="Enter product description" id="product_description"></textarea>
                                        </div>
                                    </div>
                                    {{-- =========================================================================== --}}
                                    <div class="form-row">
                                        <div class="form-group col-md-12">
                                            <label for="product_tags">Product tags(<small><i class="fas fa-tags"></i></small>)<span class="text-danger">*</span></label>
                                            <textarea class="form-control" aria-label="With textarea" placeholder="Enter product tags" id="product_tags"></textarea>
                                        </div>
                                    </div>
                                    {{-- =========================================================================== --}}
                                    <div class="form-row">
                                        <div class="form-group col-md-6 d-flex">
                                            <span class="mt-2">NOTE:(<span class="text-danger">*</span>)Marked Items Are mandatory To fill</span>
                                        </div>

                                        <div class="form-group col-md-6 d-flex flex-row-reverse">
                                            {{-- <button type="submit" class="btn btn-primary btn-sm" id="add_product" style="width:80px;">Add</button>
                                            <button class="btn text-primary border-0" data-dismiss="modal"><strong>Cancel</strong></button> --}}
                                            <div class="ui buttons">
                                                <button class="ui button" data-dismiss="modal">Cancel</button>
                                                <div class="or"></div>
                                                <button type="submit" class="ui positive button" id="add_product">Add</button>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- =========================================================================== --}}
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
                <div class="">
                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#product_filters">
                        <a class=" ui primary mini button"><i class="fas fa-filter ml-1"></i> Filter</a>
                    </button>
                </div>
                <div class=" ml-auto">
                    <button class="ui primary mini button mt-2" data-toggle="modal" data-target="#add_product_modal"><small><i class="fas fa-plus  ml-2"></i></small> ADD PRODUCT</button>
                </div>
            </div>
        </div>
        <div id="product_filters" class="collapse" data-parent="#filter_accordion">
            <div class="d-flex flex-row mb-2">
                <div class="">
                    <label for="product_filter_category">Category Filter</label>
                    <select class="custom-select" id="product_filter_category">
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-12  card border border-primary">
            <div class="table-responsive  mt-2 mb-2">
                <div class="d-flex">
                    <button class="circular ui icon button primary small" id="btn_refresh_product_table" data-tooltip="Refresh table" data-variation="mini" data-position="right center" data-inverted="">
                        <i class="sync alternate icon"></i>
                    </button>
                    <div class="ui  icon input ml-auto" style="width:50%">
                        <input type="text" class="form-control" id="search_product_filter" placeholder="Search by Product name, Category name, Sub-catgory name, Brands name" autocomplete="off">
                        <i class="search icon"></i>
                    </div>
                </div>
                <table class="table table-sm table-hover text-center table-bordered " id="product_list_table" style="width:100%">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">Id</th>
                            <th scope="col">Image</th>
                            <th scope="col">Name</th>
                            <th scope="col">Category</th>
                            <th scope="col">Sub category</th>
                            <th scope="col">Availability</th>
                            <th scope="col">Brand</th>
                            <th scope="col">MRP</th>
                            <th scope="col">Discount</th>
                            <th scope="col">Selling price</th>
                            {{-- <th scope="col">Status</th> --}}
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        {{-- products_details_modal --}}
        <!-- Modal -->
        {{-- edit modal --}}
        <div id="edit_product_modal"></div>

        {{-- view_moodal --}}
        <div class="modal fade" id="view_product_details_modal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
                <div class="modal-content  card">
                    <button type="button" class="btn btn-primary rounded-pill btn-sm view_product_modal_btn_close" data-dismiss="modal">
                        <span class="">&times;</span>
                    </button>
                    <div class="modal-body card-body">
                        <div class="row">
                            <div class="col-md-4 ">
                                <div id="product_images_carousel" class="carousel slide mt-5 mb-2" data-ride="carousel">
                                    <ol class="carousel-indicators" id="product_images_carousel_indicators">

                                    </ol>
                                    <div class="carousel-inner" id="view_product_image">

                                    </div>
                                </div>
                            </div>
                            <div class=" col-md-8 ">
                                <p class="border-bottom border-dark"><strong class="h3">Product Details</strong></p>
                                <table class="table table-sm table-borderless">
                                    <colgroup>
                                        <col style="width:180px;">
                                        <col style="width:10px;">
                                        <col>
                                    </colgroup>
                                    <tr>
                                        <td><strong>Name</strong></td>
                                        <td>:</td>
                                        <td><span id="v_product_name"></span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Description</strong></td>
                                        <td>:</td>
                                        <td><span id="v_product_description"></span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Category</strong></td>
                                        <td>:</td>
                                        <td><span id="v_category"></span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Sub-Category</strong></td>
                                        <td>:</td>
                                        <td><span id="v_sub_category"></span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Brand</strong></td>
                                        <td>:</td>
                                        <td><span id="v_brand_name"></span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>MRP</strong></td>
                                        <td>:</td>
                                        <td><span id="v_mrp"></span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Discount</strong></td>
                                        <td>:</td>
                                        <td><span id="v_discount_percentage"></span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Selling price</strong></td>
                                        <td>:</td>
                                        <td><span id="v_selling_price"></span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Min qty</strong></td>
                                        <td>:</td>
                                        <td><span id="v_min_order_qty"></span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Max qty</strong></td>
                                        <td>:</td>
                                        <td><span id="v_max_order_qty"></span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Unit type</strong></td>
                                        <td>:</td>
                                        <td><span id="v_unit_type"></span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Unit quantity</strong></td>
                                        <td>:</td>
                                        <td><span id="v_unit_quantity"></span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total delivered orders</strong></td>
                                        <td>:</td>
                                        <td><span id="v_total_orders"></span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total delivered amount</strong></td>
                                        <td>:</td>
                                        <td><span id="v_total_amount"></span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Created On</strong></td>
                                        <td>:</td>
                                        <td><span id="v_created_on"></span></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" id="v_avalibility"></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        product();
    });

</script>
@endsection
