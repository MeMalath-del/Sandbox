<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return view('profile.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|string|unique:users,phone,' . $user->id,
            'avatar' => 'nullable|image|max:2048',
        ]);
        
        $data = $request->only(['name', 'email', 'phone']);
        
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path;
        }
        
        $user->update($data);
        
        return back()->with('success', 'تم تحديث الملف الشخصي بنجاح');
    }

    public function addresses()
    {
        $addresses = auth()->user()->addresses;
        return view('profile.addresses', compact('addresses'));
    }

    public function cars()
    {
        $cars = auth()->user()->cars()->with(['make', 'model'])->get();
        return view('profile.cars', compact('cars'));
    }

    public function security()
    {
        $user = auth()->user();
        $sessions = $user->sessions()->orderByDesc('last_activity_at')->get();
        return view('profile.security', compact('user', 'sessions'));
    }

    public function wallet()
    {
        $wallet = auth()->user()->wallet ?? auth()->user()->wallet()->create([]);
        $transactions = $wallet->transactions()->latest()->paginate(20);
        
        return view('profile.wallet', compact('wallet', 'transactions'));
    }

    public function notifications()
    {
        $notifications = auth()->user()->notifications()->latest()->paginate(20);
        return view('profile.notifications', compact('notifications'));
    }

    public function messages()
    {
        $conversations = auth()->user()->conversations()
            ->with(['userOne', 'userTwo', 'lastMessage'])
            ->latest('last_message_at')
            ->paginate(20);
        
        return view('profile.messages', compact('conversations'));
    }

    public function conversation($id)
    {
        $conversation = auth()->user()->conversations()->findOrFail($id);
        $conversation->markAsReadFor(auth()->user());
        $messages = $conversation->messages()->with('sender')->latest()->paginate(50);
        
        return view('profile.conversation', compact('conversation', 'messages'));
    }
}
