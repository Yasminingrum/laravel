<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLoginForm(Request $request)
    {
        // Store redirect URL for after login
        if ($request->has('redirect_to')) {
            session(['url.intended' => $request->redirect_to]);
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->has('remember'))) {
            $request->session()->regenerate();

            // Transfer session cart to database for customers
            if (Auth::user()->isCustomer()) {
                $this->transferSessionCartToDatabase();
            }

            // FIXED: Handle checkout redirect properly
            $redirectTo = $this->getRedirectUrl($request);

            return redirect()->to($redirectTo)
                           ->with('success', 'Welcome back, ' . Auth::user()->name . '!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Get proper redirect URL after login
     */
    private function getRedirectUrl($request)
    {
        // Priority 1: Check if user has pending checkout data
        if (Session::has('checkout_data') && Session::has('checkout_redirect')) {
            // If they have checkout data, redirect to checkout process
            return route('checkout.process');
        }

        // Priority 2: Check for checkout parameter or pending flag
        if ($request->get('redirect_to') === route('checkout') || Session::has('checkout_pending') || $request->has('checkout')) {
            Session::forget('checkout_pending');
            return route('checkout');
        }

        // Priority 3: Get redirect URL from session or request
        $redirectTo = session('url.intended', $request->get('redirect_to'));

        // Clear the intended URL
        session()->forget('url.intended');

        // Priority 4: Default redirect based on user role
        if (!$redirectTo) {
            if (Auth::user()->isAdmin()) {
                $redirectTo = route('admin.dashboard');
            } else {
                $redirectTo = route('home');
            }
        }

        return $redirectTo;
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|confirmed|min:8',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'role' => 'customer',
        ]);

        Auth::login($user);

        // Transfer session cart to database for new customer
        $this->transferSessionCartToDatabase();

        // Handle checkout redirect for new users too
        $redirectTo = $this->getRedirectUrl($request);

        return redirect()->to($redirectTo)
                       ->with('success', 'Registration successful! Welcome to Toko Saya.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
                       ->with('success', 'You have been logged out successfully.');
    }

    public function showProfile()
    {
        return view('auth.profile', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return back()->with('success', 'Profile updated successfully!');
    }

    /**
     * Transfer session cart items to database when user logs in
     */
    private function transferSessionCartToDatabase()
    {
        $sessionCart = Session::get('cart', []);

        if (empty($sessionCart)) {
            return;
        }

        foreach ($sessionCart as $productId => $cartData) {
            $product = Product::find($productId);

            // Skip if product doesn't exist or insufficient stock
            if (!$product || $product->stock < $cartData['quantity']) {
                continue;
            }

            $existingCart = Cart::where('user_id', Auth::id())
                               ->where('product_id', $productId)
                               ->first();

            if ($existingCart) {
                // Add to existing quantity if stock allows
                $newQuantity = $existingCart->quantity + $cartData['quantity'];
                if ($product->stock >= $newQuantity) {
                    $existingCart->update([
                        'quantity' => $newQuantity,
                        'price' => $product->price
                    ]);
                }
            } else {
                // Create new cart item
                Cart::create([
                    'user_id' => Auth::id(),
                    'product_id' => $productId,
                    'quantity' => $cartData['quantity'],
                    'price' => $product->price
                ]);
            }
        }

        // Clear session cart after transfer
        Session::forget('cart');
    }
}
