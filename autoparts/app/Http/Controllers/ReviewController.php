<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use App\Models\Store;
use App\Models\ReviewVote;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'reviewable_type' => 'required|in:product,store,driver',
            'reviewable_id' => 'required|integer',
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'comment' => 'required|string|min:10',
            'pros' => 'nullable|string',
            'cons' => 'nullable|string',
        ]);

        $reviewableClass = match($request->reviewable_type) {
            'product' => Product::class,
            'store' => Store::class,
            default => Product::class,
        };

        $reviewable = $reviewableClass::findOrFail($request->reviewable_id);

        // Check if user already reviewed
        $existing = Review::where('user_id', auth()->id())
            ->where('reviewable_type', $reviewableClass)
            ->where('reviewable_id', $request->reviewable_id)
            ->first();

        if ($existing) {
            return back()->with('error', 'لقد قمت بتقييم هذا المنتج مسبقاً');
        }

        // Check if user purchased the product
        $hasPurchased = auth()->user()->orders()
            ->whereHas('items', function($q) use ($request) {
                $q->where('product_id', $request->reviewable_id);
            })
            ->where('status', 'delivered')
            ->exists();

        $review = Review::create([
            'user_id' => auth()->id(),
            'reviewable_type' => $reviewableClass,
            'reviewable_id' => $request->reviewable_id,
            'rating' => $request->rating,
            'title' => $request->title,
            'comment' => $request->comment,
            'pros' => $request->pros,
            'cons' => $request->cons,
            'is_verified_purchase' => $hasPurchased,
            'status' => 'pending',
        ]);

        // Handle images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('reviews', 'public');
                $review->images()->create(['image_path' => $path]);
            }
        }

        return back()->with('success', 'تم إرسال تقييمك وسيتم مراجعته قريباً');
    }

    public function vote(Request $request, Review $review)
    {
        $request->validate([
            'vote' => 'required|in:helpful,not_helpful',
        ]);

        $existingVote = ReviewVote::where('review_id', $review->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existingVote) {
            if ($existingVote->vote === $request->vote) {
                $existingVote->delete();
                return back()->with('success', 'تم إلغاء تصويتك');
            }
            $existingVote->update(['vote' => $request->vote]);
        } else {
            ReviewVote::create([
                'review_id' => $review->id,
                'user_id' => auth()->id(),
                'vote' => $request->vote,
            ]);
        }

        // Update review counts
        $review->update([
            'helpful_count' => $review->votes()->where('vote', 'helpful')->count(),
            'not_helpful_count' => $review->votes()->where('vote', 'not_helpful')->count(),
        ]);

        return back()->with('success', 'تم تسجيل تصويتك');
    }

    public function report(Request $request, Review $review)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $review->reports()->create([
            'user_id' => auth()->id(),
            'reason' => $request->reason,
        ]);

        return back()->with('success', 'تم الإبلاغ عن التقييم');
    }
}
