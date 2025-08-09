<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\vendor;
use App\Models\Mobile;
use App\Models\saleMobile;;
use App\Models\Accounts;
use App\Models\MobileTransaction;
use App\Models\MobileHistory;
use App\Models\User;
use App\Models\sale;
use Illuminate\Support\Facades\DB;



class SaleController extends Controller
{
    public function pos()
{
    $mobiles = Mobile::where('availability', 'Available')->get();
    $vendors = vendor::all();
    return view('pos', compact('mobiles', 'vendors'));
}

public function store(Request $request)
{
    $user = auth()->user();
    $mobiles = $request->input('mobiles', []);
    $vendorId = $request->input('vendor_id');
    $customerName = $request->input('customer_name');
    $discount = floatval($request->input('discount'));
    $payAmount = floatval($request->input('pay_amount'));

    if (empty($mobiles)) {
        return response()->json(['success' => false, 'message' => 'No mobiles in sale.']);
    }

    // Calculate total selling price
    $total = array_sum(array_map(function($m) {
        return floatval($m['selling_price']);
    }, $mobiles));
    $totalAfterDiscount = $total - $discount;

    DB::beginTransaction();
    try {
        // Create Sale record
        $sale = sale::create([
            'user_id' => $user->id,
            'vendor_id' => $vendorId,
            'sold_by' => $user->id,
            'customer_name' => $customerName,
            'discount' => $discount,
            'total' => $totalAfterDiscount,
            'total_amount'   => $total, 
            'pay_amount' => $payAmount,
            'sale_type'      => 'POS',
            'paid_amount' => $payAmount,

        ]);

        foreach ($mobiles as $m) {
            $mobile = Mobile::where('imei_number', $m['imei_number'])->first();
            if (!$mobile) {
                throw new \Exception("Mobile with IMEI {$m['imei_number']} not found.");
            }

            // Update mobile status
            $mobile->availability = 'Sold';
            $mobile->sold_at = now();
            $mobile->sold_by = $user->id;
            // $mobile->is_approve = 'Approved';
            $mobile->save();

            // Create SaleMobile record
            saleMobile::create([
                'sale_id' => $sale->id,
                'mobile_id' => $mobile->id,
                'selling_price' => $m['selling_price'],
                'cost_price' => $mobile->cost_price,
            ]);

            // Create transaction
            MobileTransaction::create([
                'mobile_id' => $mobile->id,
                'category' => 'Sale',
                'selling_price' => $m['selling_price'],
                'cost_price' => $mobile->cost_price,
                'vendor_id' => $vendorId,
                'customer_name' => $vendorId ? null : $customerName,
                'transaction_date' => now(),
                'user_id' => $user->id,
                'note' => 'Bulk sale via POS',
            ]);

            // Vendor accounts
            if ($vendorId) {
                Accounts::create([
                    'vendor_id' => $vendorId,
                    'category' => 'DB',
                    'amount' => $m['selling_price'],
                    'description' => "Sold mobile: {$mobile->mobile_name}",
                    'created_by' => $user->id,
                ]);
            }

            // Mobile History
            MobileHistory::create([
                'mobile_id' => $mobile->id,
                'mobile_name' => $mobile->mobile_name,
                'customer_name' => $vendorId ? (Vendor::find($vendorId)->name ?? 'Unknown Vendor') : $customerName,
                'battery_health' => $mobile->battery_health,
                'cost_price' => $mobile->cost_price,
                'selling_price' => $m['selling_price'],
                'availability_status' => 'Sold',
                'created_by' => $user->name,
                'group' => optional($mobile->group)->name,
            ]);
        }

        // Vendor: record payment (if any)
        if ($vendorId && $payAmount > 0) {
            Accounts::create([
                'vendor_id' => $vendorId,
                'category' => 'CR',
                'amount' => $payAmount,
                'description' => "Vendor paid for POS sale (receipt #{$sale->id})",
                'created_by' => $user->id,
            ]);
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Sale completed!',
            'receipt_url' => route('sales.receipt', $sale->id),
            'sale_id' => $sale->id,
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        // Optionally: Log error
        return response()->json([
            'success' => false,
            'message' => 'Sale failed! ' . $e->getMessage()
        ], 500);
    }
}

public function receipt($id)
{
    $sale = sale::with(['mobiles.mobile'])->findOrFail($id); // adjust relation names as needed
    // Return a Blade view (receipt.blade.php)
    return view('sales.receipt', compact('sale'));
}

public function index(Request $request)
    {
        $vendorId = $request->input('vendor_id');
        $sellerId = $request->input('sold_by');
        $dateFrom = $request->input('from');
        $dateTo   = $request->input('to');

        // Base query for listing (DON'T compute cost here; only counts & selling sums)
        $salesQuery = sale::query()
            ->when($vendorId, fn($q) => $q->where('vendor_id', $vendorId))
            ->when($sellerId, fn($q) => $q->where('sold_by', $sellerId))
            ->when($dateFrom, fn($q) => $q->whereDate('created_at', '>=', $dateFrom))
            ->when($dateTo, fn($q) => $q->whereDate('created_at', '<=', $dateTo));

        // Sales list for the table
        $sales = (clone $salesQuery)
            ->with(['vendor:id,name', 'seller:id,name'])
            ->withCount('mobiles')                                   // sale_mobiles count
            ->withSum('mobiles as sell_sum', 'selling_price')        // sum of selling_price from sale_mobiles
            ->latest('id')
            ->paginate(25);

        // OVERALL PROFIT (Option B): single aggregate query across filtered rows
        // Profit = SUM(sale_mobiles.selling_price) - SUM(mobiles.cost_price) - SUM(sales.discount)
        $totals = DB::table('sale_mobiles as sm')
            ->join('sales as s', 's.id', '=', 'sm.sale_id')
            ->join('mobiles as m', 'm.id', '=', 'sm.mobile_id')
            ->when($vendorId, fn($q) => $q->where('s.vendor_id', $vendorId))
            ->when($sellerId, fn($q) => $q->where('s.sold_by', $sellerId))
            ->when($dateFrom, fn($q) => $q->whereDate('s.created_at', '>=', $dateFrom))
            ->when($dateTo, fn($q) => $q->whereDate('s.created_at', '<=', $dateTo))
            ->selectRaw('
                COALESCE(SUM(sm.selling_price),0) AS sell_sum,
                COALESCE(SUM(m.cost_price),0)     AS cost_sum,
                COALESCE(SUM(s.discount),0)       AS disc_sum
            ')
            ->first();

        $overallProfit = (float)($totals->sell_sum ?? 0)
                       - (float)($totals->cost_sum ?? 0)
                       - (float)($totals->disc_sum ?? 0);

        // Dropdown data for filters
        $vendors = Vendor::orderBy('name')->get(['id','name']);
        $users   = User::orderBy('name')->get(['id','name']);

        return view('sales.index', compact('sales','vendors','users','overallProfit'));
    }

    // Keep your existing store() & receipt() methods as you already implemented.
    // If you need me to refactor them too, say the word.



}
