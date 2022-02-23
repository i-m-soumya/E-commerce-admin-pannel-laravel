<?php

namespace App\Http\Controllers\Android;

use App\Classes\AndroidResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        try {
            $androidResponse = new AndroidResponse();
            if (!$request->product_id || $request->product_id == "" || !$request->customer_id || $request->customer_id == "" || !$request->quantity || $request->quantity == "") {
                return response()->json([
                    'status' => $androidResponse->getStatus(0, 'Please provide all parameters!'),
                ]);
            }
            $is_exist = DB::table('cart')
                ->where('customer_id', '=', $request->customer_id)
                ->where('is_active', '=', 1)
                ->first();

            if ($is_exist) {
                $is_product_exist = DB::table('cart_products')
                    ->where('cart_id', '=', $is_exist->id)
                    ->where('product_id', '=', $request->product_id)
                    ->where('is_active', '=', 1)
                    ->first();
                if ($is_product_exist) {
                    return response()->json([
                        'status' => $androidResponse->getStatus(0, 'Product Already exist!'),
                    ]);
                }
                $inserted_cart = DB::table('cart_products')
                    ->insert(
                        [
                            'cart_id' => $is_exist->id,
                            'product_id' => $request->product_id,
                            'quantity' => $request->quantity,
                            'is_active' => 1,
                        ]
                    );
            } else {
                $inserted_cart = DB::table('cart')
                    ->insertGetId(
                        [
                            'customer_id' => $request->customer_id,
                            'is_active' => 1,
                        ]
                    );
                if ($inserted_cart) {
                    //cart_id, product_id, quantity, is_active
                    $inserted_cart = DB::table('cart_products')
                        ->insert(
                            [
                                'cart_id' => $inserted_cart,
                                'product_id' => $request->product_id,
                                'quantity' => $request->quantity,
                                'is_active' => 1,
                            ]
                        );
                }
            }
            return response()->json([
                'status' => $androidResponse->getStatus(1, 'Added to cart!'),
            ]);
        } catch (\Exception$e) {
            LOG::error("Error on Controller/Android/CartController.php " . $e);
            return response()->json([
                'status' => $androidResponse->getStatus(0, 'Oops. Something went wrong!'),
            ]);
        }

    }
    public function fetchCart(Request $request)
    {
        $androidResponse = new AndroidResponse();
        if (!$request->customer_id || $request->customer_id == "") {
            return response()->json([
                'status' => $androidResponse->getStatus(0, 'Please provide all parameters!'),
            ]);
        }
        try {
            $carts = DB::table('cart')
                ->where('cart.is_active', 1)
                ->where('cart_products.is_active', 1)
                ->where('cart.customer_id', $request->customer_id)
                ->leftJoin('cart_products', 'cart.id', '=', 'cart_products.cart_id')
                ->join('product', 'product.id', '=', 'cart_products.product_id')
                ->leftJoin('unit_type', 'unit_type.id', '=', 'product.unit_type_id')
                ->leftJoin('product_category', 'product_category.id', '=', 'product.category_id')
                ->leftJoin('product_sub_category', 'product_sub_category.id', '=', 'product.subcategory_id')
                ->leftJoin('brands', 'brands.id', '=', 'product.brand_id')
                ->select('product.id', 'product.name', 'brands.id as brand_id', 'brands.name as brand_name', 'product_sub_category.id as subcategory_id', 'product_sub_category.name as subcategory_name', 'product_category.id as category_id', 'product_category.name as catagory_name', 'product.desc', 'unit_type.name as unit_name', 'cart_products.quantity as quantity', 'product.mrp', 'product.sell_price', 'product.discount',
                    'product.min_qty', 'product.max_qty', 'product.is_in_stock')
                ->get();
            if (count($carts)) {
                for ($i = 0 ; $i < count($carts) ; $i++) {
                    $carts[$i]->images = DB::table("product_images")
                        ->Join("uploads", "uploads.id", "=", "product_images.upload_id")
                        ->select("uploads.url", "uploads.file_name")
                        ->where("product_images.product_id", "=", $carts[$i]->id)
                        ->get();
                }
                $referal_code = DB::table('referal_details')
                    ->where('referal_to','=',$request->customer_id)
                    ->where('valid_till','>',time())
                    ->where('is_eligeble','=',1)
                    ->where('is_used','=',0)
                    ->orderBy('valid_till', 'ASC')
                    ->leftJoin('customers', 'customers.id', '=', 'referal_details.referal_for')
                    ->select('referal_details.valid_till','referal_details.amount','customers.name as referal_bonus_for')
                    ->first();
                return response()->json([
                    'status' => $androidResponse->getStatus(1, 'Cart Found!'),
                    'is_referal_exist' => $referal_code ? 1 : 0,
                    'referal_details' => $referal_code,
                    'details' => $carts,
                ]);
            } else {
                return response()->json([
                    'status' => $androidResponse->getStatus(0, 'Cart Not Found!'),
                ]);
            }
        } catch (\Exception$e) {
            LOG::error("Error on Controller/Android/CartController.php " . $e);
            return response()->json([
                'status' => $androidResponse->getStatus(0, 'Oops. Something went wrong!'),
            ]);
        }
    }
    public function removeFromCart(Request $request)
    {
        $androidResponse = new AndroidResponse();
        try {
            if (!$request->customer_id || $request->customer_id == "" || !$request->product_id || $request->product_id == "") {
                return response()->json([
                    'status' => $androidResponse->getStatus(0, 'Please provide all parameters!'),
                ]);
            }
            $cart = DB::table('cart')
                ->where('cart.is_active', 1)
                ->where('cart.customer_id', $request->customer_id)
                ->first();
            if (!$cart) {
                return response()->json([
                    'status' => $androidResponse->getStatus(0, 'You have no cart!'),
                ]);
            }
            $update_wishlist = DB::table('cart_products')
                ->where('cart_products.is_active', 1)
                ->where('cart_products.cart_id', $cart->id)
                ->where('cart_products.product_id', $request->product_id)
                ->update(['cart_products.is_active' => 0]);
            return response()->json([
                'status' => $androidResponse->getStatus(1, 'Deleted!'),
            ]);
        } catch (\Exception$e) {
            LOG::error("Error on Controller/Android/CartController.php " . $e);
            return response()->json([
                'status' => $androidResponse->getStatus(0, 'Oops. Something went wrong!'),
            ]);
        }
    }
    public function updateCartProductQuantity(Request $request)
    {
        $androidResponse = new AndroidResponse();
        try {
            if (!$request->customer_id || $request->customer_id == "" || !$request->product_id || $request->product_id == "" || !$request->quantity || $request->quantity == "") {
                return response()->json([
                    'status' => $androidResponse->getStatus(0, 'Please provide all parameters!'),
                ]);
            }
            $cart = DB::table('cart')
                ->where('cart.is_active', 1)
                ->where('cart.customer_id', $request->customer_id)
                ->first();
            if (!$cart) {
                return response()->json([
                    'status' => $androidResponse->getStatus(0, 'No cart found!'),
                ]);
            }
            $update_wishlist = DB::table('cart_products')
                ->where('cart_products.cart_id', $cart->id)
                ->where('cart_products.product_id', $request->product_id)
                ->update(['cart_products.quantity' => $request->quantity]);
            return response()->json([
                'status' => $androidResponse->getStatus(1, 'Quantity Updated!'),
            ]);
        } catch (\Exception$e) {
            LOG::error("Error on Controller/Android/CartController.php " . $e);
            return response()->json([
                'status' => $androidResponse->getStatus(0, 'Oops. Something went wrong!'),
            ]);
        }
    }
}
