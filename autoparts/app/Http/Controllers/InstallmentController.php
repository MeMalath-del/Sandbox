<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Installment;
use App\Models\InstallmentPayment;
use Illuminate\Http\Request;

class InstallmentController extends Controller
{
    public function index()
    {
        $installments = Installment::where('user_id', auth()->id())
            ->with(['order', 'payments'])
            ->latest()
            ->paginate(20);

        return view('installments.index', compact('installments'));
    }

    public function calculate(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:500',
            'months' => 'required|integer|in:3,6,9,12',
        ]);

        $amount = $request->amount;
        $months = $request->months;
        
        // Calculate installment with interest (example: 0% for 3 months, 5% for 6, 8% for 9, 10% for 12)
        $interestRates = [3 => 0, 6 => 5, 9 => 8, 12 => 10];
        $interestRate = $interestRates[$months] ?? 0;
        
        $totalWithInterest = $amount * (1 + $interestRate / 100);
        $monthlyPayment = $totalWithInterest / $months;
        
        $schedule = [];
        for ($i = 1; $i <= $months; $i++) {
            $schedule[] = [
                'month' => $i,
                'due_date' => now()->addMonths($i)->format('Y-m-d'),
                'amount' => round($monthlyPayment, 2),
            ];
        }

        return response()->json([
            'original_amount' => $amount,
            'interest_rate' => $interestRate,
            'total_amount' => round($totalWithInterest, 2),
            'monthly_payment' => round($monthlyPayment, 2),
            'months' => $months,
            'schedule' => $schedule,
        ]);
    }

    public function apply(Request $request, Order $order)
    {
        $request->validate([
            'months' => 'required|integer|in:3,6,9,12',
        ]);

        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if ($order->total < 500) {
            return back()->with('error', 'الحد الأدنى للتقسيط 500 ر.س');
        }

        $months = $request->months;
        $interestRates = [3 => 0, 6 => 5, 9 => 8, 12 => 10];
        $interestRate = $interestRates[$months];
        
        $totalWithInterest = $order->total * (1 + $interestRate / 100);
        $monthlyPayment = $totalWithInterest / $months;

        $installment = Installment::create([
            'user_id' => auth()->id(),
            'order_id' => $order->id,
            'original_amount' => $order->total,
            'total_amount' => $totalWithInterest,
            'monthly_amount' => $monthlyPayment,
            'interest_rate' => $interestRate,
            'months' => $months,
            'status' => 'active',
        ]);

        // Create payment schedule
        for ($i = 1; $i <= $months; $i++) {
            InstallmentPayment::create([
                'installment_id' => $installment->id,
                'installment_number' => $i,
                'amount' => $monthlyPayment,
                'due_date' => now()->addMonths($i),
                'status' => 'pending',
            ]);
        }

        return redirect()->route('installments.show', $installment)->with('success', 'تم إنشاء خطة التقسيط');
    }

    public function show(Installment $installment)
    {
        if ($installment->user_id !== auth()->id()) {
            abort(403);
        }

        $installment->load(['order', 'payments']);

        return view('installments.show', compact('installment'));
    }

    public function pay(Request $request, InstallmentPayment $payment)
    {
        if ($payment->installment->user_id !== auth()->id()) {
            abort(403);
        }

        if ($payment->status === 'paid') {
            return back()->with('error', 'تم دفع هذا القسط مسبقاً');
        }

        // Process payment logic here
        $payment->update([
            'status' => 'paid',
            'paid_at' => now(),
            'payment_method' => $request->payment_method ?? 'wallet',
        ]);

        // Check if all installments are paid
        $allPaid = $payment->installment->payments()->where('status', '!=', 'paid')->count() === 0;
        
        if ($allPaid) {
            $payment->installment->update(['status' => 'completed', 'completed_at' => now()]);
        }

        return back()->with('success', 'تم دفع القسط بنجاح');
    }
}
