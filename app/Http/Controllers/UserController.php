<?php

namespace App\Http\Controllers;

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
    public function index()
    {
        //User Mobile Count
        $userMobileCount = Mobile::where('availability', 'Available')
            ->where('is_transfer', false)->where('user_id', Auth::id())
            ->count();
        // User Received Mobiles Count
        $receivedMobiles = TransferRecord::with('fromUser', 'toUser', 'mobile')
            ->whereIn('id', function ($query) {
                $query->select(\DB::raw('MAX(id)'))
                    ->from('transfer_records')
                    ->groupBy('mobile_id');
            })
            ->where('to_user_id', Auth::id())
            ->whereHas('mobile', function ($query) {
                $query->where('user_id', Auth::id())
                    ->where('availability', 'Available')
                    ->where('is_transfer', true);
            })
            ->count();

        // User Sold Mobiles
        $soldMobile = Mobile::where('user_id', auth()->user()->id)->where('availability', 'Sold')->where('is_approve','Not_Approved')->where('is_transfer', false)->count();
                $pendingMobiles = Mobile::where('user_id', auth()->user()->id)->where('availability', 'Pending')->where('is_approve','Not_Approved')->where('is_transfer', false)->count();

        // Received sold mobiles
        $receivedSoldMobiles = TransferRecord::with('fromUser', 'toUser', 'mobile')
            ->whereIn('id', function ($query) {
                $query->select(\DB::raw('MAX(id)'))
                    ->from('transfer_records')
                    ->groupBy('mobile_id');
            })
            ->where('to_user_id', Auth::id())
            ->whereHas('mobile', function ($query) {
                $query->where('user_id', Auth::id())
                    ->where('availability', 'Sold')->where('is_approve','Not_Approved');
            })
            ->whereHas('mobile', function ($query) {
                $query->where('is_transfer', true);
            })
            ->count();
        // Total Cost Price
        $totalCostPrice = DB::table('mobiles')
            ->where('user_id', auth()->user()->id)
            ->where('availability', 'Available')
            ->where('is_transfer', false)
            ->sum('cost_price');

        // Total Sold mobile Cost
        $totals = Mobile::where('user_id', auth()->user()->id)
            ->where('availability', 'Sold')
            ->where('is_transfer', false)
            ->where('is_approve', 'Not_Approved')
            ->selectRaw('SUM(cost_price) as total_cost, SUM(selling_price) as total_selling_price')
            ->first();

            $totalSellingPrice = $totals->total_selling_price;
        // $totalCost = $totals->total_cost;

        //total received mobile cost
        $sumCostPrice = Mobile::join('transfer_records', function ($join) {
            $join->on('mobiles.id', '=', 'transfer_records.mobile_id')
                ->where('transfer_records.id', function ($query) {
                    $query->selectRaw('MAX(id)')
                        ->from('transfer_records')
                        ->whereColumn('mobile_id', 'mobiles.id');
                });
        })
        ->where('mobiles.user_id', auth()->id())
        ->where('mobiles.availability', 'Available')
        ->where('mobiles.is_transfer', true)
        ->sum('mobiles.cost_price');
        
         //Weekly Profit
        $startOfWeek = Carbon::now()->startOfWeek(Carbon::FRIDAY);
        $endOfWeek = Carbon::now()->endOfWeek(Carbon::FRIDAY);

        $profit = Mobile::where('user_id', auth()->user()->id)
            ->where('availability', 'Sold')
            ->where('is_transfer', false)
            ->where('is_approve', 'Not_Approved')
            ->whereBetween('sold_at', [$startOfWeek, $endOfWeek])
            ->sum('selling_price') - Mobile::where('user_id', auth()->user()->id)
            ->where('availability', 'Sold')
            ->where('is_transfer', false)
            ->where('is_approve', 'Not_Approved')
            ->whereBetween('sold_at', [$startOfWeek, $endOfWeek])
            ->sum('cost_price');
            
            $receivedPendingMobiles = TransferRecord::with('fromUser', 'toUser', 'mobile')
            ->whereIn('id', function ($query) {
                $query->select(\DB::raw('MAX(id)'))
                    ->from('transfer_records')
                    ->groupBy('mobile_id');
            })
            ->where('to_user_id', Auth::id())
            ->whereHas('mobile', function ($query) {
                $query->where('user_id', Auth::id())
                    ->where('availability', 'Pending')->where('is_approve','Not_Approved');
            })
            ->whereHas('mobile', function ($query) {
                $query->where('is_transfer', true);
            })
            ->count();


        return view('user_dashboard', compact('userMobileCount', 'receivedMobiles', 'soldMobile', 'receivedSoldMobiles', 'totalCostPrice', 'totalSellingPrice','sumCostPrice','profit','pendingMobiles','receivedPendingMobiles'));
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
