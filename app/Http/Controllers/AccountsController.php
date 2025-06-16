<?php

namespace App\Http\Controllers;

use App\Models\Accounts;
use App\Models\vendor;
use Illuminate\Http\Request;
use App\Models\MasterPassword;


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
        
        $accounts = Accounts::with('creator') // eager load the user who created each entry
            ->where('vendor_id', $id)
            ->orderBy('created_at')
            ->get();

            // dd($accounts);

        $vendor = Vendor::findOrFail($id);
        $runningBalance = 0;

        $formatted = $accounts->map(function ($item) use (&$runningBalance) {
            if ($item->category === 'DB') {
                $runningBalance += $item->amount;
            } elseif ($item->category === 'CR') {
                $runningBalance -= $item->amount;
            }

            return [
                'id' => $item->id, 
                'created_at' => $item->created_at->format('Y-m-d H:i:s'),
                'cr' => $item->category === 'CR' ? $item->amount : null,
                'db' => $item->category === 'DB' ? $item->amount : null,
                'balance' => $runningBalance,
                'description' => $item->description ?? '-',
                'created_by' => $item->creator ? $item->creator->name : 'N/A',
            ];
        });

        $totalCredit = $accounts->where('category', 'CR')->sum('amount');
        $totalDebit = $accounts->where('category', 'DB')->sum('amount');
        $netBalance = $runningBalance;

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
        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:255',
        ]);

        $userId = auth()->user()->id;

        Accounts::create([
            'vendor_id' => $request->vendor_id,
            'category' => 'CR', // Credit
            'amount' => $request->amount,
            'description' => $request->description ?? 'Manual credit entry',
            'created_by' => $userId,
        ]);

        return redirect()->back()->with('success', 'Credit amount recorded successfully.');
    }



    public function debitAmount(Request $request)
    {
        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:255',
        ]);

        $userId = auth()->user()->id;

        Accounts::create([
            'vendor_id' => $request->vendor_id,
            'category' => 'DB', // Debit
            'amount' => $request->amount,
            'description' => $request->description ?? 'Manual debit entry',
            'created_by' => $userId,

        ]);

        return redirect()->back()->with('success', 'Debit amount recorded successfully.');
    }

    public function getaccount($id)
    {
        $filterId = Accounts::find($id);
        // dd($filterId);
        if (!$filterId) {

            return response()->json(['message' => 'Id not found'], 404);
        }

        return response()->json(['result' => $filterId]);

    }

    public function destroyAccount(Request $request)
    {
        $account = Accounts::findOrFail($request->id);

        $password = $request->input('password');
        $masterPassword = MasterPassword::first();

        // Check against delete_password
        if ($password === $masterPassword->delete_password) {
            $account->delete();

            return redirect()->back()->with('success', 'Account deleted successfully.');
        } else {
            return redirect()->back()->with('danger', 'Incorrect delete password.');
        }

        // Delete the vendor record

        return redirect()->back()->with('success', 'Vendor deleted successfully!');
    }



}
