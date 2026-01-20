<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\AuctionBid;
use Illuminate\Http\Request;

class AuctionController extends Controller
{
    public function index(Request $request)
    {
        $query = Auction::active()
            ->with(['product.primaryImage', 'store', 'highestBid']);

        if ($request->category) {
            $query->whereHas('product', fn($q) => $q->where('category_id', $request->category));
        }

        if ($request->ending_soon) {
            $query->where('ends_at', '<=', now()->addHours(24));
        }

        $auctions = $query->orderBy('ends_at')->paginate(24);

        return view('auctions.index', compact('auctions'));
    }

    public function show(Auction $auction)
    {
        $auction->load(['product.images', 'store', 'bids.user']);

        $myBids = auth()->check() 
            ? $auction->bids()->where('user_id', auth()->id())->latest()->get()
            : collect();

        return view('auctions.show', compact('auction', 'myBids'));
    }

    public function bid(Request $request, Auction $auction)
    {
        $request->validate([
            'amount' => 'required|numeric|min:' . ($auction->current_price + $auction->min_increment),
        ]);

        if ($auction->status !== 'active' || $auction->ends_at < now()) {
            return back()->with('error', 'المزاد منتهي');
        }

        if ($auction->user_id === auth()->id()) {
            return back()->with('error', 'لا يمكنك المزايدة على مزادك');
        }

        // Check if user has sufficient wallet balance as guarantee
        $wallet = auth()->user()->wallet;
        if (!$wallet || $wallet->balance < $request->amount * 0.1) {
            return back()->with('error', 'يجب أن يكون لديك 10% من قيمة المزايدة في المحفظة كضمان');
        }

        $bid = AuctionBid::create([
            'auction_id' => $auction->id,
            'user_id' => auth()->id(),
            'amount' => $request->amount,
            'is_auto_bid' => false,
        ]);

        $auction->update(['current_price' => $request->amount]);

        // Extend auction if bid in last 5 minutes
        if ($auction->ends_at->diffInMinutes(now()) < 5) {
            $auction->update(['ends_at' => $auction->ends_at->addMinutes(5)]);
        }

        return back()->with('success', 'تم تسجيل مزايدتك');
    }

    public function autoBid(Request $request, Auction $auction)
    {
        $request->validate([
            'max_amount' => 'required|numeric|min:' . ($auction->current_price + $auction->min_increment),
        ]);

        auth()->user()->autoBids()->updateOrCreate(
            ['auction_id' => $auction->id],
            ['max_amount' => $request->max_amount, 'is_active' => true]
        );

        return back()->with('success', 'تم تفعيل المزايدة التلقائية');
    }

    public function myBids()
    {
        $bids = AuctionBid::where('user_id', auth()->id())
            ->with(['auction.product.primaryImage'])
            ->latest()
            ->paginate(20);

        $wonAuctions = Auction::where('winner_id', auth()->id())
            ->with('product')
            ->latest()
            ->get();

        return view('auctions.my-bids', compact('bids', 'wonAuctions'));
    }

    public function myAuctions()
    {
        $auctions = Auction::where('user_id', auth()->id())
            ->with(['product.primaryImage', 'bids'])
            ->latest()
            ->paginate(20);

        return view('auctions.my-auctions', compact('auctions'));
    }

    public function create()
    {
        $products = auth()->user()->store?->products()->doesntHave('auction')->get() ?? collect();

        return view('auctions.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'starting_price' => 'required|numeric|min:1',
            'reserve_price' => 'nullable|numeric|gte:starting_price',
            'buy_now_price' => 'nullable|numeric|gt:starting_price',
            'min_increment' => 'required|numeric|min:1',
            'duration_hours' => 'required|integer|min:24|max:168',
        ]);

        $auction = Auction::create([
            'product_id' => $request->product_id,
            'user_id' => auth()->id(),
            'store_id' => auth()->user()->store->id,
            'starting_price' => $request->starting_price,
            'current_price' => $request->starting_price,
            'reserve_price' => $request->reserve_price,
            'buy_now_price' => $request->buy_now_price,
            'min_increment' => $request->min_increment,
            'starts_at' => now(),
            'ends_at' => now()->addHours($request->duration_hours),
            'status' => 'active',
        ]);

        return redirect()->route('auctions.show', $auction)->with('success', 'تم إنشاء المزاد');
    }

    public function buyNow(Auction $auction)
    {
        if (!$auction->buy_now_price || $auction->status !== 'active') {
            return back()->with('error', 'لا يمكن الشراء المباشر');
        }

        // Create order with buy_now_price
        // ... order creation logic

        $auction->update([
            'status' => 'sold',
            'winner_id' => auth()->id(),
            'sold_at' => now(),
            'final_price' => $auction->buy_now_price,
        ]);

        return redirect()->route('orders.index')->with('success', 'تم الشراء بنجاح');
    }
}
