<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function index()
    {
        $wallet = auth()->user()->wallet ?? auth()->user()->wallet()->create(['balance' => 0]);
        
        $transactions = WalletTransaction::where('wallet_id', $wallet->id)
            ->latest()
            ->paginate(20);

        $monthlyStats = WalletTransaction::where('wallet_id', $wallet->id)
            ->selectRaw('MONTH(created_at) as month, 
                         SUM(CASE WHEN type = "credit" THEN amount ELSE 0 END) as credits,
                         SUM(CASE WHEN type = "debit" THEN amount ELSE 0 END) as debits')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->get();

        return view('wallet.index', compact('wallet', 'transactions', 'monthlyStats'));
    }

    public function deposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10|max:10000',
            'payment_method' => 'required|in:card,bank_transfer,mada',
        ]);

        $wallet = auth()->user()->wallet ?? auth()->user()->wallet()->create(['balance' => 0]);

        // Create pending transaction
        $transaction = WalletTransaction::create([
            'wallet_id' => $wallet->id,
            'type' => 'credit',
            'amount' => $request->amount,
            'description' => 'إيداع في المحفظة',
            'payment_method' => $request->payment_method,
            'status' => 'pending',
            'reference' => 'DEP-' . strtoupper(uniqid()),
        ]);

        // Here you would integrate with payment gateway
        // For demo, we'll auto-approve
        $wallet->increment('balance', $request->amount);
        $transaction->update(['status' => 'completed']);

        return back()->with('success', 'تم إضافة ' . number_format($request->amount, 2) . ' ر.س إلى محفظتك');
    }

    public function withdraw(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:50',
            'bank_name' => 'required|string',
            'account_number' => 'required|string',
            'account_holder' => 'required|string',
        ]);

        $wallet = auth()->user()->wallet;

        if (!$wallet || $wallet->balance < $request->amount) {
            return back()->with('error', 'رصيد غير كافي');
        }

        WalletTransaction::create([
            'wallet_id' => $wallet->id,
            'type' => 'withdrawal',
            'amount' => $request->amount,
            'description' => 'سحب إلى حساب بنكي',
            'status' => 'pending',
            'reference' => 'WTH-' . strtoupper(uniqid()),
            'metadata' => [
                'bank_name' => $request->bank_name,
                'account_number' => $request->account_number,
                'account_holder' => $request->account_holder,
            ],
        ]);

        $wallet->decrement('balance', $request->amount);

        return back()->with('success', 'تم إرسال طلب السحب وسيتم معالجته خلال 3-5 أيام عمل');
    }

    public function transfer(Request $request)
    {
        $request->validate([
            'recipient_email' => 'required|email|exists:users,email',
            'amount' => 'required|numeric|min:1',
        ]);

        $sender = auth()->user();
        $recipient = \App\Models\User::where('email', $request->recipient_email)->first();

        if ($sender->id === $recipient->id) {
            return back()->with('error', 'لا يمكنك التحويل لنفسك');
        }

        $senderWallet = $sender->wallet;
        if (!$senderWallet || $senderWallet->balance < $request->amount) {
            return back()->with('error', 'رصيد غير كافي');
        }

        $recipientWallet = $recipient->wallet ?? $recipient->wallet()->create(['balance' => 0]);

        $reference = 'TRF-' . strtoupper(uniqid());

        // Debit sender
        $senderWallet->decrement('balance', $request->amount);
        WalletTransaction::create([
            'wallet_id' => $senderWallet->id,
            'type' => 'debit',
            'amount' => $request->amount,
            'description' => 'تحويل إلى ' . $recipient->name,
            'status' => 'completed',
            'reference' => $reference,
        ]);

        // Credit recipient
        $recipientWallet->increment('balance', $request->amount);
        WalletTransaction::create([
            'wallet_id' => $recipientWallet->id,
            'type' => 'credit',
            'amount' => $request->amount,
            'description' => 'تحويل من ' . $sender->name,
            'status' => 'completed',
            'reference' => $reference,
        ]);

        return back()->with('success', 'تم التحويل بنجاح');
    }

    public function transactions()
    {
        $wallet = auth()->user()->wallet;
        
        $transactions = WalletTransaction::where('wallet_id', $wallet?->id)
            ->latest()
            ->paginate(50);

        return view('wallet.transactions', compact('transactions'));
    }
}
