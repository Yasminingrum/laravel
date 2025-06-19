<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            // Authenticated user
            $cartItems = Cart::with('product.category')
                            ->where('user_id', Auth::id())
                            ->get();
        } else {
            // Guest user
            $cartItems = $this->getSessionCartItems();
        }

        $subtotal = $cartItems->sum('total');
        $tax = $subtotal * 0.11;
        $shipping = $subtotal > 500000 ? 0 : 25000;
        $total = $subtotal + $tax + $shipping;

        return view('cart.index', compact(
            'cartItems',
            'subtotal',
            'tax',
            'shipping',
            'total'
        ));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'integer|min:1|max:100'
        ]);

        $product = Product::findOrFail($request->product_id);
        $quantity = $request->quantity ?? 1;

        if ($product->stock < $quantity) {
            return back()->with('error', 'Insufficient stock. Only ' . $product->stock . ' items available.');
        }

        if (Auth::check()) {
            // Authenticated user - save to database
            return $this->addToDatabase($product, $quantity, $request);
        } else {
            // Guest user - save to session
            return $this->addToSession($product, $quantity, $request);
        }
    }

    private function addToDatabase($product, $quantity, $request)
    {
        // Check role for customers only
        if (Auth::user()->role !== 'customer') {
            return back()->with('error', 'Only customers can add items to cart.');
        }

        $existingCart = Cart::where('user_id', Auth::id())
                           ->where('product_id', $product->id)
                           ->first();

        if ($existingCart) {
            $newQuantity = $existingCart->quantity + $quantity;

            if ($product->stock < $newQuantity) {
                return back()->with('error', 'Cannot add more items. Maximum available: ' . $product->stock);
            }

            $existingCart->update([
                'quantity' => $newQuantity,
                'price' => $product->price
            ]);

            $message = 'Cart updated! Quantity increased to ' . $newQuantity;
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $product->price
            ]);

            $message = 'Product added to cart successfully!';
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'cart_count' => $this->getCartCount()
            ]);
        }

        return back()->with('success', $message);
    }

    private function addToSession($product, $quantity, $request)
    {
        $cart = Session::get('cart', []);
        $productId = $product->id;

        if (isset($cart[$productId])) {
            $newQuantity = $cart[$productId]['quantity'] + $quantity;
        } else {
            $newQuantity = $quantity;
        }

        if ($product->stock < $newQuantity) {
            return back()->with('error', 'Cannot add more items. Maximum available: ' . $product->stock);
        }

        $cart[$productId] = [
            'quantity' => $newQuantity,
            'price' => $product->price,
            'name' => $product->name,
            'image_url' => $product->image_url
        ];

        Session::put('cart', $cart);

        $message = 'Product added to cart successfully!';

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'cart_count' => $this->getCartCount()
            ]);
        }

        return back()->with('success', $message);
    }

    public function update(Request $request, $cartId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:100'
        ]);

        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->findOrFail($cartId);
            $product = $cart->product;

            if ($product->stock < $request->quantity) {
                return back()->with('error', 'Insufficient stock. Only ' . $product->stock . ' items available.');
            }

            $cart->update([
                'quantity' => $request->quantity,
                'price' => $product->price
            ]);

            if ($request->ajax()) {
                $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();
                $subtotal = $cartItems->sum('total');
                $tax = $subtotal * 0.11;
                $shipping = $subtotal > 500000 ? 0 : 25000;
                $total = $subtotal + $tax + $shipping;

                return response()->json([
                    'success' => true,
                    'message' => 'Cart updated successfully!',
                    'cart_total' => $cart->formatted_total,
                    'subtotal' => 'Rp ' . number_format($subtotal, 0, ',', '.'),
                    'tax' => 'Rp ' . number_format($tax, 0, ',', '.'),
                    'shipping' => 'Rp ' . number_format($shipping, 0, ',', '.'),
                    'total' => 'Rp ' . number_format($total, 0, ',', '.'),
                    'cart_count' => $this->getCartCount()
                ]);
            }
        } else {
            // Session cart
            $cart = Session::get('cart', []);
            $productId = $cartId;

            if (!isset($cart[$productId])) {
                return back()->with('error', 'Item not found in cart.');
            }

            $product = Product::findOrFail($productId);

            if ($product->stock < $request->quantity) {
                return back()->with('error', 'Insufficient stock. Only ' . $product->stock . ' items available.');
            }

            $cart[$productId]['quantity'] = $request->quantity;
            $cart[$productId]['price'] = $product->price;
            Session::put('cart', $cart);

            if ($request->ajax()) {
                // Calculate new totals for session cart
                $cartItems = $this->getSessionCartItems();
                $subtotal = $cartItems->sum('total');
                $tax = $subtotal * 0.11;
                $shipping = $subtotal > 500000 ? 0 : 25000;
                $total = $subtotal + $tax + $shipping;

                return response()->json([
                    'success' => true,
                    'message' => 'Cart updated successfully!',
                    'cart_total' => 'Rp ' . number_format($product->price * $request->quantity, 0, ',', '.'),
                    'subtotal' => 'Rp ' . number_format($subtotal, 0, ',', '.'),
                    'tax' => 'Rp ' . number_format($tax, 0, ',', '.'),
                    'shipping' => 'Rp ' . number_format($shipping, 0, ',', '.'),
                    'total' => 'Rp ' . number_format($total, 0, ',', '.'),
                    'cart_count' => $this->getCartCount()
                ]);
            }
        }

        return back()->with('success', 'Cart updated successfully!');
    }

    public function remove($cartId)
    {
        if (Auth::check()) {
            // Database cart
            $cart = Cart::where('user_id', Auth::id())->findOrFail($cartId);
            $productName = $cart->product->name;
            $cart->delete();
        } else {
            // Session cart
            $sessionCart = Session::get('cart', []);
            $productId = $cartId;

            if (!isset($sessionCart[$productId])) {
                return back()->with('error', 'Item not found in cart.');
            }

            $productName = $sessionCart[$productId]['name'] ?? 'Product';

            unset($sessionCart[$productId]);
            Session::put('cart', $sessionCart);
        }

        return back()->with('success', $productName . ' removed from cart.');
    }

    public function clear()
    {
        if (Auth::check()) {
            Cart::where('user_id', Auth::id())->delete();
        } else {
            Session::forget('cart');
        }

        return back()->with('success', 'Cart cleared successfully!');
    }

    public function count()
    {
        $count = $this->getCartCount();
        return response()->json(['count' => $count]);
    }

    public function quickAdd(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->stock < 1) {
            return response()->json([
                'success' => false,
                'message' => 'Product is out of stock.'
            ]);
        }

        if (Auth::check()) {
            if (Auth::user()->role !== 'customer') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only customers can add items to cart.'
                ]);
            }

            $existingCart = Cart::where('user_id', Auth::id())
                               ->where('product_id', $product->id)
                               ->first();

            if ($existingCart) {
                if ($product->stock < $existingCart->quantity + 1) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot add more items. Maximum available: ' . $product->stock
                    ]);
                }

                $existingCart->increment('quantity');
            } else {
                Cart::create([
                    'user_id' => Auth::id(),
                    'product_id' => $product->id,
                    'quantity' => 1,
                    'price' => $product->price
                ]);
            }
        } else {
            // Guest cart - add to session
            $cart = Session::get('cart', []);
            $productId = $product->id;
            $currentQuantity = isset($cart[$productId]) ? $cart[$productId]['quantity'] : 0;

            if ($product->stock < $currentQuantity + 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot add more items. Maximum available: ' . $product->stock
                ]);
            }

            $cart[$productId] = [
                'quantity' => $currentQuantity + 1,
                'price' => $product->price,
                'name' => $product->name,
                'image_url' => $product->image_url
            ];

            Session::put('cart', $cart);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart!',
            'cart_count' => $this->getCartCount()
        ]);
    }

    public function getCartCount()
    {
        if (Auth::check()) {
            return Cart::where('user_id', Auth::id())->sum('quantity');
        } else {
            $cart = Session::get('cart', []);
            return array_sum(array_column($cart, 'quantity'));
        }
    }

    private function getSessionCartItems()
    {
        $sessionCart = Session::get('cart', []);
        $cartItems = collect();

        foreach ($sessionCart as $productId => $cartData) {
            $product = Product::with('category')->find($productId);
            if ($product) {
                $cartItem = (object) [
                    'id' => $productId,
                    'product_id' => $productId,
                    'product' => $product,
                    'quantity' => $cartData['quantity'],
                    'price' => $cartData['price'],
                    'total' => $cartData['price'] * $cartData['quantity'],
                    'formatted_price' => 'Rp ' . number_format($cartData['price'], 0, ',', '.'),
                    'formatted_total' => 'Rp ' . number_format($cartData['price'] * $cartData['quantity'], 0, ',', '.'),
                ];
                $cartItems->push($cartItem);
            }
        }

        return $cartItems;
    }

    /**
     * Transfer session cart to database when user logs in
     */
    public function transferSessionToDatabase()
    {
        if (!Auth::check() || Auth::user()->role !== 'customer') {
            return;
        }

        $sessionCart = Session::get('cart', []);

        foreach ($sessionCart as $productId => $cartData) {
            $product = Product::find($productId);
            if (!$product || $product->stock < $cartData['quantity']) {
                continue;
            }

            $existingCart = Cart::where('user_id', Auth::id())
                               ->where('product_id', $productId)
                               ->first();

            if ($existingCart) {
                $newQuantity = $existingCart->quantity + $cartData['quantity'];
                if ($product->stock >= $newQuantity) {
                    $existingCart->update(['quantity' => $newQuantity]);
                }
            } else {
                Cart::create([
                    'user_id' => Auth::id(),
                    'product_id' => $productId,
                    'quantity' => $cartData['quantity'],
                    'price' => $product->price
                ]);
            }
        }

        // Clear session cart
        Session::forget('cart');
    }
}
