<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\FaqCategory;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        $categories = FaqCategory::with(['faqs' => function($q) {
            $q->active()->orderBy('sort_order');
        }])->orderBy('sort_order')->get();

        return view('faq.index', compact('categories'));
    }

    public function search(Request $request)
    {
        $query = $request->q;
        
        $faqs = Faq::active()
            ->where(function($q) use ($query) {
                $q->where('question', 'like', "%{$query}%")
                  ->orWhere('answer', 'like', "%{$query}%");
            })
            ->with('category')
            ->limit(20)
            ->get();

        return response()->json($faqs);
    }

    public function helpful(Request $request, Faq $faq)
    {
        $request->validate([
            'helpful' => 'required|boolean',
        ]);

        if ($request->helpful) {
            $faq->increment('helpful_count');
        } else {
            $faq->increment('not_helpful_count');
        }

        return response()->json(['success' => true]);
    }
}
