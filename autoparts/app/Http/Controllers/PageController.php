<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function show($slug)
    {
        $page = Page::where('slug', $slug)->active()->firstOrFail();
        $page->increment('views_count');

        return view('pages.show', compact('page'));
    }

    public function about()
    {
        $page = Page::where('slug', 'about')->first();
        $stats = [
            'products' => \App\Models\Product::count(),
            'stores' => \App\Models\Store::count(),
            'customers' => \App\Models\User::where('role', 'buyer')->count(),
            'orders' => \App\Models\Order::where('status', 'delivered')->count(),
        ];

        return view('pages.about', compact('page', 'stats'));
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function submitContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10|max:2000',
        ]);

        \App\Models\ContactMessage::create($request->all());

        return back()->with('success', 'تم إرسال رسالتك بنجاح');
    }

    public function terms()
    {
        $page = Page::where('slug', 'terms')->first();
        return view('pages.terms', compact('page'));
    }

    public function privacy()
    {
        $page = Page::where('slug', 'privacy')->first();
        return view('pages.privacy', compact('page'));
    }

    public function shipping()
    {
        $page = Page::where('slug', 'shipping')->first();
        return view('pages.shipping', compact('page'));
    }

    public function returns()
    {
        $page = Page::where('slug', 'returns-policy')->first();
        return view('pages.returns', compact('page'));
    }

    public function warranty()
    {
        $page = Page::where('slug', 'warranty')->first();
        return view('pages.warranty', compact('page'));
    }
}
