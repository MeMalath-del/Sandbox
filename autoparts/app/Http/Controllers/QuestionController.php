<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Answer;
use App\Models\Product;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'question' => 'required|string|min:10|max:1000',
        ]);

        Question::create([
            'product_id' => $request->product_id,
            'user_id' => auth()->id(),
            'question' => $request->question,
            'status' => 'pending',
        ]);

        return back()->with('success', 'تم إرسال سؤالك وسيتم الرد عليه قريباً');
    }

    public function answer(Request $request, Question $question)
    {
        $request->validate([
            'answer' => 'required|string|min:5|max:2000',
        ]);

        $user = auth()->user();
        $isStoreOwner = $question->product->store->user_id === $user->id;

        Answer::create([
            'question_id' => $question->id,
            'user_id' => $user->id,
            'answer' => $request->answer,
            'is_store_answer' => $isStoreOwner,
            'status' => $isStoreOwner ? 'approved' : 'pending',
        ]);

        return back()->with('success', 'تم إرسال إجابتك');
    }

    public function voteHelpful(Question $question)
    {
        $question->increment('helpful_count');
        return back()->with('success', 'شكراً لتصويتك');
    }

    public function voteAnswerHelpful(Answer $answer)
    {
        $answer->increment('helpful_count');
        return back()->with('success', 'شكراً لتصويتك');
    }
}
