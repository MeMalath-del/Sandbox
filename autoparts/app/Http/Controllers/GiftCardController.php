<?php

namespace App\Http\Controllers;

use App\Models\GiftCard;
use App\Models\GiftCardTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GiftCardController extends Controller
{
    public function index()
    {
        $denominations = [50, 100, 200, 500, 1000];
        $myGiftCards = auth()->check() 
            ? GiftCard::where('purchaser_id', auth()->id())->orWhere('recipient_id', auth()->id())->latest()->get()
            : collect();

        return view('gift-cards.index', compact('denominations', 'myGiftCards'));
    }

    public function purchase(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:50|max:5000',
            'recipient_email' => 'nullable|email',
            'recipient_name' => 'nullable|string|max:255',
            'message' => 'nullable|string|max:500',
            'design' => 'nullable|string',
            'send_date' => 'nullable|date|after_or_equal:today',
        ]);

        $giftCard = GiftCard::create([
            'code' => Str::upper(Str::random(4) . '-' . Str::random(4) . '-' . Str::random(4)),
            'amount' => $request->amount,
            'balance' => $request->amount,
            'purchaser_id' => auth()->id(),
            'recipient_email' => $request->recipient_email,
            'recipient_name' => $request->recipient_name,
            'message' => $request->message,
            'design' => $request->design ?? 'default',
            'send_date' => $request->send_date ?? now(),
            'expires_at' => now()->addYear(),
            'status' => 'active',
        ]);

        // Add to cart or process payment
        // For now, we'll just redirect to checkout
        session(['gift_card_purchase' => $giftCard->id]);

        return redirect()->route('checkout.gift-card');
    }

    public function redeem(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $code = str_replace('-', '', strtoupper($request->code));
        $giftCard = GiftCard::where('code', 'like', '%' . $code . '%')->first();

        if (!$giftCard) {
            return back()->with('error', 'كود البطاقة غير صحيح');
        }

        if ($giftCard->status !== 'active') {
            return back()->with('error', 'هذه البطاقة غير صالحة');
        }

        if ($giftCard->expires_at && $giftCard->expires_at < now()) {
            return back()->with('error', 'انتهت صلاحية هذه البطاقة');
        }

        if ($giftCard->balance <= 0) {
            return back()->with('error', 'لا يوجد رصيد في هذه البطاقة');
        }

        // Add balance to user's wallet
        $wallet = auth()->user()->wallet ?? auth()->user()->wallet()->create(['balance' => 0]);
        $wallet->credit($giftCard->balance, 'استخدام بطاقة هدية: ' . $giftCard->code);

        GiftCardTransaction::create([
            'gift_card_id' => $giftCard->id,
            'user_id' => auth()->id(),
            'type' => 'redeem',
            'amount' => $giftCard->balance,
        ]);

        $giftCard->update([
            'balance' => 0,
            'recipient_id' => auth()->id(),
            'redeemed_at' => now(),
            'status' => 'redeemed',
        ]);

        return back()->with('success', 'تم إضافة ' . number_format($giftCard->amount, 2) . ' ر.س إلى محفظتك');
    }

    public function checkBalance(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $code = str_replace('-', '', strtoupper($request->code));
        $giftCard = GiftCard::where('code', 'like', '%' . $code . '%')->first();

        if (!$giftCard) {
            return response()->json(['success' => false, 'message' => 'كود البطاقة غير صحيح']);
        }

        return response()->json([
            'success' => true,
            'balance' => $giftCard->balance,
            'original_amount' => $giftCard->amount,
            'expires_at' => $giftCard->expires_at?->format('Y-m-d'),
            'status' => $giftCard->status,
        ]);
    }

    public function show(GiftCard $giftCard)
    {
        if ($giftCard->purchaser_id !== auth()->id() && $giftCard->recipient_id !== auth()->id()) {
            abort(403);
        }

        $transactions = $giftCard->transactions()->with('user')->latest()->get();

        return view('gift-cards.show', compact('giftCard', 'transactions'));
    }
}
