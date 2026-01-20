<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use App\Models\Store;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index()
    {
        $conversations = Conversation::where('user_one_id', auth()->id())
            ->orWhere('user_two_id', auth()->id())
            ->with(['userOne', 'userTwo', 'lastMessage'])
            ->orderByDesc('last_message_at')
            ->paginate(20);

        return view('messages.index', compact('conversations'));
    }

    public function show(Conversation $conversation)
    {
        if ($conversation->user_one_id !== auth()->id() && $conversation->user_two_id !== auth()->id()) {
            abort(403);
        }

        $conversation->markAsReadFor(auth()->user());

        $messages = $conversation->messages()
            ->with('sender')
            ->orderBy('created_at')
            ->paginate(50);

        $otherUser = $conversation->getOtherUser(auth()->user());

        return view('messages.show', compact('conversation', 'messages', 'otherUser'));
    }

    public function create(Request $request)
    {
        $store = null;
        $user = null;

        if ($request->store_id) {
            $store = Store::findOrFail($request->store_id);
            $user = $store->user;
        } elseif ($request->user_id) {
            $user = User::findOrFail($request->user_id);
        }

        return view('messages.create', compact('store', 'user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'message' => 'required|string|max:5000',
            'subject' => 'nullable|string|max:255',
        ]);

        if ($request->recipient_id == auth()->id()) {
            return back()->with('error', 'لا يمكنك إرسال رسالة لنفسك');
        }

        // Find or create conversation
        $conversation = Conversation::where(function($q) use ($request) {
            $q->where('user_one_id', auth()->id())
              ->where('user_two_id', $request->recipient_id);
        })->orWhere(function($q) use ($request) {
            $q->where('user_one_id', $request->recipient_id)
              ->where('user_two_id', auth()->id());
        })->first();

        if (!$conversation) {
            $conversation = Conversation::create([
                'user_one_id' => auth()->id(),
                'user_two_id' => $request->recipient_id,
                'subject' => $request->subject,
            ]);
        }

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => auth()->id(),
            'message' => $request->message,
        ]);

        $conversation->update(['last_message_at' => now()]);

        // Handle attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('messages', 'public');
                $message->attachments()->create([
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        return redirect()->route('messages.show', $conversation)->with('success', 'تم إرسال الرسالة');
    }

    public function reply(Request $request, Conversation $conversation)
    {
        $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        if ($conversation->user_one_id !== auth()->id() && $conversation->user_two_id !== auth()->id()) {
            abort(403);
        }

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => auth()->id(),
            'message' => $request->message,
        ]);

        $conversation->update(['last_message_at' => now()]);

        return back()->with('success', 'تم إرسال الرد');
    }
}
