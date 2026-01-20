<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\Admin\AdminController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/categories/{category:slug}', [ProductController::class, 'category'])->name('categories.show');
Route::get('/brands/{brand:slug}', [ProductController::class, 'brand'])->name('brands.show');
Route::get('/stores', [StoreController::class, 'index'])->name('stores.index');
Route::get('/stores/{store:slug}', [StoreController::class, 'show'])->name('stores.show');
Route::get('/search', [ProductController::class, 'search'])->name('search');

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/forgot-password', function () {
        return view('auth.forgot-password');
    })->name('password.request');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/addresses', [ProfileController::class, 'addresses'])->name('profile.addresses');
    Route::get('/profile/cars', [ProfileController::class, 'cars'])->name('profile.cars');
    Route::get('/profile/security', [ProfileController::class, 'security'])->name('profile.security');
    
    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/update/{item}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{item}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    
    // Checkout
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [OrderController::class, 'placeOrder'])->name('checkout.place');
    
    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::get('/orders/{order}/track', [OrderController::class, 'track'])->name('orders.track');
    
    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/add/{product}', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::delete('/wishlist/remove/{product}', [WishlistController::class, 'remove'])->name('wishlist.remove');
    
    // Wallet
    Route::get('/wallet', [ProfileController::class, 'wallet'])->name('wallet.index');
    
    // Notifications
    Route::get('/notifications', [ProfileController::class, 'notifications'])->name('notifications.index');
    
    // Messages
    Route::get('/messages', [ProfileController::class, 'messages'])->name('messages.index');
    Route::get('/messages/{conversation}', [ProfileController::class, 'conversation'])->name('messages.show');
});

/*
|--------------------------------------------------------------------------
| Store Dashboard Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->prefix('store')->name('store.')->group(function () {
    Route::get('/dashboard', [StoreController::class, 'dashboard'])->name('dashboard');
    Route::get('/products', [StoreController::class, 'products'])->name('products');
    Route::get('/products/create', [StoreController::class, 'createProduct'])->name('products.create');
    Route::post('/products', [StoreController::class, 'storeProduct'])->name('products.store');
    Route::get('/products/{product}/edit', [StoreController::class, 'editProduct'])->name('products.edit');
    Route::put('/products/{product}', [StoreController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{product}', [StoreController::class, 'destroyProduct'])->name('products.destroy');
    
    Route::get('/orders', [StoreController::class, 'orders'])->name('orders');
    Route::get('/orders/{order}', [StoreController::class, 'showOrder'])->name('orders.show');
    Route::put('/orders/{order}/status', [StoreController::class, 'updateOrderStatus'])->name('orders.status');
    
    Route::get('/inventory', [StoreController::class, 'inventory'])->name('inventory');
    Route::get('/customers', [StoreController::class, 'customers'])->name('customers');
    Route::get('/reviews', [StoreController::class, 'reviews'])->name('reviews');
    Route::get('/reports', [StoreController::class, 'reports'])->name('reports');
    Route::get('/settings', [StoreController::class, 'settings'])->name('settings');
    Route::put('/settings', [StoreController::class, 'updateSettings'])->name('settings.update');
});

/*
|--------------------------------------------------------------------------
| Driver Dashboard Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->prefix('driver')->name('driver.')->group(function () {
    Route::get('/dashboard', [DriverController::class, 'dashboard'])->name('dashboard');
    Route::get('/deliveries', [DriverController::class, 'deliveries'])->name('deliveries');
    Route::get('/deliveries/available', [DriverController::class, 'availableDeliveries'])->name('deliveries.available');
    Route::get('/deliveries/{delivery}', [DriverController::class, 'showDelivery'])->name('deliveries.show');
    Route::post('/deliveries/{delivery}/accept', [DriverController::class, 'acceptDelivery'])->name('deliveries.accept');
    Route::post('/deliveries/{delivery}/reject', [DriverController::class, 'rejectDelivery'])->name('deliveries.reject');
    Route::put('/deliveries/{delivery}/status', [DriverController::class, 'updateDeliveryStatus'])->name('deliveries.status');
    
    Route::get('/earnings', [DriverController::class, 'earnings'])->name('earnings');
    Route::get('/ratings', [DriverController::class, 'ratings'])->name('ratings');
    Route::get('/settings', [DriverController::class, 'settings'])->name('settings');
    Route::put('/settings', [DriverController::class, 'updateSettings'])->name('settings.update');
    Route::post('/status', [DriverController::class, 'updateStatus'])->name('status.update');
});

/*
|--------------------------------------------------------------------------
| Admin Dashboard Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Users Management
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/users/{user}', [AdminController::class, 'showUser'])->name('users.show');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.delete');
    
    // Stores Management
    Route::get('/stores', [AdminController::class, 'stores'])->name('stores');
    Route::get('/stores/{store}', [AdminController::class, 'showStore'])->name('stores.show');
    Route::put('/stores/{store}/approve', [AdminController::class, 'approveStore'])->name('stores.approve');
    Route::put('/stores/{store}/suspend', [AdminController::class, 'suspendStore'])->name('stores.suspend');
    
    // Drivers Management
    Route::get('/drivers', [AdminController::class, 'drivers'])->name('drivers');
    Route::get('/drivers/{driver}', [AdminController::class, 'showDriver'])->name('drivers.show');
    Route::put('/drivers/{driver}/approve', [AdminController::class, 'approveDriver'])->name('drivers.approve');
    
    // Products Management
    Route::get('/products', [AdminController::class, 'products'])->name('products');
    Route::get('/products/{product}', [AdminController::class, 'showProduct'])->name('products.show');
    Route::put('/products/{product}/approve', [AdminController::class, 'approveProduct'])->name('products.approve');
    Route::put('/products/{product}/reject', [AdminController::class, 'rejectProduct'])->name('products.reject');
    
    // Orders Management
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
    Route::get('/orders/{order}', [AdminController::class, 'showOrder'])->name('orders.show');
    
    // Categories Management
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    Route::resource('brands', \App\Http\Controllers\Admin\BrandController::class);
    
    // Settings
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::put('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
    
    // Reports
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
    Route::get('/reports/sales', [AdminController::class, 'salesReport'])->name('reports.sales');
    Route::get('/reports/users', [AdminController::class, 'usersReport'])->name('reports.users');
    Route::get('/reports/products', [AdminController::class, 'productsReport'])->name('reports.products');
});
