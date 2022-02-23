<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use App\Classes\AndroidResponse;
use Illuminate\Support\Facades\Log;
class Setting extends Controller
{
    //==========add-category========
    public function add_new_category(Request $req)
    {
        $response = array();
        $checking = DB::table("product_category")
            ->where("name", "=", $req->new_category_name)
            ->where("is_active", "=", 1)
            ->first();
        if ($checking) {
            $response['success'] = 0;
            $response['message'] = "Category is already available";
            echo json_encode($response);
        } else {
            $flag = 0;
            $exti = $req->file('category_image')->getClientOriginalExtension();
            $flag = $exti == 'jpg' || $exti == 'JPG' || $exti == 'jpeg' || $exti == 'JPEG' || $exti == 'png' || $exti == 'PNG' ? 1 : 0;
            if (!$flag) {
                $response['success'] = 0;
                $response['message'] = "Unaccepted image type only accept png, jpeg, jpg";
                echo json_encode($response);
            } else {
                $destinationPath = 'uploaded_images/'; // upload path
                if ($req->file('category_image')) {
                    $files = $req->file('category_image');
                    $category_imageName = "category_image_" . date('YmdHis') . "." . $files->getClientOriginalExtension();
                    $files->move($destinationPath, $category_imageName);
                }
                $url = url('/') . '/' . $destinationPath . $category_imageName;
                $inserted_image_id = DB::table('uploads')
                    ->insertGetId(
                        [
                            'file_name' => $category_imageName,
                            'url' => $url,
                            'uploaded_by' => Session::get('user')['id'],
                            'is_deleted' => '0',

                        ]
                    );
                $insert_new_category = DB::table('product_category')
                    ->insert([
                        'name' => $req->new_category_name,
                        'upload_id' => $inserted_image_id,
                        'added_by' => Session::get('user')['id'],
                        'is_active' => '1',
                        'added_on' => time(),
                    ]);
                if ($insert_new_category) {
                    $response['success'] = 1;
                    $response['message'] = "Category created successfully";
                    echo json_encode($response);
                } else {
                    $response['success'] = 0;
                    $response['message'] = "something went worng";
                    echo json_encode($response);
                }
            }
        }
    }
    //=========add-sub-category======
    public function add_new_sub_category(Request $req)
    {
        $response = array();
        $checking = DB::table("product_sub_category")
            ->where("name", "=", $req->new_sub_category_name)
            ->where("category_id", "=", $req->selected_category)
            ->where("is_active", "=", 1)
            ->first();
        if ($checking) {
            $response['success'] = 0;
            $response['message'] = "Sub-category is already available";
            echo json_encode($response);
        } else {
            $flag = 0;
            $exti = $req->file('sub_category_image')->getClientOriginalExtension();
            $flag = $exti == 'jpg' || $exti == 'JPG' || $exti == 'jpeg' || $exti == 'JPEG' || $exti == 'png' || $exti == 'PNG' ? 1 : 0;
            if (!$flag) {
                $response['success'] = 0;
                $response['message'] = "Unaccepted image type only accept png, jpeg, jpg";
                echo json_encode($response);
            } else {
                $destinationPath = 'uploaded_images/'; // upload path
                if ($req->file('sub_category_image')) {
                    $files = $req->file('sub_category_image');
                    $sub_category_imageName = "sub_category_image_" . date('YmdHis') . "." . $files->getClientOriginalExtension();
                    $files->move($destinationPath, $sub_category_imageName);
                }
                $url = url('/') . '/' . $destinationPath . $sub_category_imageName;
                $inserted_image_id = DB::table('uploads')
                    ->insertGetId(
                        [
                            'file_name' => $sub_category_imageName,
                            'url' => $url,
                            'uploaded_by' => Session::get('user')['id'],
                            'is_deleted' => '0',

                        ]
                    );
                $insert_new_sub_category = DB::table('product_sub_category')
                    ->insert([
                        'name' => $req->new_sub_category_name,
                        'category_id' => $req->selected_category,
                        'upload_id' => $inserted_image_id,
                        'added_by' => Session::get('user')['id'],
                        'is_active' => '1',
                        'added_on' => time(),
                    ]);
                if ($insert_new_sub_category) {
                    $response['success'] = 1;
                    $response['message'] = "Sub category created successfully";
                    echo json_encode($response);
                } else {
                    $response['success'] = 0;
                    $response['message'] = "something went worng";
                    echo json_encode($response);
                }
            }
        }
    }
    //==========add-brands========
    public function add_new_brand(Request $req)
    {
        $response = array();
        $checking = DB::table("brands")
            ->where("name", "=", $req->new_brand_name)
            ->where("is_active", "=", 1)
            ->first();
        if ($checking) {
            $response['success'] = 0;
            $response['message'] = "Brands is already available";
            echo json_encode($response);
        } else {
            $flag = 0;
            $exti = $req->file('brand_image')->getClientOriginalExtension();
            $flag = $exti == 'jpg' || $exti == 'JPG' || $exti == 'jpeg' || $exti == 'JPEG' || $exti == 'png' || $exti == 'PNG' ? 1 : 0;
            if (!$flag) {
                $response['success'] = 0;
                $response['message'] = "Unaccepted image type only accept png, jpeg, jpg";
                echo json_encode($response);
            } else {
                $destinationPath = 'uploaded_images/'; // upload pathS
                if ($req->file('brand_image')) {
                    $files = $req->file('brand_image');
                    $brand_imageName = "brand_image" . date('YmdHis') . "." . $files->getClientOriginalExtension();
                    $files->move($destinationPath, $brand_imageName);
                }
                $url = url('/') . '/' . $destinationPath . $brand_imageName;
                $inserted_brand_image_id = DB::table('uploads')
                    ->insertGetId(
                        [
                            'file_name' => $brand_imageName,
                            'url' => $url,
                            'uploaded_by' => Session::get('user')['id'],
                            'is_deleted' => '0',

                        ]
                    );
                $insert_new_brand = DB::table('brands')
                    ->insert([
                        'name' => $req->new_brand_name,
                        'is_active' => '1',
                        'added_on' => time(),
                        'upload_id' => $inserted_brand_image_id,
                    ]);
                if ($insert_new_brand) {
                    $response['success'] = 1;
                    $response['message'] = "Brand created successfully";
                    echo json_encode($response);
                } else {
                    $response['success'] = 0;
                    $response['message'] = "something went worng";
                    echo json_encode($response);
                }
            }
        }
    }
    //==========add-unit_type========
    public function add_new_unit_type(Request $req)
    {
        $response = array();
        $checking = DB::table("unit_type")
            ->where("name", "=", $req->new_unit_type_name)
            ->where("is_deleted", "=", 0)
            ->first();
        if ($checking) {
            $response['success'] = 0;
            $response['message'] = "Unit type is already available";
            echo json_encode($response);
        } else {
            $insert_new_unit_type = DB::table('unit_type')
                ->insert([
                    'name' => $req->new_unit_type_name,
                    'is_deleted' => '0',
                ]);
            if ($insert_new_unit_type) {
                $response['success'] = 1;
                $response['message'] = "Unit type created successfully";
                echo json_encode($response);
            } else {
                $response['success'] = 0;
                $response['message'] = "something went worng";
                echo json_encode($response);
            }
        }
    }
    //==========add-new_cancellation_reason========
    public function add_new_cancellation_reason(Request $req)
    {
        $response = array();
        $checking = DB::table("cancellation_reason")
            ->where("reason", "=", $req->new_cancellation_reason)
            ->first();
        if ($checking) {
            $response['success'] = 0;
            $response['message'] = "Reason is already available";
            echo json_encode($response);
        } else {
            $insert_new_cancellation_reason = DB::table('cancellation_reason')
                ->insert([
                    'reason' => $req->new_cancellation_reason,
                ]);
            if ($insert_new_cancellation_reason) {
                $response['success'] = 1;
                $response['message'] = "Reason added successfully";
                echo json_encode($response);
            } else {
                $response['success'] = 0;
                $response['message'] = "something went worng";
                echo json_encode($response);
            }
        }
    }
    //==========add-pincode========
    public function add_new_pincode(Request $req)
    {
        $response = array();
        $checking = DB::table("pin_code")
            ->where("pincode", "=", $req->new_pincode)
            ->first();
        if ($checking) {
            $response['success'] = 0;
            $response['message'] = "Pincode already present";
            echo json_encode($response);
        } else {
            $insert_new_pincode = DB::table('pin_code')
                ->insert([
                    'pincode' => $req->new_pincode,
                    'is_active' => '1',
                ]);
            if ($insert_new_pincode) {
                $response['success'] = 1;
                $response['message'] = "Pincode added successfully";
                echo json_encode($response);
            } else {
                $response['success'] = 0;
                $response['message'] = "something went worng";
                echo json_encode($response);
            }
        }
    }
    //==========add-village========
    public function add_new_village(Request $req)
    {
        $response = array();
        $checking = DB::table("pin_wise_village")
            ->where("pin_code", "=", $req->pincode)
            ->where("village_name", "=", $req->new_village_name)
            ->first();
        if ($checking) {
            $response['success'] = 0;
            $response['message'] = "village name already present";
            echo json_encode($response);
        } else {
            $insert_new_village = DB::table('pin_wise_village')
                ->insert([
                    'pin_code' => $req->pincode,
                    'village_name' => $req->new_village_name,
                    'is_active' => '1',
                ]);
            if ($insert_new_village) {
                $response['success'] = 1;
                $response['message'] = "Village added successfully";
                echo json_encode($response);
            } else {
                $response['success'] = 0;
                $response['message'] = "something went worng";
                echo json_encode($response);
            }
        }
    }
    //=========fetch-category===========
    public function fetch_all_categories()
    {
        $response = array();

        $fetch_all_categories = DB::table('product_category')
            ->leftjoin("uploads", "uploads.id", "=", "product_category.upload_id")
            ->select("*", "uploads.id as image_id", "product_category.id as category_id")
        // ->orderBy('product_category.is_active','desc')
            ->where('product_category.is_active', '=', 1)
            ->get();
        if ($fetch_all_categories) {
            return json_encode($fetch_all_categories);
        }
    }
    //============fetch-sub_category=====================
    public function fetch_all_sub_categories()
    {
        $response = array();

        $fetch_all_sub_categories = DB::table('product_sub_category')
            ->leftJoin('product_category', 'product_category.id', '=', 'product_sub_category.category_id')
            ->leftjoin("uploads", "uploads.id", "=", "product_sub_category.upload_id")
            ->select("*", "product_sub_category.id as sub_category_id", "product_sub_category.name as sub_category_name", "product_category.name as category_name", "product_sub_category.is_active as sub_category_status", "product_category.is_active as category_status")
        // ->orderBy('product_sub_category.is_active','desc')
            ->where('product_sub_category.is_active', '=', 1)
            ->get();
        if ($fetch_all_sub_categories) {
            return json_encode($fetch_all_sub_categories);
        }
    }
    //=========fetch-brands===========
    public function fetch_all_brands()
    {
        $response = array();

        $fetch_all_brands = DB::table('brands')
            ->leftjoin('uploads', 'uploads.id', '=', 'brands.upload_id')
            ->select('brands.id', 'brands.name', 'brands.added_by', 'brands.is_active', 'brands.added_on', 'uploads.url')
        // ->orderBy('brands.is_active','desc')
            ->where('brands.is_active', '=', 1)
            ->get();
        if ($fetch_all_brands) {
            return json_encode($fetch_all_brands);
        }
    }
    //=========fetch-unit_type===========
    public function fetch_all_unit_type()
    {
        $response = array();

        $fetch_all_unit_type = DB::table('unit_type')
            ->select("*")
        // ->orderBy('unit_type.is_deleted','desc')
            ->where('unit_type.is_deleted', '=', 0)
            ->get();
        if ($fetch_all_unit_type) {
            return json_encode($fetch_all_unit_type);
        }
    }
    //=========fetch-unit_type===========
    public function fetch_all_cancellation_reason()
    {
        $response = array();

        $fetch_all_cancellation_reason = DB::table('cancellation_reason')
            ->select("*")
            ->get();
        if ($fetch_all_cancellation_reason) {
            return json_encode($fetch_all_cancellation_reason);
        }
    }
    //=========fetch-pincode===========
    public function fetch_all_pincode()
    {
        $response = array();

        $fetch_all_pincodes = DB::table('pin_code')
            ->select("*")
            ->get();
        if ($fetch_all_pincodes) {
            return json_encode($fetch_all_pincodes);
        }
    }
    //=========fetch-village===========
    public function fetch_all_village()
    {
        $response = array();
        $fetch_all_villages = DB::table('pin_wise_village')
            ->leftJoin('pin_code', 'pin_code.id', '=', 'pin_wise_village.pin_code')
            ->select('pin_code.is_active as pincode_status', 'pin_wise_village.id as village_id', 'pin_wise_village.village_name', 'pin_wise_village.pin_code as pin_code_id', 'pin_wise_village.is_active as village_is_active', 'pin_code.pincode as pincode_number')
            ->get();
        if ($fetch_all_villages) {
            return json_encode($fetch_all_villages);
        }
    }
    //=========delete_category===========
    public function delete_category(Request $req)
    {
        $checking = DB::table("product")
            ->where("category_id", "=", $req->category_id)
            ->where("product.is_active", "=", 1)
            ->first();
        $checking2 = DB::table("product_sub_category")
            ->where("category_id", "=", $req->category_id)
            ->where("product_sub_category.is_active", "=", 1)
            ->first();

        if ($checking || $checking2) {
            $response['success'] = 0;
            $response['message'] = "Category is in use, can't delete.";
            echo json_encode($response);
        } else {
            $deleting = DB::table('product_category')
                ->where('id', '=', $req->category_id)
                ->update([
                    'is_active' => 0,
                ]);
            if ($deleting) {
                $response['success'] = 1;
                $response['message'] = "Category deleted successfully";
                echo json_encode($response);
            } else {
                $response['success'] = 0;
                $response['message'] = "something wrong try again";
                echo json_encode($response);
            }
        }
    }
    //=========delete_sub_category===========
    public function delete_sub_category(Request $req)
    {
        $checking = DB::table("product")
            ->where("category_id", "=", $req->sub_category_id)
            ->where("product.is_active", "=", 1)
            ->first();
        if ($checking) {
            $response['success'] = 0;
            $response['message'] = "Sub-category is in use, can't delete.";
            echo json_encode($response);
        } else {
            $deleting = DB::table('product_sub_category')
                ->where('id', '=', $req->sub_category_id)
                ->update([
                    'is_active' => 0,
                ]);
            if ($deleting) {
                $response['success'] = 1;
                $response['message'] = "Sub-category deleted successfully";
                echo json_encode($response);
            } else {
                $response['success'] = 0;
                $response['message'] = "something wrong try again";
                echo json_encode($response);
            }
        }
    }
    //=========delete_brand===========
    public function delete_brand(Request $req)
    {
        $checking = DB::table("product")
            ->where("brand_id", "=", $req->brand_id)
            ->where("product.is_active", "=", 1)
            ->first();
        if ($checking) {
            $response['success'] = 0;
            $response['message'] = "Brand is in use, can't delete.";
            echo json_encode($response);
        } else {
            $deleting = DB::table('brands')
                ->where('id', '=', $req->brand_id)
                ->update([
                    'is_active' => 0,
                ]);
            if ($deleting) {
                $response['success'] = 1;
                $response['message'] = "Brands deleted successfully";
                echo json_encode($response);
            } else {
                $response['success'] = 0;
                $response['message'] = "something wrong try again";
                echo json_encode($response);
            }
        }
    }
    //=========delete_unit_type===========
    public function delete_unit_type(Request $req)
    {
        $checking = DB::table("product")
            ->where("unit_type_id", "=", $req->unit_type_id)
            ->where("product.is_active", "=", 1)
            ->first();
        if ($checking) {
            $response['success'] = 0;
            $response['message'] = "Unit type is in use, can't delete.";
            echo json_encode($response);
        } else {
            $deleting = DB::table('unit_type')
                ->where('id', '=', $req->unit_type_id)
                ->update([
                    'is_deleted' => 1,
                ]);
            if ($deleting) {
                $response['success'] = 1;
                $response['message'] = "Brands deleted successfully";
                echo json_encode($response);
            } else {
                $response['success'] = 0;
                $response['message'] = "something wrong try again";
                echo json_encode($response);
            }
        }
    }

    //update_village
    public function update_village_status(Request $req)
    {
        $update_village_status = DB::table('pin_wise_village')
            ->where('id', '=', $req->village_id)
            ->update([
                'is_active' => $req->updated_status,
            ]);
        if ($update_village_status) {
            $response['success'] = 1;
            $response['message'] = "Village  status updated successfully";
            echo json_encode($response);
        } else {
            $response['success'] = 0;
            $response['message'] = "Something went wrong please refresh page";
            echo json_encode($response);
        }
    }

    //update_pincode
    public function update_pincode_status(Request $req)
    {
        $update_pincode_status = DB::table('pin_code')
            ->where('id', '=', $req->pincode_id)
            ->update([
                'is_active' => $req->updated_status,
            ]);
        if ($update_pincode_status) {

            $fetch_all_village = DB::table('pin_wise_village')
                ->where('pin_code', '=', $req->pincode_id)
                ->update([
                    'is_active' => $req->updated_status,
                ]);
            if ($fetch_all_village) {
                $response['success'] = 1;
                $response['message'] = "Pincode  status updated successfully";
                echo json_encode($response);
            } else {
                $response['success'] = 0;
                $response['message'] = "All villages already disabled";
                echo json_encode($response);
            }

        } else {
            $response['success'] = 0;
            $response['message'] = "Something went wrong please refresh page";
            echo json_encode($response);
        }
    }

    //fetch_action_data
    public function fetch_action_data(Request $req)
    {
        if ($req->selected_action == 'product') {
            $fetch_action_data = DB::table('product')
                ->select('product.name', 'product.id')
                ->get();
            return $fetch_action_data;
        } else if ($req->selected_action == 'category') {
            $fetch_action_data = DB::table('product_category')
                ->select('product_category.name', 'product_category.id')
                ->get();
            return $fetch_action_data;
        } else if ($req->selected_action == 'subcategory') {
            $fetch_action_data = DB::table('product_sub_category')
                ->select('product_sub_category.name', 'product_sub_category.id')
                ->get();
            return $fetch_action_data;
        } else if ($req->selected_action == 'brands') {
            $fetch_action_data = DB::table('brands')
                ->select('brands.name', 'brands.id')
                ->get();
            return $fetch_action_data;
        }

    }

    //==========add_featured_image========
    public function add_featured_image(Request $req)
    {
        $response = array();
        $image_size = getimagesize($req->file('featured_image'));
        $image_width = $image_size[0];
        $image_height = $image_size[1];
        if ($image_width != "1200" && $image_height != "628") {
            $response['success'] = 0;
            $response['message'] = "Image resolution should be 1200 X 628 Pixel";
            return json_encode($response);

        }
        $flag = 0;
        $exti = $req->file('featured_image')->getClientOriginalExtension();
        $flag = $exti == 'jpg' || $exti == 'JPG' || $exti == 'jpeg' || $exti == 'JPEG' || $exti == 'png' || $exti == 'PNG' ? 1 : 0;
        if (!$flag) {
            $response['success'] = 0;
            $response['message'] = "Unaccepted image type only accept png, jpeg, jpg";
            return json_encode($response);
        }
        $checking_max = DB::table("android_view_flipper")
            ->select(DB::raw('count(android_view_flipper.id) as total_featured_images'))
            ->first();
        if ($checking_max->total_featured_images < 6) {
            $destinationPath = 'uploaded_images/'; // upload path
            if ($req->file('featured_image')) {
                $files = $req->file('featured_image');
                $featured_imageName = "featured_image" . date('YmdHis') . "." . $files->getClientOriginalExtension();
                $files->move($destinationPath, $featured_imageName);
            }
            $url = url('/') . '/' . $destinationPath . $featured_imageName;
            $inserted_image_id = DB::table('uploads')
                ->insertGetId(
                    [
                        'file_name' => $featured_imageName,
                        'url' => $url,
                        'uploaded_by' => Session::get('user')['id'],
                        'is_deleted' => '0',

                    ]
                );
            $insert_featured_image = DB::table('android_view_flipper')
                ->insert([
                    'upload_id' => $inserted_image_id,
                    'action' => $req->selected_action,
                    'action_keyword' => $req->featured_image_action_keyword,
                ]);
            if ($insert_featured_image) {
                $response['success'] = 1;
                $response['message'] = "featured image successfully";
                echo json_encode($response);
            } else {
                $response['success'] = 0;
                $response['message'] = "something went worng";
                echo json_encode($response);
            }
        } else {
            $response['success'] = 0;
            $response['message'] = "Maximum Six images are allowed";
            echo json_encode($response);
        }
    }
    public function fetch_product(Request $req)
    {
        $search_text = $req->search_product_text;
        $get_product = DB::table('product')
            ->select('product.id', 'product.name')
            ->Where('name', 'LIKE', "%{$search_text}%")
            ->get();
        echo json_encode($get_product);
    }
    //==========fetch_delivery_charges========
    public function fetch_delivery_charges(Request $req)
    {
        $fetch_delivery_charges = DB::table("delivery_charge")
            ->select('delivery_charge.id', 'delivery_charge.charge', 'delivery_charge.up_to')
            ->get();
        return json_encode($fetch_delivery_charges);
    }
    //modify_delivery_charge
    public function modify_delivery_charge(Request $req)
    {
        $response = array();
        $modify_delivery_charge = DB::table("delivery_charge")
            ->where('delivery_charge.id', '=', $req->id)
            ->update(['delivery_charge.charge' => $req->delivery_charge, 'delivery_charge.up_to' => $req->amount_up_to]);
        if ($modify_delivery_charge) {
            $response['success'] = 1;
            $response['message'] = "Modify successfully";
            echo json_encode($response);
        } else {
            $response['success'] = 0;
            $response['message'] = "something went worng";
            echo json_encode($response);
        }
    }
    //fetch_featured_images
    public function fetch_featured_images()
    {
        $fetch_featured_images = DB::table("android_view_flipper")
            ->leftjoin('uploads', 'uploads.id', '=', 'android_view_flipper.upload_id')
            ->select('android_view_flipper.id', 'android_view_flipper.action', 'uploads.url', 'android_view_flipper.upload_id')
            ->get();
        return json_encode($fetch_featured_images);
    }
    //delete_featured_image
    public function delete_featured_image(Request $req)
    {
        $response = array();
        $delete_featured_image = DB::table("android_view_flipper")
            ->where('android_view_flipper.id', '=', $req->id)
            ->delete();
        $delete_uploaded_image = DB::table("uploads")
            ->where('uploads.id', '=', $req->upload_id)
            ->delete();
        if ($delete_uploaded_image) {
            $response['success'] = 1;
            $response['message'] = "Featured image deleted successfully";
            echo json_encode($response);
        } else {
            $response['success'] = 0;
            $response['message'] = "something went worng";
            echo json_encode($response);
        }
    }

    //add_offer
    public function add_offer(Request $req)
    {
        $response = array();
        $add_offer = DB::table("discount_coupon")
            ->insert([
                'name' => $req->offer_name,
                'desc' => $req->offer_description,
                'order_price' => $req->order_price,
                'discount_amount' => $req->discount_amount,
            ]);
        if ($add_offer) {
            $response['success'] = 1;
            $response['message'] = "Offer added successfully";
            echo json_encode($response);
        } else {
            $response['success'] = 0;
            $response['message'] = "something went worng";
            echo json_encode($response);
        }
    }

    //fetch_offers
    public function fetch_offers()
    {
        $fetch_offers = DB::table("discount_coupon")
            ->select('discount_coupon.id', 'discount_coupon.name', 'discount_coupon.desc', 'discount_coupon.order_price', 'discount_coupon.discount_amount')
            ->get();
        return json_encode($fetch_offers);
    }

    //delete_offer
    public function delete_offer(Request $req)
    {
        $response = array();
        $delete_offer = DB::table("discount_coupon")
            ->where('discount_coupon.id', '=', $req->id)
            ->delete();
        if ($delete_offer) {
            $response['success'] = 1;
            $response['message'] = "Offer deleted successfully";
            echo json_encode($response);
        } else {
            $response['success'] = 0;
            $response['message'] = "something went worng";
            echo json_encode($response);
        }
    }
    //==========fetch_minimum_order========
    public function fetch_minimum_order(Request $request)
    {
        try {
            $androidResponse = new AndroidResponse();
            $fetch_minimum_order = DB::table("minimum_order")
                ->select('minimum_order.id', 'minimum_order.minimum_order_amount', 'minimum_order.last_updated_on')
                ->first();
            if ($fetch_minimum_order) {
                return response()->json([
                    'status' => $androidResponse->getStatus(1, 'Data found'),
                    'fetched_minimum_order' => $fetch_minimum_order,
                ]);
            } else {
                return response()->json([
                    'status' => $androidResponse->getStatus(0, 'No data found'),
                ]);
            }

        } catch (\Exception $e) {
            LOG::error("Error on Controller/Android/OrderController.php " . $e);
            return response()->json([
                'status' => $androidResponse->getStatus(0, 'Oops. Something went wrong!'),
            ]);
        }
    }
    //modify_minimum_order
    public function modify_minimum_order(Request $req)
    {
        $response = array();
        $modify_minimum_order = DB::table("minimum_order")
            ->where('minimum_order.id', '=', $req->id)
            ->update(['minimum_order.minimum_order_amount' => $req->minimum_order, 'minimum_order.last_updated_on' => time()]);
        if ($modify_minimum_order) {
            $response['success'] = 1;
            $response['message'] = "Modify successfully";
            return json_encode($response);
        } else {
            $response['success'] = 0;
            $response['message'] = "something went worng";
            return json_encode($response);
        }
    }
}
