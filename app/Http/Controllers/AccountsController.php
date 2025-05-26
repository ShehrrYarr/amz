<?php

namespace App\Http\Controllers;

use App\Models\Accounts;
use App\Models\vendor;
use Illuminate\Http\Request;

class AccountsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    //    public function showAccounts($id)
// {
//     $accounts = Accounts::where('vendor_id', $id)->get();
//     $vendor = Vendor::find($id);

    //     $formatted = $accounts->map(function ($item) {
//         return [
//             'created_at' => $item->created_at->format('Y-m-d H:i'),
//             'cr' => $item->category === 'CR' ? $item->amount : null,
//             'db' => $item->category === 'DB' ? $item->amount : null,
//             'description' => $item->description ?? '-',
//         ];
//     });

    //     $totalCredit = $accounts->where('category', 'CR')->sum('amount');
//     $totalDebit = $accounts->where('category', 'DB')->sum('amount');

    //     return view('showAccounts', compact('formatted', 'vendor', 'totalCredit', 'totalDebit'));
// }

    public function showAccounts($id)
    {
        $accounts = Accounts::where('vendor_id', $id)->get();
        $vendor = Vendor::find($id);

        $formatted = $accounts->map(function ($item) {
            return [
                'created_at' => $item->created_at->format('Y-m-d H:i'),
                'cr' => $item->category === 'CR' ? $item->amount : null,
                'db' => $item->category === 'DB' ? $item->amount : null,
                'description' => $item->description ?? '-',
            ];
        });

        $totalCredit = $accounts->where('category', 'CR')->sum('amount');
        $totalDebit = $accounts->where('category', 'DB')->sum('amount');

        // Fallback for PHP 7.x using strpos instead of str_starts_with
        $purchaseFromVendor = $accounts->filter(function ($a) {
            return $a->category === 'DB' && strpos($a->description, 'Purchased') === 0;
        })->sum('amount');

        $vendorSales = $accounts->filter(function ($a) {
            return $a->category === 'CR' && strpos($a->description, 'Vendor purchase') === 0;
        })->sum('amount');

        $vendorPayments = $accounts->filter(function ($a) {
            return $a->category === 'CR' && strpos($a->description, 'Vendor paid') === 0;
        })->sum('amount');

        $netBalance = $vendorSales + $vendorPayments - $purchaseFromVendor;

        return view('showAccounts', compact(
            'formatted',
            'vendor',
            'totalCredit',
            'totalDebit',
            'netBalance'
        ));
    }



    public function creditAmount(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:255',
        ]);

        // Store credit transaction
        Accounts::create([
            'vendor_id' => $request->vendor_id,
            'category' => 'CR', // Credit
            'amount' => $request->amount,
            'description' => $request->description,
        ]);

        // Redirect back with success message
        return redirect()->back()->with('success', 'Credit amount recorded successfully.');
    }

    public function debitAmount(Request $request)
    {
        // dd($request->all());
        // Validate the input
        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:255',
        ]);

        // Store debit transaction
        Accounts::create([
            'vendor_id' => $request->vendor_id,
            'category' => 'DB', // Debit
            'amount' => $request->amount,
            'description' => $request->description,
        ]);

        // Redirect with success message
        return redirect()->back()->with('success', 'Debit amount recorded successfully.');
    }

}
