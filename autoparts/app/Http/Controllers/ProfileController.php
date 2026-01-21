<?php

namespace App\Http\Controllers;

use App\Models\UserAddress;
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|unique:users,phone,' . $user->id,
            'avatar' => 'nullable|image|max:2048',
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|min:8|confirmed',
        ]);
        
        $data = $request->only(['first_name', 'last_name', 'email', 'phone']);
        
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path;
        }
        
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'كلمة المرور الحالية غير صحيحة']);
            }
            $data['password'] = Hash::make($request->password);
        }
        
        $user->update($data);
        
        return back()->with('success', 'تم تحديث الملف الشخصي بنجاح');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);
        
        $user = auth()->user();
        
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'كلمة المرور غير صحيحة']);
        }
        
        auth()->logout();
        $user->delete();
        
        return redirect('/')->with('success', 'تم حذف حسابك بنجاح');
    }

    public function addresses()
    {
        $addresses = auth()->user()->addresses;
        return view('profile.addresses', compact('addresses'));
    }

    public function storeAddress(Request $request)
    {
        $request->validate([
            'label' => 'required|string|max:255',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'phone' => 'required|string|max:20',
        ]);
        
        $user = auth()->user();
        
        // If this is the first address or is_default is checked
        if ($request->is_default || $user->addresses()->count() === 0) {
            $user->addresses()->update(['is_default' => false]);
            $request->merge(['is_default' => true]);
        }

        // Map form fields to UserAddress schema
        $addressData = [
            'label' => $request->label,
            'recipient_name' => $user->name,
            'recipient_phone' => $request->phone,
            // 'country' uses DB default if not provided
            'region' => $request->region,
            'city' => $request->city,
            'street' => $request->address_line_1,
            'apartment_number' => $request->address_line_2,
            'postal_code' => $request->postal_code,
            'is_default' => (bool) $request->is_default,
        ];

        $user->addresses()->create($addressData);
        
        return back()->with('success', 'تمت إضافة العنوان بنجاح');
    }

    public function setDefaultAddress(UserAddress $address)
    {
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }
        
        auth()->user()->addresses()->update(['is_default' => false]);
        $address->update(['is_default' => true]);
        
        return back()->with('success', 'تم تعيين العنوان الافتراضي');
    }

    public function destroyAddress(UserAddress $address)
    {
        if ($address->user_id !== auth()->id()) {
            abort(403);
        }
        
        $address->delete();
        
        return back()->with('success', 'تم حذف العنوان');
    }

    public function cars()
    {
        $cars = auth()->user()->cars()->with(['make', 'model'])->get();
        return view('profile.cars', compact('cars'));
    }

    public function settings()
    {
        $user = auth()->user();
        return view('profile.settings', compact('user'));
    }

    public function updateSettings(Request $request)
    {
        $user = auth()->user();
        
        $settings = [
            'email_notifications' => $request->boolean('email_notifications'),
            'sms_notifications' => $request->boolean('sms_notifications'),
            'order_updates' => $request->boolean('order_updates'),
            'promotions' => $request->boolean('promotions'),
            'profile_public' => $request->boolean('profile_public'),
        ];
        
        $user->update(['settings' => $settings]);
        
        return back()->with('success', 'تم حفظ الإعدادات');
    }

    public function wallet()
    {
        $wallet = auth()->user()->wallet ?? auth()->user()->wallet()->create(['balance' => 0]);
        $transactions = $wallet->transactions()->latest()->paginate(20);
        $loyaltyPoints = auth()->user()->loyaltyPoints?->balance ?? 0;
        
        return view('profile.wallet', compact('wallet', 'transactions', 'loyaltyPoints'));
    }

    public function addFunds(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10',
            'payment_method' => 'required|in:card,bank',
        ]);
        
        // In production, this would integrate with payment gateway
        // For now, we'll just add the funds directly
        $wallet = auth()->user()->wallet ?? auth()->user()->wallet()->create(['balance' => 0]);
        $wallet->credit($request->amount, 'إضافة رصيد');
        
        return back()->with('success', 'تم إضافة الرصيد بنجاح');
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

    public function createMessage(Request $request)
    {
        return view('profile.messages-create');
    }

    public function conversation($id)
    {
        $conversation = auth()->user()->conversations()->findOrFail($id);
        $conversation->markAsReadFor(auth()->user());
        $messages = $conversation->messages()->with('sender')->latest()->paginate(50);
        
        return view('profile.conversation', compact('conversation', 'messages'));
    }
}
