<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use App\Models\WalkinOrder;
use App\Services\AuthenticationService;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    protected AuthenticationService $authService;

    public function __construct(AuthenticationService $authService)
    {
        $this->authService = $authService;
    }

    public function index(Request $request)
    {
        $users = User::where('role', 'customer')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $walkInCustomers = WalkinOrder::query()
            ->selectRaw('customer_email, max(customer_name) as customer_name, count(*) as orders_count, sum(total) as total_spent, max(created_at) as last_order_at')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;

                $query->where(function ($query) use ($search) {
                    $query->where('customer_name', 'like', "%{$search}%")
                        ->orWhere('customer_email', 'like', "%{$search}%");
                });
            })
            ->groupBy('customer_email')
            ->orderByDesc('last_order_at')
            ->take(15)
            ->get();

        $walkInOrdersByEmail = WalkinOrder::with('items')
            ->whereIn('customer_email', $walkInCustomers->pluck('customer_email'))
            ->latest()
            ->get()
            ->groupBy('customer_email');

        $walkInCustomers->each(function ($customer) use ($walkInOrdersByEmail) {
            $customer->ordered_items = $walkInOrdersByEmail
                ->get($customer->customer_email, collect())
                ->flatMap->items
                ->map(function ($item) {
                    $variants = collect([
                        $item->selected_flavor ? "Flavor: {$item->selected_flavor}" : null,
                        $item->selected_battery_color ? "Battery Color: {$item->selected_battery_color}" : null,
                    ])->filter()->implode(' / ');

                    return (object) [
                        'name' => $item->product_name,
                        'quantity' => $item->quantity,
                        'variants' => $variants,
                    ];
                })
                ->values();
        });

        return view('admin.users', compact('users', 'walkInCustomers'));
    }

    public function show(User $user)
    {
        $user->load(['orders' => fn ($query) => $query->latest()->take(10)]);

        return view('admin.user-show', compact('user'));
    }

    public function approve(User $user, Request $request)
    {
        $user->update([
            'verification_status' => 'approved',
            'verification_reviewed_at' => now(),
            'age_verified' => true,
            'is_active' => true,
        ]);

        AuditLog::log(
            'user_approved',
            "Customer {$user->email} approved",
            auth()->id(),
            $request->ip(),
            $request->userAgent()
        );

        return back()->with('success', 'Customer approved.');
    }

    public function reject(User $user, Request $request)
    {
        $user->update([
            'verification_status' => 'rejected',
            'verification_reviewed_at' => now(),
            'age_verified' => false,
        ]);

        AuditLog::log(
            'user_rejected',
            "Customer {$user->email} rejected",
            auth()->id(),
            $request->ip(),
            $request->userAgent()
        );

        return back()->with('success', 'Customer verification rejected.');
    }

    public function unlock(User $user, Request $request)
    {
        $this->authService->unlockAccount($user, auth()->user(), $request->ip(), $request->userAgent());

        return back()->with('success', 'Customer account unlocked.');
    }
}

