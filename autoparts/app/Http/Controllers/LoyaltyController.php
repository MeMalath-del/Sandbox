<?php

namespace App\Http\Controllers;

use App\Models\LoyaltyPoints;
use App\Models\LoyaltyTransaction;
use Illuminate\Http\Request;

class LoyaltyController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $loyaltyPoints = $user->loyaltyPoints ?? $user->loyaltyPoints()->create(['balance' => 0]);
        
        $transactions = LoyaltyTransaction::where('user_id', $user->id)
            ->latest()
            ->paginate(20);

        $tiers = [
            ['name' => 'برونزي', 'min' => 0, 'max' => 999, 'discount' => 0, 'icon' => 'bi-award'],
            ['name' => 'فضي', 'min' => 1000, 'max' => 4999, 'discount' => 5, 'icon' => 'bi-award-fill'],
            ['name' => 'ذهبي', 'min' => 5000, 'max' => 9999, 'discount' => 10, 'icon' => 'bi-trophy'],
            ['name' => 'بلاتيني', 'min' => 10000, 'max' => PHP_INT_MAX, 'discount' => 15, 'icon' => 'bi-gem'],
        ];

        $currentTier = collect($tiers)->first(fn($t) => $loyaltyPoints->total_earned >= $t['min'] && $loyaltyPoints->total_earned <= $t['max']);
        $nextTier = collect($tiers)->first(fn($t) => $t['min'] > $loyaltyPoints->total_earned);

        return view('loyalty.index', compact('loyaltyPoints', 'transactions', 'tiers', 'currentTier', 'nextTier'));
    }

    public function redeem(Request $request)
    {
        $request->validate([
            'points' => 'required|integer|min:100',
        ]);

        $user = auth()->user();
        $loyaltyPoints = $user->loyaltyPoints;

        if ($request->points > $loyaltyPoints->balance) {
            return back()->with('error', 'رصيد النقاط غير كافي');
        }

        // 100 points = 1 SAR
        $amount = $request->points / 100;

        // Deduct points
        $loyaltyPoints->decrement('balance', $request->points);

        // Add to wallet
        $wallet = $user->wallet ?? $user->wallet()->create(['balance' => 0]);
        $wallet->credit($amount, 'تحويل نقاط الولاء');

        // Log transaction
        LoyaltyTransaction::create([
            'user_id' => $user->id,
            'type' => 'redeemed',
            'points' => -$request->points,
            'description' => 'تحويل إلى المحفظة',
            'balance_after' => $loyaltyPoints->balance,
        ]);

        return back()->with('success', 'تم تحويل ' . $request->points . ' نقطة إلى ' . number_format($amount, 2) . ' ر.س');
    }

    public function history()
    {
        $transactions = LoyaltyTransaction::where('user_id', auth()->id())
            ->latest()
            ->paginate(50);

        return view('loyalty.history', compact('transactions'));
    }
}
