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
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\LoyaltyController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\UserCarController;
use App\Http\Controllers\CompareController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AffiliateController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\GiftCardController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\PriceAlertController;
use App\Http\Controllers\InstallmentController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\AuctionController;
use App\Http\Controllers\BundleController;
use App\Http\Controllers\FlashSaleController;
use App\Http\Controllers\WalletController;

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

// Search
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/search/advanced', [SearchController::class, 'advanced'])->name('search.advanced');
Route::get('/api/search/suggestions', [SearchController::class, 'suggestions'])->name('search.suggestions');

// Compare Products
Route::get('/compare', [CompareController::class, 'index'])->name('compare.index');
Route::post('/compare/add', [CompareController::class, 'add'])->name('compare.add');
Route::post('/compare/remove', [CompareController::class, 'remove'])->name('compare.remove');
Route::post('/compare/clear', [CompareController::class, 'clear'])->name('compare.clear');

// Blog
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/blog/category/{slug}', [BlogController::class, 'category'])->name('blog.category');
Route::get('/blog/tag/{slug}', [BlogController::class, 'tag'])->name('blog.tag');

// FAQ
Route::get('/faq', [FaqController::class, 'index'])->name('faq.index');
Route::get('/api/faq/search', [FaqController::class, 'search'])->name('faq.search');
Route::post('/faq/{faq}/helpful', [FaqController::class, 'helpful'])->name('faq.helpful');

// Static Pages
Route::get('/about', [PageController::class, 'about'])->name('pages.about');
Route::get('/contact', [PageController::class, 'contact'])->name('pages.contact');
Route::post('/contact', [PageController::class, 'submitContact'])->name('contact.submit');
Route::get('/terms', [PageController::class, 'terms'])->name('pages.terms');
Route::get('/privacy', [PageController::class, 'privacy'])->name('pages.privacy');
Route::get('/shipping-policy', [PageController::class, 'shipping'])->name('pages.shipping');
Route::get('/returns-policy', [PageController::class, 'returns'])->name('pages.returns');
Route::get('/warranty', [PageController::class, 'warranty'])->name('pages.warranty');
Route::get('/page/{slug}', [PageController::class, 'show'])->name('pages.show');

// Flash Sales
Route::get('/flash-sales', [FlashSaleController::class, 'index'])->name('flash-sales.index');
Route::get('/flash-sales/{flashSale}', [FlashSaleController::class, 'show'])->name('flash-sales.show');

// Bundles
Route::get('/bundles', [BundleController::class, 'index'])->name('bundles.index');
Route::get('/bundles/{bundle}', [BundleController::class, 'show'])->name('bundles.show');

// Auctions
Route::get('/auctions', [AuctionController::class, 'index'])->name('auctions.index');
Route::get('/auctions/{auction}', [AuctionController::class, 'show'])->name('auctions.show');

// Gift Cards
Route::get('/gift-cards', [GiftCardController::class, 'index'])->name('gift-cards.index');
Route::post('/gift-cards/check-balance', [GiftCardController::class, 'checkBalance'])->name('gift-cards.check-balance');

// Newsletter
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('newsletter.subscribe');
Route::get('/newsletter/unsubscribe', [NewsletterController::class, 'unsubscribe'])->name('newsletter.unsubscribe');
Route::get('/newsletter/preferences', [NewsletterController::class, 'preferences'])->name('newsletter.preferences');
Route::post('/newsletter/preferences', [NewsletterController::class, 'updatePreferences'])->name('newsletter.preferences.update');

// Tracking
Route::get('/track', [TrackingController::class, 'index'])->name('tracking.index');
Route::post('/track', [TrackingController::class, 'track'])->name('tracking.track');
Route::get('/track/{orderNumber}', [TrackingController::class, 'show'])->name('tracking.show');

// Affiliate Link
Route::get('/ref/{code}', [AffiliateController::class, 'link'])->name('affiliate.link');

// Car Data API
Route::get('/api/car-makes/{make}/models', [UserCarController::class, 'getModels'])->name('api.car-models');
Route::get('/api/car-models/{model}/years', [UserCarController::class, 'getYears'])->name('api.car-years');

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
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile/addresses', [ProfileController::class, 'addresses'])->name('profile.addresses');
    Route::post('/profile/addresses', [ProfileController::class, 'storeAddress'])->name('profile.addresses.store');
    Route::post('/profile/addresses/{address}/default', [ProfileController::class, 'setDefaultAddress'])->name('profile.addresses.setDefault');
    Route::delete('/profile/addresses/{address}', [ProfileController::class, 'destroyAddress'])->name('profile.addresses.destroy');
    Route::get('/profile/settings', [ProfileController::class, 'settings'])->name('profile.settings');
    Route::put('/profile/settings', [ProfileController::class, 'updateSettings'])->name('profile.settings.update');
    
    // User Cars
    Route::get('/my-cars', [UserCarController::class, 'index'])->name('cars.index');
    Route::post('/my-cars', [UserCarController::class, 'store'])->name('cars.store');
    Route::put('/my-cars/{car}', [UserCarController::class, 'update'])->name('cars.update');
    Route::delete('/my-cars/{car}', [UserCarController::class, 'destroy'])->name('cars.destroy');
    Route::post('/my-cars/{car}/default', [UserCarController::class, 'setDefault'])->name('cars.setDefault');
    Route::get('/my-cars/{car}/compatible-products', [UserCarController::class, 'compatibleProducts'])->name('cars.compatible');
    Route::get('/my-cars/{car}/maintenance', [UserCarController::class, 'maintenanceSchedule'])->name('cars.maintenance');
    
    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/update/{item}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{item}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::post('/cart/coupon', [CouponController::class, 'apply'])->name('cart.applyCoupon');
    Route::delete('/cart/coupon', [CouponController::class, 'remove'])->name('cart.removeCoupon');
    
    // Checkout
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout.index');
    Route::post('/checkout', [OrderController::class, 'placeOrder'])->name('checkout.process');
    
    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::get('/orders/{order}/track', [OrderController::class, 'track'])->name('orders.track');
    Route::get('/orders/{order}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');
    Route::post('/orders/{order}/reorder', [OrderController::class, 'reorder'])->name('orders.reorder');
    
    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/add/{product}', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::delete('/wishlist/remove/{wishlist}', [WishlistController::class, 'remove'])->name('wishlist.remove');
    Route::post('/wishlist/move-to-cart/{wishlist}', [WishlistController::class, 'moveToCart'])->name('wishlist.moveToCart');
    
    // Wallet
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::post('/wallet/deposit', [WalletController::class, 'deposit'])->name('wallet.deposit');
    Route::post('/wallet/withdraw', [WalletController::class, 'withdraw'])->name('wallet.withdraw');
    Route::post('/wallet/transfer', [WalletController::class, 'transfer'])->name('wallet.transfer');
    Route::get('/wallet/transactions', [WalletController::class, 'transactions'])->name('wallet.transactions');
    
    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::delete('/notifications', [NotificationController::class, 'destroyAll'])->name('notifications.destroyAll');
    Route::get('/notifications/settings', [NotificationController::class, 'settings'])->name('notifications.settings');
    Route::put('/notifications/settings', [NotificationController::class, 'updateSettings'])->name('notifications.settings.update');
    
    // Messages
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/create', [MessageController::class, 'create'])->name('messages.create');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/{conversation}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{conversation}/reply', [MessageController::class, 'reply'])->name('messages.reply');
    
    // Reviews
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::post('/reviews/{review}/vote', [ReviewController::class, 'vote'])->name('reviews.vote');
    Route::post('/reviews/{review}/report', [ReviewController::class, 'report'])->name('reviews.report');
    
    // Questions
    Route::post('/questions', [QuestionController::class, 'store'])->name('questions.store');
    Route::post('/questions/{question}/answer', [QuestionController::class, 'answer'])->name('questions.answer');
    Route::post('/questions/{question}/helpful', [QuestionController::class, 'voteHelpful'])->name('questions.helpful');
    Route::post('/answers/{answer}/helpful', [QuestionController::class, 'voteAnswerHelpful'])->name('answers.helpful');
    
    // Blog Comments
    Route::post('/blog/{post}/comment', [BlogController::class, 'comment'])->name('blog.comment');
    
    // Returns
    Route::get('/returns', [ReturnController::class, 'index'])->name('returns.index');
    Route::get('/orders/{order}/return', [ReturnController::class, 'create'])->name('returns.create');
    Route::post('/orders/{order}/return', [ReturnController::class, 'store'])->name('returns.store');
    Route::get('/returns/{return}', [ReturnController::class, 'show'])->name('returns.show');
    Route::post('/returns/{return}/cancel', [ReturnController::class, 'cancel'])->name('returns.cancel');
    
    // Price Alerts
    Route::get('/price-alerts', [PriceAlertController::class, 'index'])->name('price-alerts.index');
    Route::post('/price-alerts', [PriceAlertController::class, 'store'])->name('price-alerts.store');
    Route::delete('/price-alerts/{priceAlert}', [PriceAlertController::class, 'destroy'])->name('price-alerts.destroy');
    Route::post('/price-alerts/{priceAlert}/toggle', [PriceAlertController::class, 'toggleActive'])->name('price-alerts.toggle');
    
    // Loyalty Points
    Route::get('/loyalty', [LoyaltyController::class, 'index'])->name('loyalty.index');
    Route::post('/loyalty/redeem', [LoyaltyController::class, 'redeem'])->name('loyalty.redeem');
    Route::get('/loyalty/history', [LoyaltyController::class, 'history'])->name('loyalty.history');
    
    // Coupons
    Route::get('/coupons', [CouponController::class, 'index'])->name('coupons.index');
    
    // Gift Cards
    Route::post('/gift-cards/purchase', [GiftCardController::class, 'purchase'])->name('gift-cards.purchase');
    Route::post('/gift-cards/redeem', [GiftCardController::class, 'redeem'])->name('gift-cards.redeem');
    Route::get('/gift-cards/{giftCard}', [GiftCardController::class, 'show'])->name('gift-cards.show');
    
    // Installments
    Route::get('/installments', [InstallmentController::class, 'index'])->name('installments.index');
    Route::post('/installments/calculate', [InstallmentController::class, 'calculate'])->name('installments.calculate');
    Route::post('/orders/{order}/installment', [InstallmentController::class, 'apply'])->name('installments.apply');
    Route::get('/installments/{installment}', [InstallmentController::class, 'show'])->name('installments.show');
    Route::post('/installment-payments/{payment}/pay', [InstallmentController::class, 'pay'])->name('installment-payments.pay');
    
    // Subscriptions
    Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::post('/subscriptions/{plan}', [SubscriptionController::class, 'subscribe'])->name('subscriptions.subscribe');
    Route::get('/subscriptions/{subscription}', [SubscriptionController::class, 'show'])->name('subscriptions.show');
    Route::post('/subscriptions/{subscription}/cancel', [SubscriptionController::class, 'cancel'])->name('subscriptions.cancel');
    Route::post('/subscriptions/{subscription}/renew', [SubscriptionController::class, 'renew'])->name('subscriptions.renew');
    
    // Auctions
    Route::post('/auctions/{auction}/bid', [AuctionController::class, 'bid'])->name('auctions.bid');
    Route::post('/auctions/{auction}/auto-bid', [AuctionController::class, 'autoBid'])->name('auctions.auto-bid');
    Route::post('/auctions/{auction}/buy-now', [AuctionController::class, 'buyNow'])->name('auctions.buy-now');
    Route::get('/my-bids', [AuctionController::class, 'myBids'])->name('auctions.my-bids');
    Route::get('/my-auctions', [AuctionController::class, 'myAuctions'])->name('auctions.my-auctions');
    Route::get('/auctions/create', [AuctionController::class, 'create'])->name('auctions.create');
    Route::post('/auctions', [AuctionController::class, 'store'])->name('auctions.store');
    
    // Bundles
    Route::post('/bundles/{bundle}/add-to-cart', [BundleController::class, 'addToCart'])->name('bundles.addToCart');
    
    // Flash Sales
    Route::post('/flash-sales/{flashSale}/notify', [FlashSaleController::class, 'notify'])->name('flash-sales.notify');
    Route::post('/flash-sale-items/{item}/purchase', [FlashSaleController::class, 'purchase'])->name('flash-sales.purchase');
    
    // Support Tickets
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/{ticket}', [TicketController::class, 'show'])->name('tickets.show');
    Route::post('/tickets/{ticket}/reply', [TicketController::class, 'reply'])->name('tickets.reply');
    Route::post('/tickets/{ticket}/close', [TicketController::class, 'close'])->name('tickets.close');
    Route::post('/tickets/{ticket}/reopen', [TicketController::class, 'reopen'])->name('tickets.reopen');
    Route::post('/tickets/{ticket}/rate', [TicketController::class, 'rate'])->name('tickets.rate');
    
    // Report Content
    Route::post('/report', [ReportController::class, 'store'])->name('report.store');
    
    // Recommendations
    Route::get('/recommendations', [RecommendationController::class, 'forYou'])->name('recommendations.index');
    Route::get('/recommendations/viewed', [RecommendationController::class, 'basedOnViews'])->name('recommendations.viewed');
    Route::get('/recommendations/trending', [RecommendationController::class, 'trending'])->name('recommendations.trending');
    Route::get('/recommendations/deals', [RecommendationController::class, 'deals'])->name('recommendations.deals');
    
    // Affiliate Program
    Route::get('/affiliate', [AffiliateController::class, 'dashboard'])->name('affiliate.dashboard');
    Route::post('/affiliate/generate-link', [AffiliateController::class, 'generateLink'])->name('affiliate.generate-link');
    Route::get('/affiliate/withdrawals', [AffiliateController::class, 'withdrawals'])->name('affiliate.withdrawals');
    Route::post('/affiliate/withdraw', [AffiliateController::class, 'requestWithdrawal'])->name('affiliate.withdraw');
    Route::get('/affiliate/materials', [AffiliateController::class, 'materials'])->name('affiliate.materials');
    
    // Search History
    Route::get('/search/history', [SearchController::class, 'history'])->name('search.history');
    Route::delete('/search/history', [SearchController::class, 'clearHistory'])->name('search.history.clear');
    
    // Store follow
    Route::post('/stores/{store}/follow', [StoreController::class, 'follow'])->name('stores.follow');
});

/*
|--------------------------------------------------------------------------
| Store Dashboard Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->prefix('store')->name('store.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Store\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/settings', [\App\Http\Controllers\Store\DashboardController::class, 'settings'])->name('settings');
    Route::put('/settings', [\App\Http\Controllers\Store\DashboardController::class, 'updateSettings'])->name('settings.update');
    Route::get('/earnings', [\App\Http\Controllers\Store\DashboardController::class, 'earnings'])->name('earnings');
    Route::post('/earnings/withdraw', [\App\Http\Controllers\Store\DashboardController::class, 'requestWithdrawal'])->name('earnings.withdraw');
    Route::get('/notifications', [\App\Http\Controllers\Store\DashboardController::class, 'notifications'])->name('notifications');
    Route::get('/reviews', [\App\Http\Controllers\Store\DashboardController::class, 'reviews'])->name('reviews');
    Route::post('/reviews/{review}/reply', [\App\Http\Controllers\Store\DashboardController::class, 'replyToReview'])->name('reviews.reply');
    
    // Products
    Route::get('/products', [\App\Http\Controllers\Store\ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [\App\Http\Controllers\Store\ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [\App\Http\Controllers\Store\ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [\App\Http\Controllers\Store\ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [\App\Http\Controllers\Store\ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [\App\Http\Controllers\Store\ProductController::class, 'destroy'])->name('products.destroy');
    Route::patch('/products/{product}/stock', [\App\Http\Controllers\Store\ProductController::class, 'updateStock'])->name('products.stock');
    Route::post('/products/bulk', [\App\Http\Controllers\Store\ProductController::class, 'bulkUpdate'])->name('products.bulk');
    Route::get('/products/import', [\App\Http\Controllers\Store\ProductController::class, 'import'])->name('products.import');
    Route::post('/products/import', [\App\Http\Controllers\Store\ProductController::class, 'processImport'])->name('products.import.process');
    Route::get('/products/export', [\App\Http\Controllers\Store\ProductController::class, 'export'])->name('products.export');
    
    // Orders
    Route::get('/orders', [\App\Http\Controllers\Store\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [\App\Http\Controllers\Store\OrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}/status', [\App\Http\Controllers\Store\OrderController::class, 'updateStatus'])->name('orders.status');
    Route::get('/orders/{order}/invoice', [\App\Http\Controllers\Store\OrderController::class, 'printInvoice'])->name('orders.invoice');
    Route::get('/orders/{order}/shipping-label', [\App\Http\Controllers\Store\OrderController::class, 'printShippingLabel'])->name('orders.shipping-label');
    Route::post('/orders/bulk-print', [\App\Http\Controllers\Store\OrderController::class, 'bulkPrint'])->name('orders.bulk-print');
    Route::post('/orders/{order}/assign-driver', [\App\Http\Controllers\Store\OrderController::class, 'assignDriver'])->name('orders.assign-driver');
    Route::post('/orders/{order}/cancel', [\App\Http\Controllers\Store\OrderController::class, 'cancel'])->name('orders.cancel');
    Route::get('/orders/analytics', [\App\Http\Controllers\Store\OrderController::class, 'analytics'])->name('orders.analytics');
    
    // Coupons
    Route::get('/coupons', [\App\Http\Controllers\Store\CouponController::class, 'index'])->name('coupons.index');
    Route::get('/coupons/create', [\App\Http\Controllers\Store\CouponController::class, 'create'])->name('coupons.create');
    Route::post('/coupons', [\App\Http\Controllers\Store\CouponController::class, 'store'])->name('coupons.store');
    Route::get('/coupons/{coupon}/edit', [\App\Http\Controllers\Store\CouponController::class, 'edit'])->name('coupons.edit');
    Route::put('/coupons/{coupon}', [\App\Http\Controllers\Store\CouponController::class, 'update'])->name('coupons.update');
    Route::delete('/coupons/{coupon}', [\App\Http\Controllers\Store\CouponController::class, 'destroy'])->name('coupons.destroy');
    Route::post('/coupons/{coupon}/toggle', [\App\Http\Controllers\Store\CouponController::class, 'toggleActive'])->name('coupons.toggle');
    
    // Returns
    Route::get('/returns', [\App\Http\Controllers\Store\ReturnController::class, 'index'])->name('returns.index');
    Route::get('/returns/{return}', [\App\Http\Controllers\Store\ReturnController::class, 'show'])->name('returns.show');
    Route::post('/returns/{return}/approve', [\App\Http\Controllers\Store\ReturnController::class, 'approve'])->name('returns.approve');
    Route::post('/returns/{return}/reject', [\App\Http\Controllers\Store\ReturnController::class, 'reject'])->name('returns.reject');
    Route::post('/returns/{return}/receive', [\App\Http\Controllers\Store\ReturnController::class, 'receive'])->name('returns.receive');
    Route::post('/returns/{return}/complete', [\App\Http\Controllers\Store\ReturnController::class, 'complete'])->name('returns.complete');
    
    // Questions
    Route::get('/questions', [\App\Http\Controllers\Store\QuestionController::class, 'index'])->name('questions.index');
    Route::post('/questions/{question}/answer', [\App\Http\Controllers\Store\QuestionController::class, 'answer'])->name('questions.answer');
    Route::post('/questions/{question}/hide', [\App\Http\Controllers\Store\QuestionController::class, 'hide'])->name('questions.hide');
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
    Route::post('/deliveries/{delivery}/location', [DriverController::class, 'updateLocation'])->name('deliveries.location');
    
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
