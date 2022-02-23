<?php

namespace App\Http\Controllers\Android;

use App\Classes\AndroidResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class WishlistController extends Controller
{
    public function addToWishlist(Request $request)
    {
        try {
            $androidResponse = new AndroidResponse();
            if (!$request->product_id || $request->product_id == "" || !$request->customer_id || $request->customer_id == "") {
                return response()->json([
                    'status' => $androidResponse->getStatus(0, 'Please provide all parameters!'),
                ]);
            }
            $is_exist = DB::table('wish_list')
                ->where('customer_id', '=', $request->customer_id)
                ->where('is_active', '=', 1)
                ->first();
            if ($is_exist) {
                $is_product_exist = DB::table('wish_list_products')
                    ->where('wish_list_id', '=', $is_exist->id)
                    ->where('product_id', '=', $request->product_id)
                    ->where('is_active', '=', 1)
                    ->first();
                if ($is_product_exist) {
                    return response()->json([
                        'status' => $androidResponse->getStatus(0, 'Product Already exist!'),
                    ]);
                }
                $inserted_wishlist = DB::table('wish_list_products')
                    ->insert(
                        [
                            'wish_list_id' => $is_exist->id,
                            'product_id' => $request->product_id,
                            'is_active' => 1,
                        ]
                    );
            } else {
                $inserted_wishlist = DB::table('wish_list')
                    ->insertGetId(
                        [
                            'customer_id' => $request->customer_id,
                            'is_active' => 1,
                        ]
                    );
                if ($inserted_wishlist) {
                    $insert = DB::table('wish_list_products')
                        ->insert(
                            [
                                'wish_list_id' => $inserted_wishlist,
                                'product_id' => $request->product_id,
                                'is_active' => 1,
                            ]
                        );
                }
            }
            return response()->json([
                'status' => $androidResponse->getStatus(1, 'Added to wishlist!'),
            ]);
        } catch (\Exception$e) {
            LOG::error("Error on Controller/Android/WishlistController.php " . $e);
            return response()->json([
                'status' => $androidResponse->getStatus(0, 'Oops. Something went wrong!'),
            ]);
        }

    }
    public function fetchWishlist(Request $request)
    {
        $androidResponse = new AndroidResponse();
        if (!$request->customer_id || $request->customer_id == "") {
            return response()->json([
                'status' => $androidResponse->getStatus(0, 'Please provide all parameters!'),
            ]);
        }
        try {
            $wishlists = DB::table('wish_list')
                ->where('wish_list.is_active', 1)
                ->where('wish_list_products.is_active', 1)
                ->where('wish_list.customer_id', $request->customer_id)
                ->leftJoin('wish_list_products', 'wish_list.id', '=', 'wish_list_products.wish_list_id')
                ->join('product', 'product.id', '=', 'wish_list_products.product_id')
                ->leftJoin('unit_type', 'unit_type.id', '=', 'product.unit_type_id')
                ->leftJoin('product_category', 'product_category.id', '=', 'product.category_id')
                ->leftJoin('product_sub_category', 'product_sub_category.id', '=', 'product.subcategory_id')
                ->leftJoin('brands', 'brands.id', '=', 'product.brand_id')
                ->select('product.id', 'product.name', 'brands.id as brand_id', 'brands.name as brand_name', 'product_sub_category.id as subcategory_id', 'product_sub_category.name as subcategory_name', 'product_category.id as category_id', 'product_category.name as catagory_name', 'product.desc', 'unit_type.name as unit_name', 'product.mrp', 'product.sell_price', 'product.discount',
                    'product.min_qty', 'product.max_qty', 'product.is_in_stock')
                ->get();
            if (count($wishlists)) {
                for($i = 0 ; $i < count($wishlists) ; $i++) {
                    $wishlists[$i]->images = DB::table("product_images")
                        ->Join("uploads", "uploads.id", "=", "product_images.upload_id")
                        ->select("uploads.url", "uploads.file_name")
                        ->where("product_images.product_id", "=", $wishlists[$i]->id)
                        ->get();
                }
                return response()->json([
                    'status' => $androidResponse->getStatus(1, 'wishlist Found!'),
                    'details' => $wishlists,
                ]);
            } else {
                return response()->json([
                    'status' => $androidResponse->getStatus(0, 'wishlist Not Found!'),
                ]);
            }
        } catch (\Exception$e) {
            LOG::error("Error on Controller/Android/WishlistController.php " . $e);
            return response()->json([
                'status' => $androidResponse->getStatus(0, 'Oops. Something went wrong!'),
            ]);
        }
    }
    public function removeFromWishlist(Request $request)
    {
        $androidResponse = new AndroidResponse();
        try {
            if (!$request->customer_id || $request->customer_id == "" || !$request->product_id || $request->product_id == "") {
                return response()->json([
                    'status' => $androidResponse->getStatus(0, 'Please provide all parameters!'),
                ]);
            }
            $wish_list = DB::table('wish_list')
                ->where('wish_list.is_active', 1)
                ->where('wish_list.customer_id', $request->customer_id)
                ->first();
            if (!$wish_list) {
                return response()->json([
                    'status' => $androidResponse->getStatus(0, 'You have no wishlist!'),
                ]);
            }
            $update_wishlist = DB::table('wish_list_products')
                ->where('wish_list_products.is_active', 1)
                ->where('wish_list_products.wish_list_id', $wish_list->id)
                ->where('wish_list_products.product_id', $request->product_id)
                ->update(['wish_list_products.is_active' => 0]);
            return response()->json([
                'status' => $androidResponse->getStatus(1, 'Deleted!'),
            ]);
        } catch (\Exception$e) {
            LOG::error("Error on Controller/Android/WishlistController.php " . $e);
            return response()->json([
                'status' => $androidResponse->getStatus(0, 'Oops. Something went wrong!'),
            ]);
        }
    }
}
