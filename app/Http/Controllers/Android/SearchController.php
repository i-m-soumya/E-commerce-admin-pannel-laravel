<?php

namespace App\Http\Controllers\Android;

use App\Classes\AndroidResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\AndroidSearchFilterBrand;
use App\Http\Resources\AndroidSearchFilterCategory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
    public function searchProducts(Request $request)
    {
        $androidResponse = new AndroidResponse();
        try {
            $androidResponse = new AndroidResponse();
            if (!$request->page && !$request->customer_id) {
                return response()->json([
                    'status' => $androidResponse->getStatus(0, 'Please provide all the required data!'),
                ]);
            }
            $request->search_key = strtolower($request->search_key);
            $products = DB::table('product_tag')
            //->where('product_tag.name', "LIKE", "%$request->search_key%")
                ->join('product', 'product.id', '=', 'product_tag.product_id')
                ->leftJoin('unit_type', 'unit_type.id', '=', 'product.unit_type_id')
                ->leftJoin('product_category', 'product_category.id', '=', 'product.category_id')
                ->leftJoin('product_sub_category', 'product_sub_category.id', '=', 'product.subcategory_id')
                ->leftJoin('brands', 'brands.id', '=', 'product.brand_id')
                ->select(
                    'product.id',
                    'product.name',
                    'brands.id as brand_id',
                    'brands.name as brand_name',
                    'product_sub_category.id as subcategory_id',
                    'product_sub_category.name as subcategory_name',
                    'product_category.id as category_id',
                    'product_category.name as catagory_name',
                    'product.desc',
                    'unit_type.name as unit_name',
                    'product.quantity',
                    'product.mrp',
                    'product.sell_price',
                    'product.discount',
                    'product.min_qty',
                    'product.max_qty',
                    'product.is_in_stock'
                )
                ->groupBy('product.id', 'brands.id', 'unit_type.id', 'product_category.id', 'product_sub_category.id');
            if ($request->search_key) {
                $products = $products->where('product_tag.name', "LIKE", "%$request->search_key%");
            }
            //$request->sell_price_sort = 'desc';
            //PRICE WISE SORT
            if ($request->sell_price_sort) {
                $products = $products->orderBy('product.sell_price', $request->sell_price_sort);
            }
            //$request->category_filter = 'accusantium';
            //CATEGORY FILTER
            if ($request->category_filter) {
                $products = $products->where('product_category.name', "=", "$request->category_filter");
            }
            //MAX MIN PRICE FILTER
            if ($request->max_price && $request->min_price) {
                $products = $products->whereBetween('product.sell_price', [$request->min_price, $request->max_price]);
            }
            //$request->sub_category_filter = 'occaecati';
            //SUB CATEGORY FILTER
            if ($request->sub_category_filter) {
                $products = $products->where('product_sub_category.name', "=", "$request->sub_category_filter");
            }
            //$request->brands_filter = 'voluptatem';
            //BRAND FILTER
            if ($request->brands_filter) {
                $products = $products->where('brands.name', "=", "$request->brands_filter");
            }
            //PAGINATION
            $paginatedProducts = $products->paginate($request->per_page ? $request->per_page : 10);
            //ATTACHING OTHER DETAILS
            foreach ($paginatedProducts as $paginatedProduct) {
                $paginatedProduct->images = DB::table("product_images")
                    ->Join("uploads", "uploads.id", "=", "product_images.upload_id")
                    ->select("uploads.*", "product_images.product_id")
                    ->where("product_images.product_id", "=", $paginatedProduct->id)
                    ->get();
                $is_in_cart = DB::table("cart")
                    ->where("cart.customer_id", "=", $request->customer_id)
                    ->where("cart_products.product_id", "=", $paginatedProduct->id)
                    ->where("cart_products.is_active", "=", 1)
                    ->join('cart_products', 'cart_products.cart_id', '=', 'cart.id')
                    ->get();
                $paginatedProduct->is_in_cart = count($is_in_cart) ? 1 : 0;
                $is_in_wish_list = DB::table("wish_list")
                    ->where("wish_list.customer_id", "=", $request->customer_id)
                    ->where("wish_list_products.product_id", "=", $paginatedProduct->id)
                    ->where("wish_list_products.is_active", "=", 1)
                    ->join('wish_list_products', 'wish_list_products.wish_list_id', '=', 'wish_list.id')
                    ->get();
                $paginatedProduct->is_in_wish_list = count($is_in_wish_list) ? 1 : 0;
            }
            if($request->search_key != '')
            {
                DB::table('search_history')
                    ->insert(
                    [
                        'user_id' => $request->customer_id,
                        'search_key' => $request->search_key,
                        'timestamp' => time(),
                        'search_result_no' => $paginatedProducts->total(),
                    ]
                );
            }
            if (!count($paginatedProducts->toArray()['data'])) {
                return response()->json([
                    'status' => $androidResponse->getStatus(0, 'No Product found!'),
                ]);
            }
            return response()->json([
                'status' => $androidResponse->getStatus(1, 'Product Found!'),
                'products' => $paginatedProducts->toArray()['data'],
                'total' => $paginatedProducts->total(),
                'per_page' => $paginatedProducts->perPage(),
                'current_page' => $paginatedProducts->currentPage(),
                'total_page' => $paginatedProducts->lastPage(),
                'from' => $paginatedProducts->firstItem(),
                'to' => $paginatedProducts->lastItem(),
            ]);
        } catch (\Exception $e) {
            LOG::error("Error on Controller/Android/SearchController.php " . $e);
            return response()->json([
                'status' => $androidResponse->getStatus(0, 'Oops. Something went wrong!'),
            ]);
        }
    }

    public function filtersForSearchPage(Request $request)
    {
        $androidResponse = new AndroidResponse();

        try {
            $products = DB::table('product_tag')

                ->where('product_tag.name', "LIKE", "%$request->search_key%")

                ->join('product', 'product.id', '=', 'product_tag.product_id')

                ->leftJoin('product_category', 'product_category.id', '=', 'product.category_id')

                ->leftJoin('product_sub_category', 'product_sub_category.id', '=', 'product.subcategory_id')

                ->leftJoin('brands', 'brands.id', '=', 'product.brand_id')

                ->select('brands.id as brand_id', 'product_sub_category.id as subcategory_id', 'product_category.id as category_id', 'product.sell_price')

                ->groupBy('product.id', 'brands.id', 'product_category.id', 'product_sub_category.id')

                ->get();

            if (count($products)) {
                $max = $products[0]->sell_price;

                $min = $products[0]->sell_price;
            }

            for ($i = 0; $i < count($products); $i++) {
                if ($max < $products[$i]->sell_price) {
                    $max = $products[$i]->sell_price;
                }

                if ($min > $products[$i]->sell_price) {
                    $min = $products[$i]->sell_price;
                }
            }

            return response()->json([

                'status' => $androidResponse->getStatus(1, 'Filter Fetched Successfully!'),

                'max_price' => $max,

                'min_price' => $min,

                'catagories' => AndroidSearchFilterCategory::collection(json_decode(json_encode($products), true))->unique(['category_id'])->values()->all(),

                'brands' => AndroidSearchFilterBrand::collection(json_decode(json_encode($products), true))->unique(['brand_id'])->values()->all(),

            ]);
        } catch (\Exception $e) {
            LOG::error("Error on Controller/Android/SearchController.php " . $e);

            return response()->json([

                'status' => $androidResponse->getStatus(0, 'Oops. Something went wrong!'),

            ]);
        }
    }

    //fetch_best_deals
    public function fetch_best_deals(Request $request)
    {
        try {
            $androidResponse = new AndroidResponse();
            $products = DB::table('product')
                ->leftJoin('unit_type', 'unit_type.id', '=', 'product.unit_type_id')
                ->leftJoin('product_category', 'product_category.id', '=', 'product.category_id')
                ->leftJoin('product_sub_category', 'product_sub_category.id', '=', 'product.subcategory_id')
                ->leftJoin('brands', 'brands.id', '=', 'product.brand_id')
                ->select(
                    'product.id',
                    'product.name',
                    'brands.id as brand_id',
                    'brands.name as brand_name',
                    'product_sub_category.id as subcategory_id',
                    'product_sub_category.name as subcategory_name',
                    'product_category.id as category_id',
                    'product_category.name as catagory_name',
                    'product.desc',
                    'unit_type.name as unit_name',
                    'product.quantity',
                    'product.mrp',
                    'product.sell_price',
                    'product.discount',
                    'product.min_qty',
                    'product.max_qty',
                    'product.is_in_stock'
                )
                ->orderBy('product.discount', 'desc')
                ->limit(10)
                ->get();
            foreach ($products as $product) {
                $product->images = DB::table("product_images")
                    ->Join("uploads", "uploads.id", "=", "product_images.upload_id")
                    ->select("uploads.*", "product_images.product_id")
                    ->where("product_images.product_id", "=", $product->id)
                    ->get();
                $product->is_in_cart = 0;

                $product->is_in_wish_list = 0;
                if ($request->customer_id) {
                    $is_in_cart = DB::table("cart")
                        ->where("cart.customer_id", "=", $request->customer_id)
                        ->where("cart_products.product_id", "=", $product->id)
                        ->join('cart_products', 'cart_products.cart_id', '=', 'cart.id')
                        ->get();
                    $is_in_wish_list = DB::table("wish_list")
                        ->where("wish_list.customer_id", "=", $request->customer_id)
                        ->where("wish_list_products.product_id", "=", $product->id)
                        ->join('wish_list_products', 'wish_list_products.wish_list_id', '=', 'wish_list.id')
                        ->get();
                    $product->is_in_cart = count($is_in_cart) ? 1 : 0;

                    $product->is_in_wish_list = count($is_in_wish_list) ? 1 : 0;
                }

            }
            if (count($products)) {
                return response()->json([
                    'status' => $androidResponse->getStatus(1, 'Best deals fetched successfully'),
                    'products' => $products,
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

    //trending_products
    public function trending_products(Request $request)
    {
        try {
            $androidResponse = new AndroidResponse();
            $fetch_trending_products = DB::table('orders_details')
                ->leftjoin('product', 'orders_details.product_id', '=', 'product.id')
                ->select(DB::raw('count(orders_details.product_id) as total_order,orders_details.product_id'))
                ->groupBy('orders_details.product_id')
                ->orderBy('total_order', 'desc')
                ->limit(10)
                ->get();
            $product_ids = array();
            foreach ($fetch_trending_products as $trending_product) {
                array_push($product_ids, $trending_product->product_id);
            }

            $products = DB::table('product')
                ->whereIn('product.id', $product_ids)
                ->leftJoin('unit_type', 'unit_type.id', '=', 'product.unit_type_id')
                ->leftJoin('product_category', 'product_category.id', '=', 'product.category_id')
                ->leftJoin('product_sub_category', 'product_sub_category.id', '=', 'product.subcategory_id')
                ->leftJoin('brands', 'brands.id', '=', 'product.brand_id')
                ->select(
                    'product.id',
                    'product.name',
                    'brands.id as brand_id',
                    'brands.name as brand_name',
                    'product_sub_category.id as subcategory_id',
                    'product_sub_category.name as subcategory_name',
                    'product_category.id as category_id',
                    'product_category.name as catagory_name',
                    'product.desc',
                    'unit_type.name as unit_name',
                    'product.quantity',
                    'product.mrp',
                    'product.sell_price',
                    'product.discount',
                    'product.min_qty',
                    'product.max_qty',
                    'product.is_in_stock'
                )
                ->orderBy('product.discount', 'desc')
                ->get();
            foreach ($products as $product) {
                $product->is_in_cart = 0;

                $product->is_in_wish_list = 0;
                $product->images = DB::table("product_images")
                    ->Join("uploads", "uploads.id", "=", "product_images.upload_id")
                    ->select("uploads.*", "product_images.product_id")
                    ->where("product_images.product_id", "=", $product->id)
                    ->get();
                if ($request->customer_id) {
                    $is_in_cart = DB::table("cart")
                        ->where("cart.customer_id", "=", $request->customer_id)
                        ->where("cart_products.product_id", "=", $product->id)
                        ->join('cart_products', 'cart_products.cart_id', '=', 'cart.id')
                        ->get();
                    $product->is_in_cart = count($is_in_cart) ? 1 : 0;
                    $is_in_wish_list = DB::table("wish_list")
                        ->where("wish_list.customer_id", "=", $request->customer_id)
                        ->where("wish_list_products.product_id", "=", $product->id)
                        ->join('wish_list_products', 'wish_list_products.wish_list_id', '=', 'wish_list.id')
                        ->get();
                    $product->is_in_wish_list = count($is_in_wish_list) ? 1 : 0;
                }
            }
            if (count($products)) {
                return response()->json([
                    'status' => $androidResponse->getStatus(1, 'Trending products fetched successfully'),
                    'products' => $products,
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
    public function searchSuggetions(Request $request)
    {
        if (!$request->search_key && !$request->search_key) {
            return response()->json([
                'status' => $androidResponse->getStatus(0, 'Please provide all the required data!'),
            ]);
        }
        try {
            $androidResponse = new AndroidResponse();
            $products = DB::table('product')
                ->where('product.name', "LIKE", "%$request->search_key%")
                ->where('product.is_in_stock', "=", 1)
                ->select('product.name')
                ->distinct()
                ->limit(3)
                ->get();
            $product_category = DB::table('product_category')
                ->where('product_category.name', "LIKE", "%$request->search_key%")
                ->where('product_category.is_active', "=", 1)
                ->select('product_category.name')
                ->distinct()
                ->limit(5)
                ->get();
            $brands = DB::table('brands')
                ->where('brands.name', "LIKE", "%$request->search_key%")
                ->where('brands.is_active', "=", 1)
                ->select('brands.name')
                ->distinct()
                ->limit(5)
                ->get();
            $tags = DB::table('product_tag')
                ->where('product_tag.name', "LIKE", "%$request->search_key%")
                ->select('product_tag.name')
                ->distinct()
                ->limit(5)
                ->get();
            return response()->json([
                'status' => $androidResponse->getStatus(1, 'Success!'),
                'product' => $products,
                'category' => $product_category,
                'brands' => $brands,
                'tags' => $tags,
            ]);

        } catch (\Exception $e) {
            LOG::error("Error on Controller/Android/OrderController.php " . $e);
            return response()->json([
                'status' => $androidResponse->getStatus(0, 'Oops. Something went wrong!'),
            ]);
        }
    }
}
