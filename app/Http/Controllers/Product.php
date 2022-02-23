<?php

namespace App\Http\Controllers;

use App\Classes\Extras;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;

class Product extends Controller
{
    public function product_details_list(Request $req)
    {
        $product_images_list = array();
        $product_array = array();
        $fetched_product_list = DB::table('product')
            ->leftJoin('product_category', 'product_category.id', '=', 'product.category_id')
            ->leftJoin('product_sub_category', 'product_sub_category.id', '=', 'product.subcategory_id')
            ->leftJoin('brands', 'brands.id', '=', 'product.brand_id')
            ->leftJoin('unit_type', 'unit_type.id', '=', 'product.unit_type_id')
            ->select("product.id", "product.name", "product.desc", "product.upload_ids", "product.unit_type_id", "product.quantity", "product.mrp", "product.sell_price", "product.brand_id", "product.category_id", "product.subcategory_id", "product.discount", "product.min_qty", "product.max_qty", "product.is_in_stock", "product_category.name as product_category_name", "product_sub_category.name as product_sub_category_name", "brands.name as product_brand_name", "unit_type.name as product_unit_type", 'product.is_active', 'product.created_on')
            ->where('product.id', '=', $req->product_id)
            ->get();

        //WHERE IS ACTIVE , IS DELETED
        foreach ($fetched_product_list as $product) {
            $product->product_tags = DB::table('product_tag')
                ->select('product_tag.name as tag_name')
                ->where('product_tag.product_id', '=', $req->product_id)
                ->get();

            $product->images = DB::table("product_images")
                ->Join("uploads", "uploads.id", "=", "product_images.upload_id")
                ->select("uploads.*", "product_images.product_id")
                ->where("product_images.product_id", "=", $product->id)
                ->get();
            $product->order_details = DB::table('orders_details')
                ->leftJoin('product', 'product.id', '=', 'orders_details.product_id')
                ->leftjoin('orders', 'orders.id', '=', 'orders_details.order_id')
                ->select(DB::raw('sum(case when orders_details.product_id then orders_details.quantity else 0 end) as total_order,SUM(orders_details.per_qty_sell_price * orders_details.quantity) as total_order_amount'))
                ->where('product.id', '=', $product->id)
                ->where('orders.status_id', '=', 4)
                ->first();
        }
        echo json_encode($fetched_product_list);
    }
    public function fetch_product_list(Request $request)
    {
        $columns = array(
            0 => 'product.id',
            1 => 'images',
            2 => 'product.name',
            3 => 'product_category.name',
            4 => 'product_sub_category.name',
            5 => 'product.is_in_stock',
            6 => 'brands.name',
            7 => 'product.mrp',
            8 => 'product.desc',
            9 => 'product.sell_price',
            10 => 'action',
        );

        $totalData = DB::table('product')->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $ordering = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $products = DB::table('product')
            ->leftJoin('product_category', 'product_category.id', '=', 'product.category_id')
            ->leftJoin('product_sub_category', 'product_sub_category.id', '=', 'product.subcategory_id')
            ->leftJoin('brands', 'brands.id', '=', 'product.brand_id')
            ->leftJoin('unit_type', 'unit_type.id', '=', 'product.unit_type_id')
            ->select(
                "product.id",
                "product.name",
                "product.desc",
                "product.upload_ids",
                "product.unit_type_id",
                "product.quantity",
                "product.mrp",
                "product.sell_price",
                "product.brand_id",
                "product.category_id",
                "product.subcategory_id",
                "product.discount",
                "product.min_qty",
                "product.max_qty",
                "product.is_in_stock",
                "product.is_active",
                "product_category.name as product_category_name",
                "product_sub_category.name as product_sub_category_name",
                "brands.name as product_brand_name",
                "unit_type.name as product_unit_type"
            )
            ->where('product.is_active', '=', '1');

        if (!empty($request->input('search.value'))) {
            $search = $request->input('search.value');

            $products = $products
                ->where('product.name', 'LIKE', "%{$search}%")
                ->orwhere('product_category.name', 'LIKE', "%{$search}%")
                ->orwhere('product_sub_category.name', 'LIKE', "%{$search}%")
                ->orwhere('brands.name', 'LIKE', "%{$search}%");
        }
        if (!empty($request->filter_category)) {
            $search_filter_category = $request->filter_category;

            $products = $products
                ->where('product_category.name', 'LIKE', "%{$search_filter_category}%");
        }
        $totalFiltered = $products->count();
        $products = $products
            ->offset($start)
            ->limit($limit)
            ->orderBy($ordering, $dir)
            ->get();
        foreach ($products as $product) {
            $product->image = DB::table("product_images")
                ->Join("uploads", "uploads.id", "=", "product_images.upload_id")
                ->select("uploads.url")
                ->where("product_images.product_id", "=", $product->id)
                ->first();
        }
        $data = array();
        if (!empty($products)) {
            foreach ($products as $product) {
                if ($product->is_in_stock == 1) {
                    $avability = '<span class="text-green">In stock</span>';
                } else {
                    $avability = '<span class="text-danger">Not in stock</span>';
                }
                $nestedData['id'] = $product->id;
                $nestedData['product_image'] = $product->image ? '<img  src="' . $product->image->url . '" height="45px" width="45px" class="rounded-circle mt-1 mb-1" >' : '---';
                $nestedData['name'] = '<a class="text-link"  data-toggle="modal" data-target="#view_product_details_modal" id="btn_view_product" onclick="view_product_details(' . $product->id . ')"><strong>' . $product->name . '</strong></a>';
                $nestedData['category'] = $product->product_category_name;
                $nestedData['sub_category'] = $product->product_sub_category_name;
                $nestedData['is_in_stock'] = $avability;
                $nestedData['brand'] = $product->product_brand_name;
                $nestedData['mrp'] = $product->mrp;
                $nestedData['discount'] = $product->discount;
                $nestedData['selling_price'] = $product->sell_price;
                $action_btn = '<button class="mt-1 mb-1  ui icon button mini inverted primary rounded-circle" data-tooltip="View Product" data-variation="mini" data-position="top right"  data-toggle="modal" data-target="#view_product_details_modal" id="btn_view_product" onclick="view_product_details(' . $product->id . ')">
                    <i class="eye icon"></i>
                    </button>
                    <button class="mt-1 mb-1  ui icon button mini inverted orange rounded-circle" data-tooltip="Edit Product" data-variation="mini" data-position="top right"  data-toggle="modal" data-target="#edit_product_modal' . $product->id . '" id="btn_edit_product' . $product->id . '" onclick="edit_product_details(' . $product->id . ')">
                    <i class="pen icon"></i>
                    </button>';
                $nestedData['action'] = $action_btn;
                $data[] = $nestedData;
            }
        }

        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data,
        );

        return json_encode($json_data);
    }
    public function fetch_category()
    {
        $fetch_category = DB::table('product_category')
            ->where('is_active', '1')
            ->orderBy('name', 'ASC')
            ->get();
        echo json_encode($fetch_category);
    }
    public function fetch_unit_type()
    {
        $fetch_unit_type = DB::table('unit_type')
            ->where('is_deleted', '0')
            ->orderBy('name', 'ASC')
            ->get();
        echo json_encode($fetch_unit_type);
    }
    public function fetch_sub_category(Request $req)
    {
        $id = $req->category_id;
        $fetch_sub_category = DB::table('product_sub_category')
            ->where([['category_id', $id], ['is_active', '1']])
            ->orderBy('name', 'ASC')
            ->get();
        echo json_encode($fetch_sub_category);
    }
    public function fetch_brand(Request $req)
    {
        $search_text = $req->search_brand_text;
        $get_brand = DB::table('brands')
            ->Where('name', 'LIKE', "%{$search_text}%")
            ->where('is_active', '1')
            ->get();
        echo json_encode($get_brand);
    }
    public function update_product_image(Request $req)
    {
        $extras = new Extras();
        $images_name = array("0", "0", "0");
        $response = array();
        $images_id = array();
        $destinationPath = 'uploaded_images/'; // upload path
        if ($req->file('image1')) {
            $flag = 0;
            $exti = $req->file('image1')->getClientOriginalExtension();
            $flag = $exti == 'jpg' || $exti == 'JPG' || $exti == 'jpeg' || $exti == 'JPEG' || $exti == 'png' || $exti == 'PNG' ? 1 : 0;
            if (!$flag) {
                $response['success'] = 0;
                $response['message'] = "Unaccepted image type only accept png, jpeg, jpg";
                return json_encode($response);
            }
            $files = $req->file('image1');
            $imageName1 = $imageName1 = hash('adler32',rand(1000000000,9999999999)) ."image1_" . date('YmdHis') . "." . $files->getClientOriginalExtension();
            $files->move($destinationPath, $imageName1);
            // array_push($images_name, $imageName1);
            $images_name[0] = $imageName1;
        }
        if ($req->file('image2')) {
            $flag = 0;
            $exti = $req->file('image2')->getClientOriginalExtension();
            $flag = $exti == 'jpg' || $exti == 'JPG' || $exti == 'jpeg' || $exti == 'JPEG' || $exti == 'png' || $exti == 'PNG' ? 1 : 0;
            if (!$flag) {
                $response['success'] = 0;
                $response['message'] = "Unaccepted image type only accept png, jpeg, jpg";
                return json_encode($response);
            }
            $files2 = $req->file('image2');
            $imageName2 = $imageName1 = hash('adler32',rand(1000000000,9999999999)) ."_image2_" . date('YmdHis') . "." . $files2->getClientOriginalExtension();
            $files2->move($destinationPath, $imageName2);
            $images_name[1] = $imageName2;
        }
        if ($req->file('image3')) {
            $flag = 0;
            $exti = $req->file('image3')->getClientOriginalExtension();
            $flag = $exti == 'jpg' || $exti == 'JPG' || $exti == 'jpeg' || $exti == 'JPEG' || $exti == 'png' || $exti == 'PNG' ? 1 : 0;
            if (!$flag) {
                $response['success'] = 0;
                $response['message'] = "Unaccepted image type only accept png, jpeg, jpg";
                return json_encode($response);
            }
            $files3 = $req->file('image3');
            $imageName3 = $imageName1 = hash('adler32',rand(1000000000,9999999999)) ."_image3_" . date('YmdHis') . "." . $files3->getClientOriginalExtension();
            $files3->move($destinationPath, $imageName3);
            $images_name[2] = $imageName3;
        }
        $old_image_ids = array("0", "0", "0");
        $count = 0;
        foreach (json_decode($req->old_data) as $data) {
            $old_image_ids[$count] = $data->id;
            $count++;
        }
        $image_count = 0;
        foreach ($images_name as $image) {
            $url = url('/') . '/' . $destinationPath . $image;
            if ($old_image_ids[$image_count] != "0") {
                if ($image != "0") {
                    $update_images_id = DB::table('uploads')
                        ->where('uploads.id', '=', $old_image_ids[$image_count])
                        ->update(
                            [
                                'file_name' => $image,
                                'url' => $url,
                                'uploaded_by' => Session::get('user')['id'],
                                'is_deleted' => '0',

                            ]
                        );
                    array_push($images_id, "0");
                } else {
                    array_push($images_id, "0");
                }
            } else {
                if ($image != "0") {
                    $inserted_images_id = DB::table('uploads')
                        ->insertGetId(
                            [
                                'file_name' => $image,
                                'url' => $url,
                                'uploaded_by' => Session::get('user')['id'],
                                'is_deleted' => '0',

                            ]
                        );
                    array_push($images_id, $inserted_images_id);
                }
            }
            $image_count++;
        }
        foreach ($images_id as $image_id) {
            if ($image_id != "0") {
                $inserte_product_image = DB::table('product_images')
                    ->insertGetId(
                        [
                            'product_id' => $req->product_id,
                            'upload_id' => $image_id,

                        ]
                    );
            }
        }
        $response['success'] = 1;
        $response['message'] = "Product inserted successfully";
        return json_encode($response);
    }
    public function insert_product_details(Request $req)
    {
        $extras = new Extras();
        $images_name = array();
        $response = array();
        $images_id = array();
        $destinationPath = 'uploaded_images/'; // upload path
        if ($req->file('image1')) {
            $flag = 0;
            $exti = $req->file('image1')->getClientOriginalExtension();
            $flag = $exti == 'jpg' || $exti == 'JPG' || $exti == 'jpeg' || $exti == 'JPEG' || $exti == 'png' || $exti == 'PNG' ? 1 : 0;
            if (!$flag) {
                $response['success'] = 0;
                $response['message'] = "Unaccepted image type only accept png, jpeg, jpg";
                return json_encode($response);
            }
            $files = $req->file('image1');
            $imageName1 = $imageName1 = hash('adler32',rand(1000000000,9999999999)) ."_image1_" . date('YmdHis') . "." . $files->getClientOriginalExtension();
            $files->move($destinationPath, $imageName1);
            array_push($images_name, $imageName1);
        }
        if ($req->file('image2')) {
            $flag = 0;
            $exti = $req->file('image2')->getClientOriginalExtension();
            $flag = $exti == 'jpg' || $exti == 'JPG' || $exti == 'jpeg' || $exti == 'JPEG' || $exti == 'png' || $exti == 'PNG' ? 1 : 0;
            if (!$flag) {
                $response['success'] = 0;
                $response['message'] = "Unaccepted image type only accept png, jpeg, jpg";
                return json_encode($response);
            }
            $files2 = $req->file('image2');
            $imageName2 = $imageName1 = hash('adler32',rand(1000000000,9999999999)) ."_image2_" . date('YmdHis') . "." . $files2->getClientOriginalExtension();
            $files2->move($destinationPath, $imageName2);
            array_push($images_name, $imageName2);
        }
        if ($req->file('image3')) {
            $flag = 0;
            $exti = $req->file('image3')->getClientOriginalExtension();
            $flag = $exti == 'jpg' || $exti == 'JPG' || $exti == 'jpeg' || $exti == 'JPEG' || $exti == 'png' || $exti == 'PNG' ? 1 : 0;
            if (!$flag) {
                $response['success'] = 0;
                $response['message'] = "Unaccepted image type only accept png, jpeg, jpg";
                return json_encode($response);
            }
            $files3 = $req->file('image3');
            $imageName3 = $imageName1 = hash('adler32',rand(1000000000,9999999999)) ."_image3_" . date('YmdHis') . "." . $files3->getClientOriginalExtension();
            $files3->move($destinationPath, $imageName3);
            array_push($images_name, $imageName3);
        }
        $name_tag = explode(" ", $req->product_name);
        $category_name_tag = explode(" ", $req->category_name);
        $sub_category_name_tag = explode(" ", $req->sub_category_name);
        $brand_name_tag = explode(" ", $req->brand_name);
        $extra_tag = array(
            "0" => $req->category_name,
            "1" => $req->sub_category_name,
            "2" => $req->brand_name,
            "3" => $req->product_name,
        );
        $insert_product_details = DB::table('product')
            ->insertGetId(
                [
                    'name' => $req->product_name,
                    'category_id' => $req->category,
                    'subcategory_id' => $req->sub_category,
                    'brand_id' => $req->brand_id,
                    'mrp' => $req->mrp,
                    'discount' => $req->discount_percentage,
                    'sell_price' => $req->selling_price,
                    'min_qty' => $req->minimum_order,
                    'max_qty' => $req->maximum_order,
                    'desc' => $req->product_description,
                    'unit_type_id' => $req->unit_type,
                    'quantity' => $req->quantity,
                    'added_by' => Session::get('user')['id'],
                    'is_in_stock' => '1',
                    'created_on' => time(),
                ]
            );
        if ($insert_product_details) {
            $product_tag = explode(",", $req->product_tags);
            $all_product_tag = array_merge($name_tag, $category_name_tag, $sub_category_name_tag, $brand_name_tag, $extra_tag, $product_tag);
            foreach ($all_product_tag as $tag) {
                $check_availability = DB::table('product_tag')
                    ->where('product_id', '=', $insert_product_details)
                    ->where('name', '=', $tag)
                    ->first();
                if (!$check_availability) {
                    $tag = $extras->specialCharactersRemove($tag);
                    if ($tag != '') {
                        $insert_product_tags = DB::table('product_tag')
                            ->insert([
                                'name' => $tag,
                                'product_id' => $insert_product_details,
                            ]);
                    }
                }
            }
            foreach ($images_name as $image) {
                $url = url('/') . '/' . $destinationPath . $image;
                $inserted_images_id = DB::table('uploads')
                    ->insertGetId(
                        [
                            'file_name' => $image,
                            'url' => $url,
                            'uploaded_by' => Session::get('user')['id'],
                            'is_deleted' => '0',

                        ]
                    );
                array_push($images_id, $inserted_images_id);
            }
            foreach ($images_id as $image_id) {
                $inserte_product_image = DB::table('product_images')
                    ->insertGetId(
                        [
                            'product_id' => $insert_product_details,
                            'upload_id' => $image_id,

                        ]
                    );
            }
        }
        if ($insert_product_details) {
            $response['success'] = 1;
            $response['message'] = "Product inserted successfully";
            echo json_encode($response);
        } else {
            $response['success'] = 0;
            $response['message'] = "something went worng";
            echo json_encode($response);
        }
    }
    //===================edit product============================
    public function edit_product_details(Request $req)
    {
        $extras = new Extras();
        $flag = 0;
        $update_product_details = DB::table('product')
            ->where('product.id', '=', $req->product_id)
            ->update([
                'name' => $req->product_name,
                'category_id' => $req->category,
                'subcategory_id' => $req->sub_category,
                'brand_id' => $req->brand_id,
                'mrp' => $req->mrp,
                'discount' => $req->discount_percentage,
                'sell_price' => $req->selling_price,
                'min_qty' => $req->minimum_order,
                'max_qty' => $req->maximum_order,
                'desc' => $req->product_description,
                'unit_type_id' => $req->unit_type,
                'quantity' => $req->quantity,
                'is_in_stock' => $req->is_in_stock,
            ]);
        $product_tag = explode(",", $req->product_tags);
        $old_tag = explode(",", $req->old_tag_array);
        $check_tag_1 = array_diff($product_tag, $old_tag);
        $check_tag_2 = array_diff($old_tag, $product_tag);
        if (count(array_merge($check_tag_2, $check_tag_1)) > 0) {
            $flag = 1;
        }
        $delete_tags = DB::table('product_tag')
            ->where('product_tag.product_id', '=', $req->product_id)
            ->delete();
        $name_tag = explode(" ", $req->product_name);
        $category_name_tag = explode(" ", $req->category_name);
        $sub_category_name_tag = explode(" ", $req->sub_category_name);
        $brand_name_tag = explode(" ", $req->brand_name);
        $extra_tag = array(
            "0" => $req->category_name,
            "1" => $req->sub_category_name,
            "2" => $req->brand_name,
            "3" => $req->product_name,
        );
        $all_product_tag = array_merge($name_tag, $category_name_tag, $sub_category_name_tag, $brand_name_tag, $extra_tag, $product_tag);
        foreach ($all_product_tag as $tag) {
            $check_availability = DB::table('product_tag')
                ->where('product_id', '=', $req->product_id)
                ->where('name', '=', $tag)
                ->first();

            if (!$check_availability) {
                $tag = $extras->specialCharactersRemove($tag);
                if ($tag != '') {
                    $insert_product_tags = DB::table('product_tag')
                        ->insert([
                            'name' => $tag,
                            'product_id' => $req->product_id,

                        ]);
                }
            }
        }
        if ($flag != 0 || $update_product_details) {
            $response['success'] = 1;
            $response['flag'] = $flag;
            $response['message'] = "Product edited successfully.";
            return json_encode($response);
        } else {
            $response['success'] = 0;
            $response['message'] = "Please made a change.";
            return json_encode($response);
        }
    }

    //===================delete_product============================
    public function delete_product(Request $req)
    {
        return null;
        $update_product_status = DB::table('product')
            ->where('id', '=', $req->product_id)
            ->update([
                'is_active' => 0,
                'is_in_stock' => 0,
            ]);
        if ($update_product_status) {
            $response['success'] = 1;
            $response['message'] = "Product Deleted successfully";
            echo json_encode($response);
        } else {
            $response['success'] = 0;
            $response['message'] = "oops!something wrong please try again";
            echo json_encode($response);
        }
    }
}
