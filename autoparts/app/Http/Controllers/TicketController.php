<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketReply;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::where('user_id', auth()->id())
            ->latest()
            ->paginate(20);

        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        $categories = [
            'order' => 'مشكلة في طلب',
            'product' => 'استفسار عن منتج',
            'payment' => 'مشكلة في الدفع',
            'delivery' => 'مشكلة في التوصيل',
            'return' => 'إرجاع أو استبدال',
            'account' => 'مشكلة في الحساب',
            'suggestion' => 'اقتراح',
            'other' => 'أخرى',
        ];

        $orders = auth()->user()->orders()->latest()->limit(10)->get();

        return view('tickets.create', compact('categories', 'orders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'category' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'message' => 'required|string|min:10',
            'order_id' => 'nullable|exists:orders,id',
        ]);

        $ticket = Ticket::create([
            'user_id' => auth()->id(),
            'subject' => $request->subject,
            'category' => $request->category,
            'priority' => $request->priority,
            'order_id' => $request->order_id,
            'status' => 'open',
            'ticket_number' => 'TKT-' . strtoupper(uniqid()),
        ]);

        TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        // Handle attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('tickets', 'public');
                $ticket->attachments()->create([
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                ]);
            }
        }

        return redirect()->route('tickets.show', $ticket)->with('success', 'تم إنشاء التذكرة بنجاح');
    }

    public function show(Ticket $ticket)
    {
        if ($ticket->user_id !== auth()->id()) {
            abort(403);
        }

        $ticket->load(['replies.user', 'order', 'attachments']);

        return view('tickets.show', compact('ticket'));
    }

    public function reply(Request $request, Ticket $ticket)
    {
        if ($ticket->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'message' => 'required|string|min:5',
        ]);

        TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        $ticket->update(['status' => 'customer_reply']);

        return back()->with('success', 'تم إرسال الرد');
    }

    public function close(Ticket $ticket)
    {
        if ($ticket->user_id !== auth()->id()) {
            abort(403);
        }

        $ticket->update(['status' => 'closed', 'closed_at' => now()]);

        return back()->with('success', 'تم إغلاق التذكرة');
    }

    public function reopen(Ticket $ticket)
    {
        if ($ticket->user_id !== auth()->id()) {
            abort(403);
        }

        if ($ticket->closed_at && $ticket->closed_at->diffInDays(now()) > 7) {
            return back()->with('error', 'لا يمكن إعادة فتح التذكرة بعد 7 أيام من إغلاقها');
        }

        $ticket->update(['status' => 'open', 'closed_at' => null]);

        return back()->with('success', 'تم إعادة فتح التذكرة');
    }

    public function rate(Request $request, Ticket $ticket)
    {
        if ($ticket->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'feedback' => 'nullable|string|max:500',
        ]);

        $ticket->update([
            'rating' => $request->rating,
            'feedback' => $request->feedback,
        ]);

        return back()->with('success', 'شكراً على تقييمك');
    }
}
