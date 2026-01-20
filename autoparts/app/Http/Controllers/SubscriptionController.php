<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        $plans = SubscriptionPlan::active()->orderBy('price')->get();
        $currentSubscription = auth()->user()->activeSubscription;

        return view('subscriptions.index', compact('plans', 'currentSubscription'));
    }

    public function subscribe(Request $request, SubscriptionPlan $plan)
    {
        $user = auth()->user();

        // Check if already subscribed
        if ($user->activeSubscription) {
            return back()->with('error', 'لديك اشتراك نشط بالفعل');
        }

        // Check wallet balance
        $wallet = $user->wallet;
        if (!$wallet || $wallet->balance < $plan->price) {
            return back()->with('error', 'رصيد المحفظة غير كافي');
        }

        // Deduct from wallet
        $wallet->debit($plan->price, 'اشتراك في خطة: ' . $plan->name);

        // Create subscription
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
            'starts_at' => now(),
            'ends_at' => now()->addDays($plan->duration_days),
            'status' => 'active',
            'amount_paid' => $plan->price,
        ]);

        return redirect()->route('subscriptions.show', $subscription)->with('success', 'تم الاشتراك بنجاح');
    }

    public function show(Subscription $subscription)
    {
        if ($subscription->user_id !== auth()->id()) {
            abort(403);
        }

        $subscription->load('plan');

        return view('subscriptions.show', compact('subscription'));
    }

    public function cancel(Subscription $subscription)
    {
        if ($subscription->user_id !== auth()->id()) {
            abort(403);
        }

        if ($subscription->status !== 'active') {
            return back()->with('error', 'لا يمكن إلغاء هذا الاشتراك');
        }

        $subscription->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        return back()->with('success', 'تم إلغاء الاشتراك');
    }

    public function renew(Subscription $subscription)
    {
        if ($subscription->user_id !== auth()->id()) {
            abort(403);
        }

        $plan = $subscription->plan;
        $wallet = auth()->user()->wallet;

        if (!$wallet || $wallet->balance < $plan->price) {
            return back()->with('error', 'رصيد المحفظة غير كافي');
        }

        $wallet->debit($plan->price, 'تجديد اشتراك: ' . $plan->name);

        $subscription->update([
            'ends_at' => $subscription->ends_at->addDays($plan->duration_days),
            'status' => 'active',
        ]);

        return back()->with('success', 'تم تجديد الاشتراك');
    }
}
