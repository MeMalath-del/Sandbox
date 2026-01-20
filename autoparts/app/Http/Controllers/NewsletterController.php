<?php

namespace App\Http\Controllers;

use App\Models\Newsletter;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $subscriber = NewsletterSubscriber::where('email', $request->email)->first();

        if ($subscriber) {
            if ($subscriber->status === 'active') {
                return response()->json(['success' => false, 'message' => 'أنت مشترك بالفعل']);
            }
            $subscriber->update(['status' => 'active']);
        } else {
            NewsletterSubscriber::create([
                'email' => $request->email,
                'user_id' => auth()->id(),
                'token' => Str::random(32),
                'status' => 'active',
                'preferences' => [
                    'promotions' => true,
                    'new_products' => true,
                    'blog' => true,
                ],
            ]);
        }

        return response()->json(['success' => true, 'message' => 'تم الاشتراك بنجاح']);
    }

    public function unsubscribe(Request $request)
    {
        $subscriber = NewsletterSubscriber::where('token', $request->token)->firstOrFail();
        $subscriber->update(['status' => 'unsubscribed', 'unsubscribed_at' => now()]);

        return view('newsletter.unsubscribed');
    }

    public function preferences(Request $request)
    {
        $subscriber = NewsletterSubscriber::where('token', $request->token)->firstOrFail();

        return view('newsletter.preferences', compact('subscriber'));
    }

    public function updatePreferences(Request $request)
    {
        $subscriber = NewsletterSubscriber::where('token', $request->token)->firstOrFail();

        $subscriber->update([
            'preferences' => [
                'promotions' => $request->boolean('promotions'),
                'new_products' => $request->boolean('new_products'),
                'blog' => $request->boolean('blog'),
            ],
        ]);

        return back()->with('success', 'تم حفظ التفضيلات');
    }
}
