<?php

namespace App\Http\Controllers;

use App\Models\Accounts;
use App\Models\Mobile;
use App\Models\MobileTransaction;
use App\Models\TransferRecord;
use Hash;
use Illuminate\Http\Request;
use App\Models\Publication;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{

    public function index()
    {
        $userId = auth()->id();

        // 1. User Mobile Count (Available & not transferred)
        $userMobileCount = Mobile::where('availability', 'Available')
            ->where('is_transfer', false)
            ->count();

        // 2. Sold Mobiles (Not Approved & not transferred)
        $soldMobile = MobileTransaction::where('category', 'Sale')->count();

        // 3. Pending Mobiles Count & Cost
        $pendingMobiles = Mobile::where('availability', 'Pending')
            ->where('is_approve', 'Not_Approved')
            ->where('is_transfer', false)
            ->count();

        $pendingMobilesCost = Mobile::where('availability', 'Pending')
            ->where('is_approve', 'Not_Approved')
            ->where('is_transfer', false)
            ->sum('cost_price');

        // 4. Total Cost Price (Available)
        $totalCostPrice = Mobile::where('availability', 'Available')
            ->where('is_transfer', false)
            ->sum('cost_price');

        // 5. Total Selling Price of Sold Mobiles (Not Approved)
        $totalSellingPrice = MobileTransaction::where('category', 'Sale')
        ->sum('selling_price');
    

        // 6. Total Cost of Received Mobiles
        $sumCostPrice = Mobile::join('transfer_records', function ($join) {
            $join->on('mobiles.id', '=', 'transfer_records.mobile_id')
                ->where('transfer_records.id', function ($query) {
                    $query->selectRaw('MAX(id)')
                        ->from('transfer_records')
                        ->whereColumn('mobile_id', 'mobiles.id');
                });
        })
            ->where('mobiles.user_id', $userId)
            ->where('mobiles.availability', 'Available')
            ->where('mobiles.is_transfer', true)
            ->sum('mobiles.cost_price');

        // 7. Weekly Profit (Friday to Friday)
        $startOfWeek = Carbon::now()->startOfWeek(Carbon::FRIDAY);
        $endOfWeek = Carbon::now()->endOfWeek(Carbon::FRIDAY);

        $weeklySelling = Mobile::where('availability', 'Sold')
            ->where('is_transfer', false)
            ->where('is_approve', 'Not_Approved')
            ->whereBetween('sold_at', [$startOfWeek, $endOfWeek])
            ->sum('selling_price');

        $weeklyCost = Mobile::where('availability', 'Sold')
            ->where('user_id', $userId)
            ->where('is_transfer', false)
            ->where('is_approve', 'Not_Approved')
            ->whereBetween('sold_at', [$startOfWeek, $endOfWeek])
            ->sum('cost_price');

        $profit = $weeklySelling - $weeklyCost;

        // 8. Total Receivable from Vendors (sum of DB - CR where balance > 0)
        $vendorReceivables = DB::table('accounts')
            ->select(
                'vendor_id',
                DB::raw("
                SUM(CASE WHEN category = 'DB' THEN amount ELSE 0 END) AS total_debit,
                SUM(CASE WHEN category = 'CR' THEN amount ELSE 0 END) AS total_credit
            ")
            )
            ->whereNotNull('vendor_id')
            ->groupBy('vendor_id')
            ->get();

        $totalReceivable = $vendorReceivables->reduce(function ($carry, $vendor) {
            $balance = $vendor->total_debit - $vendor->total_credit;
            return $balance > 0 ? $carry + $balance : $carry;
        }, 0);

        return view('user_dashboard', compact(
            'userMobileCount',
            'soldMobile',
            'totalCostPrice',
            'totalSellingPrice',
            'sumCostPrice',
            'profit',
            'pendingMobiles',
            'pendingMobilesCost',
            'totalReceivable',
            'userId'
        ));
    }

    public function showUsers()
    {
        if (!in_array(auth()->id(), [1, 2])) {
            return redirect()->back()->with('danger', 'You cannot view this page.');
        }

        $users = User::all();

        return view('showUsers', compact('users'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'password_text' => $request->password,
        ]);

        return redirect()->back()->with('success', 'User added successfully.');
    }

    public function editUser($id)
    {
        $filterId = User::find($id);
        // dd($filterId);
        if (!$filterId) {

            return response()->json(['message' => 'Id not found'], 404);
        }

        return response()->json(['result' => $filterId]);

    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $request->id,
            'password' => 'nullable|string|min:6', // Make password optional for update
            'is_active' => 'nullable|boolean', // Validate the active status
        ]);

        $user = User::findOrFail($request->id);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password, // Update password only if provided
            'password_text' => $request->password,
            'is_active' => $request->is_active, // Update the active status
        ]);

        return redirect()->back()->with('success', 'User updated successfully.');
    }


    public function logoutUser($id)
    {
        // Find the user by ID
        $user = User::findOrFail($id);

        // Manually logging out the specified user by clearing their session
        // We can store the user's session ID or other identifier to be able to clear the correct session later.

        $sessionKey = 'user_session_' . $user->id;

        // Clear the specific user's session
        Session::forget($sessionKey);  // Remove session data related to the user

        // Optionally, if you're storing the user session manually (e.g., in a cache), you would invalidate it here.

        return redirect()->route('home')->with('success', 'User has been logged out successfully.');
    }


}
