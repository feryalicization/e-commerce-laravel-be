<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class CartController extends Controller
{

    public function index()
    {
        $cartItems = Cart::where('user_id', Auth::id())
        ->whereNull('deleted_at')
        ->with('product')
        ->get()
        ->map(function ($item) {
            $subtotal = $item->quantity * $item->product->price;
            $item->subtotal = $subtotal; 
            return $item;
        });

    $total = $cartItems->sum('subtotal'); 

    $cartItems = $cartItems->map(function ($item) {
        $item->subtotal = 'Rp ' . number_format($item->subtotal, 0, ',', '.');
        return $item;
    });

    return response()->json([
        'cart_items' => $cartItems,
        'total' => 'Rp ' . number_format($total, 0, ',', '.') 
    ], 200);

    }

    public function store(Request $request)
    {
        $user = Auth::user();

        if (in_array($user->role, ['admin', 'superuser'])) {
            return response()->json(['message' => 'Admins and superusers cannot add products to the cart'], 403);
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::find($request->product_id);
        
        if (!$product || $product->deleted_at !== null) {
            return response()->json(['message' => 'Product not found or has been deleted.'], 404);
        }


        try {
            DB::beginTransaction();

            // Check if cart item already exists
            $cartItem = Cart::where('user_id', $user->id)
                            ->where('product_id', $request->product_id)
                            ->first();

            if ($cartItem) {
                // Increment quantity if the item already exists
                $cartItem->increment('quantity', $request->quantity);
            } else {
                // Create new cart entry
                Cart::create([
                    'user_id' => $user->id,
                    'product_id' => $request->product_id,
                    'quantity' => $request->quantity
                ]);
            }

            DB::commit();

            return response()->json(['message' => 'Product added to cart successfully.'], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to add product to cart.', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {   
        $cart = Cart::where('user_id', Auth::id())->find($id);

        if (!$cart) {
            return response()->json(['message' => 'Cart item not found'], 404);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart->update(['quantity' => $request->quantity]);

        return response()->json(['message' => 'Cart updated', 'cart' => $cart], 200);
    }

    public function destroy($id)
    {
        $user = Auth::user();

        $cartItem = Cart::where('user_id', $user->id)->where('id', $id)->first();

        if (!$cartItem) {
            return response()->json(['message' => 'Cart item not found'], 404);
        }
        $cartItem->delete();

        return response()->json(['message' => 'Cart item removed successfully'], 200);
    }


    public function clear()
    {
        Cart::where('user_id', Auth::id())->delete();

        return response()->json(['message' => 'Cart cleared'], 200);
    }
}
