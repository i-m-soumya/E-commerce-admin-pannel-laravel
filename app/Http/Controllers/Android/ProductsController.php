<?php

namespace App\Http\Controllers\Android;

use App\Classes\AndroidResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class ProductsController extends Controller
{

    public function fetch_categories(Request $request)
    {
        $androidResponse = new AndroidResponse();
        try {
            $categories = DB::table('product_category')
                ->where('is_active', 1)
                ->leftJoin('uploads', 'uploads.id', '=', 'product_category.upload_id')
                ->select('product_category.id', 'product_category.name', 'product_category.added_by', 'product_category.added_on', 'product_category.is_active', 'uploads.url')
                ->orderBy('order', 'asc');
            $paginatedCategories = $categories->paginate($request->per_page ? $request->per_page : 10);
            if ($categories) {
                return response()->json([
                    'status' => $androidResponse->getStatus(1, 'Categories Found.'),
                    'categories' => $paginatedCategories->toArray()['data'],
                    'total' => $paginatedCategories->total(),
                    'per_page' => $paginatedCategories->perPage(),
                    'current_page' => $paginatedCategories->currentPage(),
                    'total_page' => $paginatedCategories->lastPage(),
                    'from' => $paginatedCategories->firstItem(),
                    'to' => $paginatedCategories->lastItem(),
                ]);
            } else {
                return response()->json([
                    'status' => $androidResponse->getStatus(0, 'No Categories Found!'),
                ]);
            }
        } catch (\Exception $e) {
            LOG::error("Error on Controller/Android/ProductsConroller.php " . $e);
            return response()->json([
                'status' => $androidResponse->getStatus(0, 'Oops. Something went wrong!'),
            ]);
        }
    }

    public function fetch_brands(Request $request)
    {
        $androidResponse = new AndroidResponse();
        try {
            $brands = DB::table('brands')
                ->where('is_active', 1)
                ->leftJoin('uploads', 'uploads.id', '=', 'brands.upload_id')
                ->select('brands.id', 'brands.name', 'brands.added_on', 'brands.is_active', 'uploads.url');
                $paginatedBrands = $brands->paginate($request->per_page ? $request->per_page : 10);
            if ($brands) {
                return response()->json([
                    'status' => $androidResponse->getStatus(1, 'Brands Found!'),
                    'brands' => $paginatedBrands->toArray()['data'],
                    'total' => $paginatedBrands->total(),
                    'per_page' => $paginatedBrands->perPage(),
                    'current_page' => $paginatedBrands->currentPage(),
                    'total_page' => $paginatedBrands->lastPage(),
                    'from' => $paginatedBrands->firstItem(),
                    'to' => $paginatedBrands->lastItem(),
                ]);
            } else {
                return response()->json([
                    'status' => $androidResponse->getStatus(0, 'No Brands Found!'),
                ]);
            }
        } catch (\Exception $e) {
            LOG::error("Error on Controller/Android/ProductsConroller.php " . $e);
            return response()->json([
                'status' => $androidResponse->getStatus(0, 'Oops. Something went wrong!'),
            ]);
        }
    }

    public function fetch_sub_categories(Request $request)
    {
        $androidResponse = new AndroidResponse();
        if (!$request->category) {
            return response()->json([
                'status' => $androidResponse->getStatus(0, 'Please input a valid category!'),
            ]);
        }
        try {
            $sub_categories = DB::table('product_sub_category')
                ->where('category_id', $request->category)
                ->where('is_active', 1)
                ->leftJoin('uploads', 'uploads.id', '=', 'product_sub_category.upload_id')
                ->select('product_sub_category.id', 'product_sub_category.category_id', 'product_sub_category.name', 'product_sub_category.is_active', 'uploads.url')
                ->get();
            if (count($sub_categories)) {
                return response()->json([
                    'status' => $androidResponse->getStatus(1, 'Sub category found!'),
                    'sub_categories' => $sub_categories,
                ]);
            } else {
                return response()->json([
                    'status' => $androidResponse->getStatus(0, 'No Subcategory Found!'),
                ]);
            }
        } catch (\Exception$e) {
            LOG::error("Error on Controller/Android/ProductsConroller.php " . $e);
            return response()->json([
                'status' => $androidResponse->getStatus(0, 'Oops. Something went wrong!'),
            ]);
        }
    }

    public function fetch_featured_images(Request $request)
    {
        $androidResponse = new AndroidResponse();
        try {
            $featured_images = DB::table('android_view_flipper')
                ->leftJoin('uploads', 'uploads.id', '=', 'android_view_flipper.upload_id')
                ->select('android_view_flipper.id', 'android_view_flipper.action', 'android_view_flipper.action_keyword', 'uploads.url')
                ->get();
            if (count($featured_images)) {
                return response()->json([
                    'status' => $androidResponse->getStatus(1, 'Featured Image Found!'),
                    'featured_images' => $featured_images,
                ]);
            } else {
                return response()->json([
                    'status' => $androidResponse->getStatus(0, 'No Featured Image Found!'),
                ]);
            }
        } catch (\Exception$e) {
            LOG::error("Error on Controller/Android/ProductsConroller.php " . $e);
            return response()->json([
                'status' => $androidResponse->getStatus(0, 'Oops. Something went wrong!'),
            ]);
        }
    }

    public function fetch_popular_distinct_tags(Request $request)
    {
        $androidResponse = new AndroidResponse();
        try {
            $tags = DB::table('product_tag')
                ->select('id', 'name', 'product_id')
                ->get();
            if (count($tags)) {
                return response()->json([
                    'status' => $androidResponse->getStatus(1, 'Tags Found!'),
                    'tags' => $tags,
                ]);
            } else {
                return response()->json([
                    'status' => $androidResponse->getStatus(0, 'No Tags Found!'),
                ]);
            }
        } catch (\Exception$e) {
            LOG::error("Error on Controller/Android/ProductsConroller.php " . $e);
            return response()->json([
                'status' => $androidResponse->getStatus(0, 'Oops. Something went wrong!'),
            ]);
        }
    }
    public function fetch_product_details(Request $request)
    {
        $androidResponse = new AndroidResponse();
        try {
            if (!$request->product_id || $request->product_id == "" || !$request->customer_id || $request->customer_id == "") {
                return response()->json([
                    'status' => $androidResponse->getStatus(0, 'Please provide all parameters!'),
                ]);
            }
            $product_details = DB::table('product')
                ->where('product.id', $request->product_id)
                ->leftJoin('unit_type', 'unit_type.id', '=', 'product.unit_type_id')
                ->select(
                    'product.id', 'product.name', 'product.desc', 'unit_type.name as unit_name', 'product.quantity',
                    'product.mrp', 'product.sell_price', 'product.brand_id', 'product.category_id',
                    'product.subcategory_id', 'product.discount', 'product.min_qty', 'product.max_qty',
                    'product.is_in_stock'
                )
                ->get();
            if (!$product_details) {
                return response()->json([
                    'status' => $androidResponse->getStatus(0, 'No Product Found!'),
                ]);
            }
            $product_details = $product_details[0];
            $is_in_cart = DB::table("cart")
                ->where("cart.customer_id", "=", $request->customer_id)
                ->where('cart.is_active', 1)
                ->where('cart_products.is_active', 1)
                ->where("cart_products.product_id", "=", $request->product_id)
                ->join('cart_products', 'cart_products.cart_id', '=', 'cart.id')
                ->get();
            $product_details->is_in_cart = count($is_in_cart) ? 1 : 0;
            $is_in_wish_list = DB::table("wish_list")
                ->where("wish_list.customer_id", "=", $request->customer_id)
                ->where('wish_list.is_active', 1)
                ->where('wish_list_products.is_active', 1)
                ->where("wish_list_products.product_id", "=", $request->product_id)
                ->join('wish_list_products', 'wish_list_products.wish_list_id', '=', 'wish_list.id')
                ->get();
            $product_details->is_in_wish_list = count($is_in_wish_list) ? 1 : 0;
            $product_details->images = DB::table("product_images")
                ->Join("uploads", "uploads.id", "=", "product_images.upload_id")
                ->select("uploads.*", "product_images.product_id")
                ->where("product_images.product_id", "=", $request->product_id)
                ->get();
            $product_details->category_details = DB::table("product_category")
                ->leftJoin('uploads', 'uploads.id', '=', 'product_category.upload_id')
                ->select('product_category.id', 'product_category.name', 'product_category.added_by', 'product_category.added_on', 'product_category.is_active', 'uploads.url')
                ->where("product_category.id", "=", $product_details->category_id)
                ->first();
            $product_details->sub_category_details = DB::table("product_sub_category")
                ->leftJoin('uploads', 'uploads.id', '=', 'product_sub_category.upload_id')
                ->select('product_sub_category.id', 'product_sub_category.category_id', 'product_sub_category.name', 'product_sub_category.is_active', 'uploads.url')
                ->where("product_sub_category.id", "=", $product_details->subcategory_id)
                ->first();
            $product_details->brand_details = DB::table("brands")
                ->leftJoin('uploads', 'uploads.id', '=', 'brands.upload_id')
                ->select('brands.id', 'brands.name', 'brands.added_on', 'brands.is_active', 'uploads.url')
                ->where("brands.id", "=", $product_details->brand_id)
                ->first();
                // `user_id`, `product_id`, `timestamp`
            DB::table('clicked_product_details')
                ->insert(
                    [
                        'user_id' => $request->customer_id,
                        'product_id' => $request->product_id,
                        'timestamp' => time(),
                    ]
                );
            return response()->json([
                'status' => $androidResponse->getStatus(1, 'Product Found!'),
                'data' => $product_details,
            ]);
        } catch (\Exception$e) {
            LOG::error("Error on Controller/Android/ProductsConroller.php " . $e);
            return response()->json([
                'status' => $androidResponse->getStatus(0, 'Oops. Something went wrong!'),
            ]);
        }
    }
}
