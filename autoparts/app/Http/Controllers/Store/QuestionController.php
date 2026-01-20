<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        $store = auth()->user()->store;

        $query = Question::whereHas('product', fn($q) => $q->where('store_id', $store->id))
            ->with(['product', 'user', 'answers']);

        if ($request->status === 'unanswered') {
            $query->whereDoesntHave('answers', fn($q) => $q->where('is_store_answer', true));
        }

        if ($request->status === 'answered') {
            $query->whereHas('answers', fn($q) => $q->where('is_store_answer', true));
        }

        $questions = $query->latest()->paginate(20);

        $stats = [
            'total' => Question::whereHas('product', fn($q) => $q->where('store_id', $store->id))->count(),
            'unanswered' => Question::whereHas('product', fn($q) => $q->where('store_id', $store->id))
                ->whereDoesntHave('answers', fn($q) => $q->where('is_store_answer', true))->count(),
        ];

        return view('store.questions.index', compact('questions', 'stats'));
    }

    public function answer(Request $request, Question $question)
    {
        if ($question->product->store_id !== auth()->user()->store->id) {
            abort(403);
        }

        $request->validate([
            'answer' => 'required|string|min:5|max:2000',
        ]);

        Answer::create([
            'question_id' => $question->id,
            'user_id' => auth()->id(),
            'answer' => $request->answer,
            'is_store_answer' => true,
            'status' => 'approved',
        ]);

        // Notify the question author
        // ...

        return back()->with('success', 'تم إرسال الإجابة');
    }

    public function hide(Question $question)
    {
        if ($question->product->store_id !== auth()->user()->store->id) {
            abort(403);
        }

        $question->update(['status' => 'hidden']);

        return back()->with('success', 'تم إخفاء السؤال');
    }
}
