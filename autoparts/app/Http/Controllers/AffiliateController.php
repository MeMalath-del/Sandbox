<?php

namespace App\Http\Controllers;

use App\Models\Affiliate;
use App\Models\AffiliateClick;
use App\Models\AffiliateCommission;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AffiliateController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $affiliate = $user->affiliate ?? $user->affiliate()->create([
            'code' => Str::upper(Str::random(8)),
            'commission_rate' => 5,
            'status' => 'pending',
        ]);

        $stats = [
            'total_clicks' => $affiliate->clicks()->count(),
            'total_orders' => $affiliate->commissions()->count(),
            'total_earnings' => $affiliate->commissions()->where('status', 'paid')->sum('amount'),
            'pending_earnings' => $affiliate->commissions()->where('status', 'pending')->sum('amount'),
        ];

        $recentCommissions = $affiliate->commissions()
            ->with('order')
            ->latest()
            ->limit(10)
            ->get();

        $clicksChart = $affiliate->clicks()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->limit(30)
            ->get();

        return view('affiliate.dashboard', compact('affiliate', 'stats', 'recentCommissions', 'clicksChart'));
    }

    public function link($code)
    {
        $affiliate = Affiliate::where('code', $code)->active()->firstOrFail();

        AffiliateClick::create([
            'affiliate_id' => $affiliate->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'referer' => request()->header('referer'),
        ]);

        // Store affiliate code in cookie for 30 days
        return redirect('/')->withCookie(cookie('affiliate_code', $code, 60 * 24 * 30));
    }

    public function generateLink(Request $request)
    {
        $user = auth()->user();
        $affiliate = $user->affiliate;

        if (!$affiliate) {
            return back()->with('error', 'لم يتم تفعيل حسابك كشريك بعد');
        }

        $url = $request->url ?? url('/');
        $affiliateUrl = $url . (str_contains($url, '?') ? '&' : '?') . 'ref=' . $affiliate->code;

        return response()->json([
            'url' => $affiliateUrl,
            'short_url' => route('affiliate.link', $affiliate->code),
        ]);
    }

    public function withdrawals()
    {
        $affiliate = auth()->user()->affiliate;

        $withdrawals = $affiliate->withdrawals()
            ->latest()
            ->paginate(20);

        return view('affiliate.withdrawals', compact('affiliate', 'withdrawals'));
    }

    public function requestWithdrawal(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100',
            'payment_method' => 'required|in:bank,paypal',
        ]);

        $affiliate = auth()->user()->affiliate;

        if ($request->amount > $affiliate->available_balance) {
            return back()->with('error', 'الرصيد غير كافي');
        }

        $affiliate->withdrawals()->create([
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'status' => 'pending',
        ]);

        return back()->with('success', 'تم إرسال طلب السحب');
    }

    public function materials()
    {
        $banners = \App\Models\AffiliateMaterial::where('type', 'banner')->active()->get();
        $texts = \App\Models\AffiliateMaterial::where('type', 'text')->active()->get();

        return view('affiliate.materials', compact('banners', 'texts'));
    }
}
