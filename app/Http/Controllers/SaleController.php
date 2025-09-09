<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\vendor;
use App\Models\Mobile;
use App\Models\saleMobile;
use App\Models\Accounts;
use App\Models\MobileTransaction;
use App\Models\MobileHistory;
use App\Models\User;
use App\Models\sale;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;



class SaleController extends Controller
{

     public function __construct()
    {
        $this->middleware('auth');
    }

    
public function pos()
{
    // Existing data
    $mobiles = Mobile::where('availability', 'Available')->get();
    $vendors = vendor::all();

    // 🔹 Today's sales (no filters)
    $todayQuery = sale::whereDate('created_at', Carbon::today());

    // List for the POS page (with basic rollups per sale)
    $todaySales = (clone $todayQuery)
        ->with(['vendor:id,name', 'seller:id,name'])
        ->withCount('mobiles')
        ->withSum('mobiles as sell_sum', 'selling_price')
        ->latest('id')
        ->paginate(10, ['*'], 'daily_page'); // separate pager key (if you add another pager later)

    // Totals for today
    $saleIds = (clone $todayQuery)->pluck('id');

    if ($saleIds->isEmpty()) {
        $todaySellSum = 0;
        $todayCostSum = 0;
        $todayDiscSum = 0;
        $todayProfit  = 0;
    } else {
        $todaySellSum = (float) saleMobile::whereIn('sale_id', $saleIds)->sum('selling_price');

        $todayCostSum = (float) saleMobile::whereIn('sale_id', $saleIds)
            ->join('mobiles as m', 'm.id', '=', 'sale_mobiles.mobile_id')
            ->sum(DB::raw('m.cost_price'));

        $todayDiscSum = (float) sale::whereIn('id', $saleIds)->sum('discount');

        $todayProfit = $todaySellSum - $todayCostSum - $todayDiscSum;
    }

    return view('pos', compact(
        'mobiles',
        'vendors',
        'todaySales',
        'todaySellSum',
        'todayCostSum',
        'todayDiscSum',
        'todayProfit'
    ));
}

// public function store(Request $request)
// {
//     $user = auth()->user();
//     $mobiles = $request->input('mobiles', []);
//     $vendorId = $request->input('vendor_id');
//     $customerName = $request->input('customer_name');
//     $discount = floatval($request->input('discount'));
//     $payAmount = floatval($request->input('pay_amount'));

//     if (empty($mobiles)) {
//         return response()->json(['success' => false, 'message' => 'No mobiles in sale.']);
//     }

//     // Calculate total selling price
//     $total = array_sum(array_map(function($m) {
//         return floatval($m['selling_price']);
//     }, $mobiles));
//     $totalAfterDiscount = $total - $discount;

//     DB::beginTransaction();
//     try {
//         // Create Sale record
//         $sale = sale::create([
//             'user_id' => $user->id,
//             'vendor_id' => $vendorId,
//             'sold_by' => $user->id,
//             'customer_name' => $customerName,
//             'discount' => $discount,
//             'total' => $totalAfterDiscount,
//             'total_amount'   => $total, 
//             'pay_amount' => $payAmount,
//             'sale_type'      => 'POS',
//             'paid_amount' => $payAmount,

//         ]);

//         foreach ($mobiles as $m) {
//             $mobile = Mobile::where('imei_number', $m['imei_number'])->first();
//             if (!$mobile) {
//                 throw new \Exception("Mobile with IMEI {$m['imei_number']} not found.");
//             }

//             // Update mobile status
//             $mobile->availability = 'Sold';
//             $mobile->sold_at = now();
//             $mobile->sold_by = $user->id;
//             // $mobile->is_approve = 'Approved';
//             $mobile->save();

//             // Create SaleMobile record
//             saleMobile::create([
//                 'sale_id' => $sale->id,
//                 'mobile_id' => $mobile->id,
//                 'selling_price' => $m['selling_price'],
//                 'cost_price' => $mobile->cost_price,
//             ]);

//             // Create transaction
//             MobileTransaction::create([
//                 'mobile_id' => $mobile->id,
//                 'category' => 'Sale',
//                 'selling_price' => $m['selling_price'],
//                 'cost_price' => $mobile->cost_price,
//                 'vendor_id' => $vendorId,
//                 'customer_name' => $vendorId ? null : $customerName,
//                 'transaction_date' => now(),
//                 'user_id' => $user->id,
//                 'note' => 'Bulk sale via POS',
//             ]);

//             // Vendor accounts
//             if ($vendorId) {
//                 Accounts::create([
//                     'vendor_id' => $vendorId,
//                     'category' => 'DB',
//                     'amount' => $m['selling_price'],
//                     'description' => "Sold mobile: {$mobile->mobile_name}",
//                     'created_by' => $user->id,
//                 ]);
//             }

//             // Mobile History
//             MobileHistory::create([
//                 'mobile_id' => $mobile->id,
//                 'mobile_name' => $mobile->mobile_name,
//                 'customer_name' => $vendorId ? (Vendor::find($vendorId)->name ?? 'Unknown Vendor') : $customerName,
//                 'battery_health' => $mobile->battery_health,
//                 'cost_price' => $mobile->cost_price,
//                 'selling_price' => $m['selling_price'],
//                 'availability_status' => 'Sold',
//                 'created_by' => $user->name,
//                 'group' => optional($mobile->group)->name,
//             ]);
//         }

//         // Vendor: record payment (if any)
//         if ($vendorId && $payAmount > 0) {
//             Accounts::create([
//                 'vendor_id' => $vendorId,
//                 'category' => 'CR',
//                 'amount' => $payAmount,
//                 'description' => "Vendor paid for POS sale (receipt #{$sale->id})",
//                 'created_by' => $user->id,
//             ]);
//         }

//         DB::commit();

//         return response()->json([
//             'success' => true,
//             'message' => 'Sale completed!',
//             'receipt_url' => route('sales.receipt', $sale->id),
//             'sale_id' => $sale->id,
//         ]);

//     } catch (\Exception $e) {
//         DB::rollBack();
//         // Optionally: Log error
//         return response()->json([
//             'success' => false,
//             'message' => 'Sale failed! ' . $e->getMessage()
//         ], 500);
//     }
// }

public function store(Request $request)
{
    $user         = auth()->user();
    $mobiles      = $request->input('mobiles', []);   // each: ['imei_number' => ..., 'selling_price' => ...]
    $vendorId     = $request->input('vendor_id');
    $customerName = $request->input('customer_name');
    $discount     = (float) $request->input('discount', 0);
    $payAmount    = (float) $request->input('pay_amount', 0);

    if (empty($mobiles)) {
        return response()->json(['success' => false, 'message' => 'No mobiles in sale.']);
    }

    // ===== 1) Compute totals (original/gross) =====
    $total = array_sum(array_map(static function ($m) {
        return (float) $m['selling_price'];
    }, $mobiles));

    // Guardrails
    if ($total <= 0) {
        return response()->json(['success' => false, 'message' => 'Invalid total amount.']);
    }
    if ($discount < 0) {
        $discount = 0;
    }
    if ($discount > $total) {
        $discount = $total;
    }

    $totalAfterDiscount = round($total - $discount, 2);

    // ===== 2) Allocate discount PROPORTIONALLY per item (by original price) =====
    // Build allocations keyed by IMEI for stable lookup
    $allocations = [];
    $allocatedSum = 0.0;

    foreach ($mobiles as $m) {
        $imei  = $m['imei_number'];
        $price = (float) $m['selling_price'];
        $share = $total > 0 ? round($discount * ($price / $total), 2) : 0.0;

        $allocations[$imei] = [
            'original_price'           => $price,
            'discount_share'           => $share,
            'selling_discounted_price' => round($price - $share, 2),
        ];
        $allocatedSum += $share;
    }

    // Fix rounding remainder on the last item to ensure exact sum
    $remainder = round($discount - $allocatedSum, 2);
    if (abs($remainder) >= 0.01) {
        $lastImei = array_key_last($allocations);
        $allocations[$lastImei]['discount_share']            = round($allocations[$lastImei]['discount_share'] + $remainder, 2);
        $allocations[$lastImei]['selling_discounted_price']  = round(
            $allocations[$lastImei]['original_price'] - $allocations[$lastImei]['discount_share'], 2
        );
    }

    DB::beginTransaction();
    try {
        // ===== 3) Create Sale (header) =====
        $sale = sale::create([
            'user_id'       => $user->id,
            'vendor_id'     => $vendorId,
            'sold_by'       => $user->id,
            'customer_name' => $customerName,
            'discount'      => $discount,            // overall discount
            'total_amount'  => $total,               // sum of originals (gross)
            'total'         => $totalAfterDiscount,  // net after discount
            'pay_amount'    => $payAmount,
            'paid_amount'   => $payAmount,
            'sale_type'     => 'POS',
        ]);

        // Pre-fetch vendor name once for history (avoid inside loop N+1)
        $vendorName = null;
        if ($vendorId) {
            $vendor = Vendor::find($vendorId);
            $vendorName = $vendor->name ?? 'Unknown Vendor';
        }

        // ===== 4) Line items, inventory updates, transactions, accounts, history =====
        foreach ($mobiles as $m) {
            $imei   = $m['imei_number'];
            $mobile = Mobile::where('imei_number', $imei)->first();

            if (!$mobile) {
                throw new \Exception("Mobile with IMEI {$imei} not found.");
            }

            $lineOriginal   = $allocations[$imei]['original_price'];
            $lineDiscount   = $allocations[$imei]['discount_share'];
            $lineDiscounted = $allocations[$imei]['selling_discounted_price'];

            // Inventory update
            $mobile->availability = 'Sold';
            $mobile->sold_at      = now();
            $mobile->sold_by      = $user->id;
            $mobile->save();

            // SaleMobile (store both original & net and the discount share)
            saleMobile::create([
                'sale_id'                  => $sale->id,
                'mobile_id'                => $mobile->id,
                'selling_price'            => $lineOriginal,    // original/gross
                'selling_discounted_price' => $lineDiscounted,  // net after per-item discount
                'discount_share'           => $lineDiscount,
                'cost_price'               => $mobile->cost_price,
            ]);

            // Transaction (use discounted/net)
            MobileTransaction::create([
                'mobile_id'        => $mobile->id,
                'category'         => 'Sale',
                'selling_price'    => $lineDiscounted, // net
                'cost_price'       => $mobile->cost_price,
                'vendor_id'        => $vendorId,
                'customer_name'    => $vendorId ? null : $customerName,
                'transaction_date' => now(),
                'user_id'          => $user->id,
                'note'             => 'Bulk sale via POS',
            ]);

            // Vendor Accounts (DB) — record the net receivable per line for vendor sales
            if ($vendorId) {
                Accounts::create([
                    'vendor_id'   => $vendorId,
                    'category'    => 'DB',
                    'amount'      => $lineDiscounted, // net (after discount)
                    'description' => "Sold mobile: {$mobile->mobile_name}",
                    'created_by'  => $user->id,
                ]);
            }

            // Mobile History (store net selling price)
            MobileHistory::create([
                'mobile_id'           => $mobile->id,
                'mobile_name'         => $mobile->mobile_name,
                'customer_name'       => $vendorId ? $vendorName : $customerName,
                'battery_health'      => $mobile->battery_health,
                'cost_price'          => $mobile->cost_price,
                'selling_price'       => $lineDiscounted, // net
                'availability_status' => 'Sold',
                'created_by'          => $user->name,
                'group'               => optional($mobile->group)->name,
            ]);
        }

        // ===== 5) Vendor payment (CR) if provided =====
        if ($vendorId && $payAmount > 0) {
            Accounts::create([
                'vendor_id'   => $vendorId,
                'category'    => 'CR',
                'amount'      => $payAmount,
                'description' => "Vendor paid for POS sale (receipt #{$sale->id})",
                'created_by'  => $user->id,
            ]);
        }

        DB::commit();

        return response()->json([
            'success'     => true,
            'message'     => 'Sale completed!',
            'receipt_url' => route('sales.receipt', $sale->id),
            'sale_id'     => $sale->id,
        ]);
    } catch (\Throwable $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Sale failed! ' . $e->getMessage(),
        ], 500);
    }
}


public function receipt($id)
{
    $sale = Sale::with(['saleMobiles.mobile.company', 'vendor', 'seller'])->findOrFail($id);

    return view('sales.receipt', compact('sale'));
}


// public function receipt($id)
// {
//     $sale = sale::with(['mobiles.mobile'])->findOrFail($id); // adjust relation names as needed
//     // Return a Blade view (receipt.blade.php)
//     return view('sales.receipt', compact('sale'));
// }

public function index(Request $request)
{
     $userId = auth()->id();
    $vendorId = $request->input('vendor_id');
    $sellerId = $request->input('sold_by');
    $dateFrom = $request->input('from');
    $dateTo   = $request->input('to');

    // Base query for listing
    $salesQuery = sale::query()
        ->when($vendorId, fn($q) => $q->where('vendor_id', $vendorId))
        ->when($sellerId, fn($q) => $q->where('sold_by', $sellerId))
        ->when($dateFrom, fn($q) => $q->whereDate('created_at', '>=', $dateFrom))
        ->when($dateTo, fn($q) => $q->whereDate('created_at', '<=', $dateTo));

    // Paginated data for the table
    $sales = (clone $salesQuery)
        ->with(['vendor:id,name', 'seller:id,name'])
        ->withCount('mobiles')                            // relation: mobiles() -> hasMany(saleMobile::class)
        ->withSum('mobiles as sell_sum', 'selling_price') // sum from sale_mobiles
        ->latest('id')
        ->paginate(25);

    // === Overall Profit (simple & optimized without DB::table alias) ===
    // Get the filtered sale IDs (for the whole filtered set, not just current page)
    $saleIds = (clone $salesQuery)->pluck('id'); // collection of IDs

    // If no rows, profit is zero
    if ($saleIds->isEmpty()) {
        $overallProfit  = 0;
        $overallSellSum = 0;
        $overallCostSum = 0;
        $overallDiscSum = 0;
    } else {
        // Sum of selling prices from sale_mobiles for these sales
        $overallSellSum = (float) saleMobile::whereIn('sale_id', $saleIds)
            ->sum('selling_price');

        // Sum of costs via a light join to mobiles (uses table name but via Eloquent builder)
        $overallCostSum = (float) saleMobile::whereIn('sale_id', $saleIds)
            ->join('mobiles as m', 'm.id', '=', 'sale_mobiles.mobile_id')
            ->sum(DB::raw('m.cost_price'));

        // Sum of discounts from sales
        $overallDiscSum = (float) sale::whereIn('id', $saleIds)
            ->sum('discount');

        // Profit = sell - cost - discount
        $overallProfit = $overallSellSum - $overallCostSum - $overallDiscSum;
    }

    // Filters dropdowns
    $vendors = vendor::orderBy('name')->get(['id','name']);
    $users   = User::orderBy('name')->get(['id','name']);

    return view('sales.index', [
        'sales'          => $sales,
        'vendors'        => $vendors,
        'users'          => $users,
        'overallProfit'  => $overallProfit,
        // optional chips above table:
        'overallSellSum' => $overallSellSum,
        'overallCostSum' => $overallCostSum,
        'overallDiscSum' => $overallDiscSum,
        'userId' => $userId,
    ]);
}


    // Keep your existing store() & receipt() methods as you already implemented.
    // If you need me to refactor them too, say the word.



}
