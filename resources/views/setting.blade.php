@extends('layouts.template')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                {{-- top navigation --}}
                <div class="card ctab">
                    <ul class="nav nav-tabs text-center flex-column flex-md-row p-2" id="pills-tab" role="tablist">
                        <li class="nav-item flex-md-fill border-primary" role="presentation">
                            <a class="nav-link active text-center" id="category_tab" data-toggle="tab" href="#category" role="tab"><strong>CATEGORY</strong></a>
                        </li>
                        <li class="nav-item flex-md-fill border-primary" role="presentation">
                            <a class="nav-link" id="sub_category_tab" data-toggle="tab" href="#sub_category" role="tab"><strong>SUB CATEGORY</strong></a>
                        </li>
                        <li class="nav-item flex-md-fill border-primary" role="presentation">
                            <a class="nav-link" id="brands_tab" data-toggle="tab" href="#brands" role="tab"><strong>BRANDS</strong></a>
                        </li>
                        <li class="nav-item flex-md-fill border-primary" role="presentation">
                            <a class="nav-link" id="unit_tab" data-toggle="tab" href="#unit" role="tab"><strong>UNIT TYPE</strong></a>
                        </li>
                        <li class="nav-item flex-md-fill border-primary" role="presentation">
                            <a class="nav-link" id="cancellation_reason_tab" data-toggle="tab" href="#cancellation_reason" role="tab"><strong>CANCELLATION REASON</strong></a>
                        </li>
                        <li class="nav-item flex-md-fill border-primary" role="presentation">
                            <a class="nav-link" id="pincode_and_village_tab" data-toggle="tab" href="#pincode_and_village" role="tab"><strong>PINCODE & VILLAGE</strong></a>
                        </li>
                        <li class="nav-item flex-md-fill border-primary" role="presentation">
                            <a class="nav-link" id="extra_settings_tab" data-toggle="tab" href="#extra_settings_tab_content" role="tab"><strong>EXTRA SETTINGS</strong></a>
                        </li>
                        <li class="nav-item" role="presentation" id="change_eable_btn">
                            <a class="nav-link" data-toggle="modal" data-target="#add_category_modal"><small><i class="fas fa-plus"></i></small> Add category</a>
                        </li>
                    </ul>
                </div>
                {{-- end top navigation --}}
            </div>
        </div>
    </div>
</div>
<div class="content">
    <div class="container-fluid">
        <div class="tab-content" id="pills-tabContent">
            {{-- ===================================category========================================================= --}}
            <div class="tab-pane fade show active" id="category" role="tabpanel">

                <x-settings_modal modalid="add_category_modal" modaltitle="Add new category" formid="add_category_form" inputid="new_category_name" inputtype="text" labelname="Category name" btncreateid="btn_create_category">
                    <div class="col-md-12 d-flex justify-content-center">
                        <div class="">
                            <div class="avatar-upload">
                                <div class="avatar-edit">
                                    <input type='file' id="category_image" name="category_image" accept=".png, .jpg, .jpeg" />
                                    <label for="category_image"><i class="fas fa-plus ml-1"></i></label>
                                </div>
                                <div class="avatar-preview">
                                    <div id="category_imagePreview" style="background-image: url(assets/dist/img/product_default_image.png);">
                                    </div>
                                </div>
                            </div>
                            <span class="ml-4">Image<span class="text-danger">*</span></span>
                        </div>
                    </div>
                </x-settings_modal>
                <div class="row" id="all_category_view">

                </div>
            </div>
            {{-- ===================================sub_category========================================================= --}}
            <div class="tab-pane fade" id="sub_category" role="tabpanel">
                <x-settings_modal modalid="add_sub_category_modal" modaltitle="Add new sub-category" formid="sub_category_form" inputid="new_sub_category_name" inputtype="text" labelname="Sub category name" btncreateid="btn_create_sub_category">
                    <div class="col-md-12 d-flex justify-content-center">
                        <div class="">
                            <div class="avatar-upload">
                                <div class="avatar-edit">
                                    <input type='file' id="sub_category_image" name="sub_category_image" accept=".png, .jpg, .jpeg" />
                                    <label for="sub_category_image"><i class="fas fa-plus ml-1"></i></label>
                                </div>
                                <div class="avatar-preview">
                                    <div id="sub_category_imagePreview" style="background-image: url(assets/dist/img/product_default_image.png);">
                                    </div>
                                </div>
                            </div>
                            <span class="ml-4">Image<span class="text-danger">*</span></span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="select_category">Select_category</label>
                            <select class="custom-select" id="select_category">
                            </select>
                        </div>
                    </div>
                </x-settings_modal>
                <div class="row" id="all_sub_category_list_view">

                </div>
            </div>
            {{-- ===================================brands========================================================= --}}
            <div class="tab-pane fade" id="brands" role="tabpanel">
                <x-settings_modal modalid="add_brand_modal" modaltitle="Add new Brand" formid="brand_form" inputid="new_brand_name" inputtype="text" labelname="Brand name" btncreateid="btn_create_brand">
                    <div class="col-md-12 d-flex justify-content-center">
                        <div class="">
                            <div class="avatar-upload">
                                <div class="avatar-edit">
                                    <input type='file' id="brand_image" name="brand_image" accept=".png, .jpg, .jpeg" />
                                    <label for="brand_image"><i class="fas fa-plus ml-1"></i></label>
                                </div>
                                <div class="avatar-preview">
                                    <div id="brand_imagePreview" style="background-image: url(assets/dist/img/product_default_image.png);">
                                    </div>
                                </div>
                            </div>
                            <span class="ml-4">Image<span class="text-danger">*</span></span>
                        </div>
                    </div>
                </x-settings_modal>
                <div class="col-md-12  card border border-primary">
                    <div class="table-responsive  mt-2 mb-2">
                        <table class="table table-sm table-hover text-center table-bordered" id="all_brand_table">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Id</th>
                                    <th scope="col">Image</th>
                                    <th scope="col">Name</th>
                                    {{-- <th scope="col">Added by</th> --}}
                                    <th scope="col">Added On</th>
                                    {{-- <th scope="col">Status</th> --}}
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody id="all_brand_table_tbody">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {{-- ===================================unit_type========================================================= --}}
            <div class="tab-pane fade" id="unit" role="tabpanel">
                <x-settings_modal modalid="add_unit_type_modal" modaltitle="Add new Unit type" formid="unit_type_form" inputid="new_unit_type_name" inputtype="text" labelname="Unit type name" btncreateid="btn_create_unit" />
                <div class="col-md-12  card border border-primary">
                    <div class="table-responsive  mt-2 mb-2">
                        <table class="table table-sm table-hover text-center table-bordered" id="all_unit_type_table">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Id</th>
                                    <th scope="col">Name</th>
                                    {{-- <th scope="col">Status</th> --}}
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody id="all_unit_type_table_tbody">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {{-- ===================================unit_type========================================================= --}}
            <div class="tab-pane fade" id="cancellation_reason" role="tabpanel">
                <x-settings_modal modalid="add_cancellation_reason" modaltitle="Add new reason" formid="cancellation_reason_form" inputid="new_cancellation_reason" inputtype="text" labelname="New reason" btncreateid="btn_create_cancel_reason" />
                <div class="col-md-12  card border border-primary">
                    <div class="table-responsive  mt-2 mb-2">
                        <table class="table table-sm table-hover text-center table-bordered" id="all_cancellation_reason_table">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">Sl no.</th>
                                    <th scope="col">Id</th>
                                    <th scope="col">Reason</th>
                                </tr>
                            </thead>
                            <tbody id="all_cancellation_reason_table_tbody">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {{-- ===================================pincode_&_village========================================================= --}}
            <div class="tab-pane fade" id="pincode_and_village" role="tabpanel">
                {{-- second top navigation --}}
                <div class="card ctab">
                    <ul class="nav nav-tabs text-center flex-column flex-md-row p-2" id="second-pills-tab" role="tablist">
                        <li class="nav-item flex-md-fill border-primary" role="presentation">
                            <a class="nav-link text-center active" id="pincode_tab" data-toggle="tab" href="#pincode" role="tab"><strong>PINCODE</strong></a>
                        </li>
                        <li class="nav-item flex-md-fill border-primary" role="presentation">
                            <a class="nav-link" id="village_tab" data-toggle="tab" href="#village" role="tab"><strong>VILLAGE</strong></a>
                        </li>
                        <li class="nav-item" role="presentation" id="change_eable_btn_2">
                            {{-- <a class="nav-link" data-toggle="modal" data-target="#add_village_modal"><small><i class="fas fa-plus"></i></small> Add pincode</a> --}}
                        </li>
                    </ul>
                </div>

                {{--============================= end second top navigation ================================--}}
                {{--===================================== inner-tab-content ===============================--}}
                <div class="tab-content" id="second-pills-tabContent">
                    {{-- add-pincode --}}
                    <div class="tab-pane fade show active" id="pincode" role="tabpanel">
                        <x-settings_modal modalid="add_pincode_modal" modaltitle="Add new pincode" formid="pincode_form" inputid="new_pincode" inputtype="number" labelname="PINCODE" btncreateid="btn_create_pincode" />
                        <div class="col-md-12  card border border-primary">
                            <div class="table-responsive  mt-2 mb-2">
                                <table class="table table-sm table-hover text-center table-bordered" id="all_pincode_table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col">Id</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="all_pincode_table_tbody">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 d-flex "><span class="text-deepred">*</span><span>NOTE: Deactivation of a pincode will automatically deactivate all the villages in it.</span></div>
                        </div>
                    </div>
                    {{--==========================add_village ======================================================--}}
                    <div class="tab-pane fade" id="village" role="tabpanel">
                        <x-settings_modal modalid="add_village_modal" modaltitle="Add new village" formid="village_form" inputid="new_village_name" inputtype="text" labelname="VILLAGE" btncreateid="btn_create_village">
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label for="selected_category">select_pincode</label>
                                    <select class="custom-select" id="selected_pincode">
                                    </select>
                                </div>
                            </div>
                        </x-settings_modal>
                        <div class="col-md-12  card border border-primary">
                            <div class="table-responsive  mt-2 mb-2">
                                <table class="table table-sm table-hover text-center table-bordered" id="all_village_table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col">Id</th>
                                            <th scope="col">Village name</th>
                                            <th scope="col">Pincode</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="all_village_table_tbody">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- ===================================Featured image========================================================= --}}
            <div class="tab-pane fade" id="extra_settings_tab_content" role="tabpanel">
                <div class="row">
                    <!--Add Featured image-->
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header border-transparent">
                                <h3 class="card-title"><strong>Featured Image</strong></h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <button class="btn  btn-primary btn-sm" data-toggle="modal" data-target="#add_featured_image_modal" id="add_feature_images_btn">Add featured image</button>
                                <div class="row mt-2" id="featured_image_view_placeholder">
                                </div>

                                {{-- featured image modal --}}
                                <div class="modal fade" id="add_featured_image_modal" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modal_title">Add new featured image</h5>
                                                <button type="button" id="btn_modal_close" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form class="" id="featured_image_form" methode="post" enctype="multipart/form-data">
                                                    @csrf
                                                    <label for="image_div" class="ml-5 mt-1">Image<span class="text-danger">*</span></label>
                                                    <div class="col-md-12 d-flex justify-content-center mb-2" id="image_div">
                                                        <div class="">
                                                            <div class="featured_image_upload">
                                                                <div class="featured_image_edit">
                                                                    <input type='file' id="featured_image" name="featured_image" accept=".png, .jpg, .jpeg" />
                                                                    <label for="featured_image"><i class="fas fa-plus ml-2 mt-2 mr-2 mb-2"></i></label>
                                                                </div>
                                                                <div class="featured_image_preview">
                                                                    <div id="featured_imagePreview" style="background-image: url(assets/dist/img/product_default_image.png);">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="form-group col-md-12">
                                                            <label for="featured_image_action">Action<span class="text-danger">*</span></label>
                                                            <select class="custom-select " id="featured_image_action">
                                                                <option value="" id="selected_action_id">Select action</option>
                                                                <option value="product">Product</option>
                                                                <option value="brands">Brands</option>
                                                                <option value="category">Category</option>
                                                                <option value="subcategory">Sub-Category</option>
                                                                <option value="keyword">Keyword</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="form-group col-md-12" id="action_keyword_placeholder">
                                                        </div>
                                                    </div>

                                                    <div class="form-row">
                                                        <div class="form-group col-md-12">
                                                            <div class="d-flex justify-content-end">
                                                                <button type="submit" id="featured_image_create_btn" class="btn btn-outline-primary btn-sm rounded-pill"><small><i class="fas fa-plus"></i></small>Create</button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- /.card-body -->
                        </div><!-- /.card -->
                    </div>
                    <!--/Add Featured image-->

                    <!--Delivery Charge modification-->
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header border-transparent">
                                <h3 class="card-title"><strong>Delivery Charge modification</strong></h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-sm text-center mb-4" id="delivery_charge_table">
                                    <thead>
                                        <tr class="thead-light">
                                            <th scope="col">Amount</th>
                                            <th scope="col">Charge</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="delivery_charge_table_tbody">
                                    </tbody>
                                </table>
                                {{-- featured image modal --}}
                                <div id="delivery_charge_modification_modal"></div>
                            </div><!-- /.card-body -->
                        </div><!-- /.card -->
                    </div>
                    <!--/Delivery Charge modification-->

                    <!--Maximum order modification-->
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header border-transparent">
                                <h3 class="card-title"><strong>Minimum order modification</strong></h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-sm text-center mb-4" id="minimum_order_table">
                                    <thead>
                                        <tr class="thead-light">
                                            <th scope="col">Minimum order amount</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="minimum_order_table_tbody">
                                    </tbody>
                                </table>
                                {{-- featured image modal --}}
                                <div id="minimum_order_modification_modal"></div>
                            </div><!-- /.card-body -->
                        </div><!-- /.card -->
                    </div>
                    <!--/Delivery Charge modification-->

                    <!--Offers Add/Delete-->
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header border-transparent">
                                <h3 class="card-title"><strong>Offers Add/Delete</strong></h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <button class="btn  btn-primary btn-sm" data-toggle="modal" data-target="#add_offer_modal">Add offer</button>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm text-center mb-4 mt-2" id="offers_table" style="width:100%">
                                        <thead>
                                            <tr class="thead-light">
                                                <th scope="col">Name</th>
                                                <th scope="col">Description</th>
                                                <th scope="col">Order Price</th>
                                                <th scope="col">Discount Amount</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="offers_table_tbody">
                                        </tbody>
                                    </table>
                                </div>

                                {{-- featured image modal --}}
                                <div class="modal fade" id="add_offer_modal" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="modal_title">Add new offer</h5>
                                                <button type="button" id="btn_offer_modal_close" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form class="" id="add_offer_form" methode="post" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="form-row">
                                                        <div class="form-group col-md-12" id="">
                                                            <label for="offer_name">Offer name</label>
                                                            <input type="text" class="form-control" id="offer_name" placeholder="Enter offer name" autocomplete="off">
                                                        </div>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="form-group col-md-12" id="">
                                                            <label for="offer_description">Offer description</label>
                                                            <textarea class="form-control" id="offer_description" placeholder="Enter offer description"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="form-group col-md-6" id="">
                                                            <label for="order_price">Order price</label>
                                                            <input type="number" class="form-control" id="order_price" placeholder="Enter order price" autocomplete="off">
                                                        </div>
                                                        <div class="form-group col-md-6" id="">
                                                            <label for="discount_amount">Discount amount</label>
                                                            <input type="number" class="form-control" id="discount_amount" placeholder="Enter discount amount" autocomplete="off">
                                                        </div>
                                                    </div>

                                                    <div class="form-row">
                                                        <div class="form-group col-md-12">
                                                            <div class="d-flex justify-content-end">
                                                                <button type="submit" class="btn btn-outline-primary btn-sm rounded-pill"><small><i class="fas fa-plus"></i></small>Create</button>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- /.card-body -->
                        </div><!-- /.card -->
                    </div>
                    <!--/Offers Add/Delete-->


                </div>

            </div>

        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        settings();
    });

</script>
@endsection
