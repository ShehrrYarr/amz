<?php

namespace App\Http\Controllers;

use App\Models\Accounts;
use App\Models\Mobile;
use App\Models\TransferRecord;
use Illuminate\Http\Request;
use App\Models\Publication;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function index()
    // {
    //     //User Mobile Count
    //     $userMobileCount = Mobile::where('availability', 'Available')
    //         ->where('is_transfer', false)->count();


    //     // User Sold Mobiles
    //     $soldMobile = Mobile::where('availability', 'Sold')->where('is_approve', 'Not_Approved')->where('is_transfer', false)->count();
    //     $pendingMobiles = Mobile::where('availability', 'Pending')->where('is_approve', 'Not_Approved')
    //         ->where('is_transfer', false)->count();
    //     $pendingMobilesCost = Mobile::where('availability', 'Pending')->where('is_approve', 'Not_Approved')
    //         ->where('is_transfer', false)->sum('cost_price');


    //     // Total Cost Price
    //     $totalCostPrice = DB::table('mobiles')->where('availability', 'Available')
    //         ->where('is_transfer', false)
    //         ->sum('cost_price');

    //     // Total Sold mobile Cost
    //     $totals = Mobile::where('availability', 'Sold')
    //         ->where('is_transfer', false)
    //         ->where('is_approve', 'Not_Approved')
    //         ->selectRaw('SUM(cost_price) as total_cost, SUM(selling_price) as total_selling_price')
    //         ->first();

    //     $totalSellingPrice = $totals->total_selling_price;
    //     // $totalCost = $totals->total_cost;

    //     //total received mobile cost
    //     $sumCostPrice = Mobile::join('transfer_records', function ($join) {
    //         $join->on('mobiles.id', '=', 'transfer_records.mobile_id')
    //             ->where('transfer_records.id', function ($query) {
    //                 $query->selectRaw('MAX(id)')
    //                     ->from('transfer_records')
    //                     ->whereColumn('mobile_id', 'mobiles.id');
    //             });
    //     })
    //         ->where('mobiles.user_id', auth()->id())
    //         ->where('mobiles.availability', 'Available')
    //         ->where('mobiles.is_transfer', true)
    //         ->sum('mobiles.cost_price');

    //     //Weekly Profit
    //     $startOfWeek = Carbon::now()->startOfWeek(Carbon::FRIDAY);
    //     $endOfWeek = Carbon::now()->endOfWeek(Carbon::FRIDAY);

    //     $profit = Mobile::where('availability', 'Sold')
    //         ->where('is_transfer', false)
    //         ->where('is_approve', 'Not_Approved')
    //         ->whereBetween('sold_at', [$startOfWeek, $endOfWeek])
    //         ->sum('selling_price') - Mobile::where('user_id', auth()->user()->id)
    //             ->where('availability', 'Sold')
    //             ->where('is_transfer', false)
    //             ->where('is_approve', 'Not_Approved')
    //             ->whereBetween('sold_at', [$startOfWeek, $endOfWeek])
    //             ->sum('cost_price');

    //     //Debit/Credit calculation
    //     $totalDebit = Accounts::where('category', 'DB')
    //         ->sum('amount');

    //     // Total Credit (Purchases from Vendors)
    //     $totalCredit = Accounts::where('category', 'CR')
    //         ->sum('amount');




    //     return view('user_dashboard', compact(
    //         'userMobileCount',
    //         'soldMobile',
    //         'totalCostPrice',
    //         'totalSellingPrice',
    //         'sumCostPrice',
    //         'profit',
    //         'pendingMobiles',
    //         'pendingMobilesCost',
    //         'totalDebit',
    //         'totalCredit'
    //     ));
    // }
    public function index()
    {
        $userId = auth()->id();

        // 1. User Mobile Count (Available & not transferred)
        $userMobileCount = Mobile::where('availability', 'Available')
            ->where('is_transfer', false)
            ->count();

        // 2. Sold Mobiles (Not Approved & not transferred)
        $soldMobile = Mobile::where('availability', 'Sold')
            ->where('is_approve', 'Not_Approved')
            ->where('is_transfer', false)
            ->count();

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
        $totals = Mobile::where('availability', 'Sold')
            ->where('is_transfer', false)
            ->where('is_approve', 'Not_Approved')
            ->selectRaw('SUM(cost_price) as total_cost, SUM(selling_price) as total_selling_price')
            ->first();

        $totalSellingPrice = $totals->total_selling_price ?? 0;

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
            'totalReceivable'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
