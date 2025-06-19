<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    /**
     * Display customer orders - Updated to use correct view
     */
    public function index()
    {
        // Manual auth check
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        // Manual role check - ensure only customers can access this
        if (Auth::user()->role !== 'customer') {
            abort(403, 'Access denied. Customer access required.');
        }

        $orders = Order::with(['items.product', 'user'])
                      ->where('user_id', Auth::id())
                      ->orderBy('created_at', 'desc')
                      ->paginate(10);

        $userId = Auth::id();
        $orderStats = Order::selectRaw("
            COUNT(*) as total_orders,
            SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_orders,
            SUM(CASE WHEN status = 'processing' THEN 1 ELSE 0 END) as processing_orders,
            SUM(CASE WHEN status = 'shipped' THEN 1 ELSE 0 END) as shipped_orders,
            SUM(CASE WHEN status = 'delivered' THEN 1 ELSE 0 END) as delivered_orders,
            SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_orders,
            SUM(CASE WHEN status != 'cancelled' THEN total ELSE 0 END) as total_spent
        ")
        ->where('user_id', $userId)
        ->first();

        $stats = [
            'total_orders' => $orderStats->total_orders ?? 0,
            'pending_orders' => $orderStats->pending_orders ?? 0,
            'processing_orders' => $orderStats->processing_orders ?? 0,
            'shipped_orders' => $orderStats->shipped_orders ?? 0,
            'delivered_orders' => $orderStats->delivered_orders ?? 0,
            'cancelled_orders' => $orderStats->cancelled_orders ?? 0,
            'completed_orders' => $orderStats->delivered_orders ?? 0,
            'total_spent' => $orderStats->total_spent ?? 0,
        ];

        // Use customer-specific view
        return view('orders.index', compact('orders', 'stats'));
    }

    /**
     * Display order details
     */
    public function show(Order $order)
    {
        // Manual auth check
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        // Ensure user can only view their own orders OR admin can view all
        if (Auth::user()->role === 'customer' && $order->user_id !== Auth::id()) {
            abort(403, 'Access denied. You can only view your own orders.');
        }

        if (Auth::user()->role === 'admin') {
            // Admin viewing order details
            $order->load(['items.product.category', 'user']);
            return view('admin.orders.show', compact('order'));
        } else {
            // Customer viewing order details
            $order->load(['items.product.category', 'user']);
            return view('orders.show', compact('order'));
        }
    }

    /**
     * Show checkout page - Updated to allow guests
     */
    public function showCheckout()
    {
        // Get cart items for both authenticated and guest users
        if (Auth::check()) {
            $cartItems = Cart::with('product')
                            ->where('user_id', Auth::id())
                            ->get();
        } else {
            $cartItems = $this->getSessionCartItems();
        }

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                           ->with('error', 'Your cart is empty. Add some products first.');
        }

        // Check stock availability
        foreach ($cartItems as $item) {
            if ($item->product->stock < $item->quantity) {
                return redirect()->route('cart.index')
                               ->with('error', "Insufficient stock for {$item->product->name}. Only {$item->product->stock} available.");
            }
        }

        $subtotal = $cartItems->sum('total');
        $tax = $subtotal * 0.11; // 11% tax
        $shipping = $subtotal > 500000 ? 0 : 25000;
        $total = $subtotal + $tax + $shipping;

        $user = Auth::user();

        return view('orders.checkout', compact(
            'cartItems',
            'subtotal',
            'tax',
            'shipping',
            'total',
            'user'
        ));
    }

    /**
     * Process checkout and create order - FIXED for guest handling
     */
    public function processCheckout(Request $request)
    {
        // FIXED: Handle guest checkout properly
        if (!Auth::check()) {
            // For guest users, validate and store checkout data in session, then redirect to login
            $validatedData = $request->validate([
                'shipping_name' => 'required|string|max:255',
                'shipping_email' => 'required|email|max:255',
                'shipping_phone' => 'required|string|max:20',
                'shipping_address' => 'required|string|max:500',
                'shipping_city' => 'required|string|max:100',
                'shipping_postal_code' => 'required|string|max:10',
                'shipping_country' => 'required|string|max:100',
                'payment_method' => 'required|in:bank_transfer,credit_card,e_wallet,cod',
                'notes' => 'nullable|string|max:500',
            ]);

            // Store checkout data in session
            Session::put('checkout_data', $validatedData);
            Session::put('checkout_redirect', true);

            // Redirect to login with a message
            return redirect()->route('login')
                           ->with('info', 'Please login or create an account to complete your order. Your shipping details have been saved.')
                           ->with('checkout_pending', true);
        }

        // Check if user just logged in and has checkout data
        $checkoutData = Session::get('checkout_data');
        if ($checkoutData && Session::get('checkout_redirect')) {
            // Use stored checkout data and merge with current request
            $request->merge($checkoutData);
            Session::forget('checkout_data');
            Session::forget('checkout_redirect');
        } else {
            // Regular validation for logged-in users
            $request->validate([
                'shipping_name' => 'required|string|max:255',
                'shipping_email' => 'required|email|max:255',
                'shipping_phone' => 'required|string|max:20',
                'shipping_address' => 'required|string|max:500',
                'shipping_city' => 'required|string|max:100',
                'shipping_postal_code' => 'required|string|max:10',
                'shipping_country' => 'required|string|max:100',
                'payment_method' => 'required|in:bank_transfer,credit_card,e_wallet,cod',
                'notes' => 'nullable|string|max:500',
            ]);
        }

        // Manual role check
        if (Auth::user()->role !== 'customer') {
            abort(403, 'Access denied. Customer access required.');
        }

        $cartItems = Cart::with('product')
                        ->where('user_id', Auth::id())
                        ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                           ->with('error', 'Your cart is empty.');
        }

        // Calculate totals
        $subtotal = $cartItems->sum('total');
        $tax = $subtotal * 0.11;
        $shipping = $subtotal > 500000 ? 0 : 25000;
        $total = $subtotal + $tax + $shipping;

        DB::beginTransaction();

        try {
            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping_cost' => $shipping,
                'total' => $total,
                'payment_method' => $request->payment_method,
                'shipping_name' => $request->shipping_name,
                'shipping_email' => $request->shipping_email,
                'shipping_phone' => $request->shipping_phone,
                'shipping_address' => $request->shipping_address,
                'shipping_city' => $request->shipping_city,
                'shipping_postal_code' => $request->shipping_postal_code,
                'shipping_country' => $request->shipping_country,
                'notes' => $request->notes,
            ]);

            // Create order items and update product stock
            foreach ($cartItems as $cartItem) {
                $product = $cartItem->product;

                // Check stock again before creating order
                if ($product->stock < $cartItem->quantity) {
                    throw new \Exception("Insufficient stock for {$product->name}");
                }

                // Create order item
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_description' => $product->description,
                    'product_image_url' => $product->image_url,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price,
                    'total' => $cartItem->total,
                ]);

                // Update product stock
                $product->decrement('stock', $cartItem->quantity);
            }

            // Clear cart
            Cart::where('user_id', Auth::id())->delete();

            DB::commit();

            return redirect()->route('orders.show', $order)
                           ->with('success', 'Order placed successfully! Order Number: ' . $order->order_number);

        } catch (\Exception $e) {
            DB::rollback();

            return back()->withInput()
                        ->with('error', 'Order failed: ' . $e->getMessage());
        }
    }

    /**
     * Get session cart items for guest users
     */
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
     * Cancel an order
     */
    public function cancel(Order $order)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        // Ensure user can only cancel their own orders
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if order can be cancelled
        if (!in_array($order->status, ['pending', 'processing'])) {
            return back()->with('error', 'This order cannot be cancelled.');
        }

        DB::beginTransaction();

        try {
            // Restore stock for all items
            $order->load('items');
            foreach ($order->items as $item) {
                $product = Product::find($item->product_id);
                if ($product) {
                    $product->increment('stock', $item->quantity);
                }
            }

            // Update order status
            $order->update(['status' => 'cancelled']);

            DB::commit();

            return back()->with('success', 'Order has been cancelled successfully. Stock has been restored.');

        } catch (\Exception $e) {
            DB::rollback();

            return back()->with('error', 'Failed to cancel order: ' . $e->getMessage());
        }
    }

    /**
     * Reorder items
     */
    public function reorder(Order $order)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        // Ensure user can only reorder their own orders
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load('items');

        $addedItems = 0;
        $unavailableItems = [];

        foreach ($order->items as $item) {
            $product = Product::find($item->product_id);

            if (!$product || $product->stock < $item->quantity) {
                $unavailableItems[] = $item->product_name;
                continue;
            }

            $existingCart = Cart::where('user_id', Auth::id())
                               ->where('product_id', $product->id)
                               ->first();

            if ($existingCart) {
                $newQuantity = $existingCart->quantity + $item->quantity;
                if ($product->stock >= $newQuantity) {
                    $existingCart->update(['quantity' => $newQuantity]);
                    $addedItems++;
                } else {
                    $unavailableItems[] = $item->product_name;
                }
            } else {
                Cart::create([
                    'user_id' => Auth::id(),
                    'product_id' => $product->id,
                    'quantity' => $item->quantity,
                    'price' => $product->price
                ]);
                $addedItems++;
            }
        }

        $message = "Added {$addedItems} items to cart.";
        if (!empty($unavailableItems)) {
            $message .= " Some items are unavailable: " . implode(', ', $unavailableItems);
        }

        return redirect()->route('cart.index')->with('success', $message);
    }

    /**
     * Admin index - Display all orders for admin
     */
    public function adminIndex(Request $request)
    {
        // Manual auth check
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Access denied. Administrator access required.');
        }

        // Start building the query
        $query = Order::with(['user', 'items']);

        // Apply status filter if provided
        $statusFilter = $request->get('status');
        if ($statusFilter && $statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        // Apply search filter if provided
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', '%' . $search . '%')
                ->orWhereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%');
                });
            });
        }

        // Apply date range filter if provided
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->get('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->get('date_to'));
        }

        // Get orders with pagination
        $orders = $query->orderBy('created_at', 'desc')->paginate(20);

        // Calculate statistics
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'processing_orders' => Order::where('status', 'processing')->count(),
            'shipped_orders' => Order::where('status', 'shipped')->count(),
            'delivered_orders' => Order::where('status', 'delivered')->count(),
            'cancelled_orders' => Order::where('status', 'cancelled')->count(),
            'total_revenue' => Order::where('status', '!=', 'cancelled')->sum('total'),
        ];

        // Calculate filtered statistics
        $filteredQuery = Order::query();
        if ($statusFilter && $statusFilter !== 'all') {
            $filteredQuery->where('status', $statusFilter);
        }
        if ($request->filled('search')) {
            $search = $request->get('search');
            $filteredQuery->where(function($q) use ($search) {
                $q->where('order_number', 'like', '%' . $search . '%')
                ->orWhereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('name', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%');
                });
            });
        }

        $filteredStats = [
            'filtered_count' => $filteredQuery->count(),
            'filtered_revenue' => $filteredQuery->where('status', '!=', 'cancelled')->sum('total'),
        ];

        // Use admin-specific view
        return view('admin.orders.index', compact('orders', 'stats', 'filteredStats'));
    }

    /**
     * Update order status (Admin only)
     */
    public function updateStatus(Request $request, Order $order)
    {
        // Manual auth check
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Access denied. Administrator access required.');
        }

        // Validate the status
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        // Don't allow changing from cancelled to other status
        if ($oldStatus === 'cancelled' && $newStatus !== 'cancelled') {
            return back()->with('error', 'Cannot change status of cancelled orders.');
        }

        // Don't allow changing from delivered to other status
        if ($oldStatus === 'delivered' && $newStatus !== 'delivered') {
            return back()->with('error', 'Cannot change status of delivered orders.');
        }

        DB::beginTransaction();

        try {
            // Handle stock restoration for cancelled orders
            if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
                $order->load('items');
                foreach ($order->items as $item) {
                    $product = Product::find($item->product_id);
                    if ($product) {
                        $product->increment('stock', $item->quantity);
                    }
                }
            }

            // Handle special status updates
            $updateData = ['status' => $newStatus];

            if ($newStatus === 'shipped' && $oldStatus !== 'shipped') {
                $updateData['shipped_at'] = now();
            }

            if ($newStatus === 'delivered' && $oldStatus !== 'delivered') {
                $updateData['delivered_at'] = now();
            }

            // Update the order
            $order->update($updateData);

            DB::commit();

            $message = "Order {$order->order_number} status updated from {$oldStatus} to {$newStatus}.";

            if ($newStatus === 'cancelled') {
                $message .= ' Stock has been restored.';
            }

            return back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollback();

            return back()->with('error', 'Failed to update order status: ' . $e->getMessage());
        }
    }

    /**
     * Process order for demo/testing - for quick order creation
     */
    public function processOrder(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        if (Auth::user()->role !== 'customer') {
            abort(403, 'Access denied. Customer access required.');
        }

        // This is for demo/testing - use for direct order processing
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'shipping_address' => 'required|string|max:500',
            'payment_method' => 'required|in:bank_transfer,credit_card,e_wallet,cod',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->stock < $request->quantity) {
            return back()->with('error', 'Insufficient stock available.');
        }

        $subtotal = $product->price * $request->quantity;
        $tax = $subtotal * 0.11;
        $shipping = $subtotal > 500000 ? 0 : 25000;
        $total = $subtotal + $tax + $shipping;

        DB::beginTransaction();

        try {
            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping_cost' => $shipping,
                'total' => $total,
                'payment_method' => $request->payment_method,
                'shipping_name' => Auth::user()->name,
                'shipping_email' => Auth::user()->email,
                'shipping_phone' => Auth::user()->phone ?? '',
                'shipping_address' => $request->shipping_address,
                'shipping_city' => 'Jakarta',
                'shipping_postal_code' => '12345',
                'shipping_country' => 'Indonesia',
                'notes' => $request->notes ?? '',
            ]);

            // Create order item
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_description' => $product->description,
                'product_image_url' => $product->image_url,
                'quantity' => $request->quantity,
                'price' => $product->price,
                'total' => $subtotal,
            ]);

            // Update product stock
            $product->decrement('stock', $request->quantity);

            DB::commit();

            return redirect()->route('orders.show', $order)
                           ->with('success', 'Order placed successfully! Order Number: ' . $order->order_number);

        } catch (\Exception $e) {
            DB::rollback();

            return back()->withInput()
                        ->with('error', 'Order failed: ' . $e->getMessage());
        }
    }
}
