<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'reportable_type' => 'required|in:product,review,store,user,comment',
            'reportable_id' => 'required|integer',
            'reason' => 'required|string|max:500',
            'category' => 'required|in:spam,inappropriate,fake,counterfeit,harassment,other',
        ]);

        $reportableClass = match($request->reportable_type) {
            'product' => Product::class,
            'review' => Review::class,
            'store' => \App\Models\Store::class,
            'user' => \App\Models\User::class,
            'comment' => \App\Models\BlogComment::class,
        };

        // Check for duplicate reports
        $existing = Report::where('user_id', auth()->id())
            ->where('reportable_type', $reportableClass)
            ->where('reportable_id', $request->reportable_id)
            ->exists();

        if ($existing) {
            return back()->with('error', 'لقد قمت بالإبلاغ عن هذا المحتوى مسبقاً');
        }

        Report::create([
            'user_id' => auth()->id(),
            'reportable_type' => $reportableClass,
            'reportable_id' => $request->reportable_id,
            'reason' => $request->reason,
            'category' => $request->category,
            'status' => 'pending',
        ]);

        return back()->with('success', 'تم إرسال البلاغ وسيتم مراجعته');
    }
}
