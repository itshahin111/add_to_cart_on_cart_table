<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('products', compact('products'));
    }

    public function addToCart($id)
    {
        $product = Product::findOrFail($id);

        $cart = Cart::where('user_id', Auth::id())
            ->where('product_id', $id)
            ->first();

        if ($cart) {
            $cart->quantity++;
            $cart->save();
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $id,
                'quantity' => 1,
            ]);
        }

        return response()->json([
            'message' => 'Product added to cart successfully!',
            'cartCount' => Cart::where('user_id', Auth::id())->count(),
            'cartItems' => Cart::where('user_id', Auth::id())->with('product')->get(),
            'totalPrice' => Cart::where('user_id', Auth::id())->with('product')->get()->sum(function ($cartItem) {
                return $cartItem->product->price * $cartItem->quantity;
            }),
        ]);
    }

    public function cart()
    {
        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
        $totalPrice = $cartItems->sum(function ($cartItem) {
            return $cartItem->product->price * $cartItem->quantity;
        });

        return view('cart', compact('cartItems', 'totalPrice'));
    }

    public function update(Request $request)
    {
        $cart = Cart::where('user_id', Auth::id())->where('product_id', $request->id)->first();

        if ($cart) {
            $cart->quantity = $request->quantity;
            $cart->save();
        }

        return response()->json([
            'message' => 'Cart updated successfully',
            'totalPrice' => Cart::where('user_id', Auth::id())->with('product')->get()->sum(function ($cartItem) {
                return $cartItem->product->price * $cartItem->quantity;
            }),
        ]);
    }

    public function remove(Request $request)
    {
        $cart = Cart::where('user_id', Auth::id())->where('product_id', $request->id)->first();

        if ($cart) {
            $cart->delete();
        }

        return response()->json([
            'message' => 'Product removed successfully',
            'totalPrice' => Cart::where('user_id', Auth::id())->with('product')->get()->sum(function ($cartItem) {
                return $cartItem->product->price * $cartItem->quantity;
            }),
        ]);
    }
}