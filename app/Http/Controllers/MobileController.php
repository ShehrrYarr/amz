<?php

namespace App\Http\Controllers;

use App\Models\company;
use App\Models\group;
use App\Models\MasterPassword;
use App\Models\Mobile;
use App\Models\TransferRecord;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MobilesExport;
use App\Exports\SoldMobilesExport;
use App\Models\Restore;
use App\Models\MobileHistory;
use App\Models\vendor;
use App\Models\Accounts;
use App\Models\MobileTransaction;
use Illuminate\Support\Facades\DB; // <-- Add this line




class MobileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function exportMobiles(Request $request)
    {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');


        return Excel::download(new MobilesExport($start_date, $end_date), 'mobiles.xlsx');
    }


    public function exportSoldMobiles(Request $request)
    {
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        // Generate and return the Excel sheet
        return Excel::download(new SoldMobilesExport($start_date, $end_date), 'sold_mobiles.xlsx');
    }





    public function bulkStoreMobile(Request $request)
    {
        $validated = $request->validate([
            'mobile_name' => 'required|string',
            'sim_lock' => 'required|in:J.V,PTA,Non-PTA',
            'color' => 'required|string',
            'storage' => 'required|string',
            'cost_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'company_id' => 'required|exists:companies,id',
            'group_id' => 'required|exists:groups,id',
            'vendor_id' => 'nullable|exists:vendors,id',
            'battery_health' => 'nullable|string',
            'imeis' => 'required|array|min:1',
            'imeis.*' => 'required|digits:15|distinct',
        ]);

        foreach ($validated['imeis'] as $imei) {
            $mobile = new Mobile([
                'mobile_name' => $validated['mobile_name'],
                'sim_lock' => $validated['sim_lock'],
                'color' => $validated['color'],
                'storage' => $validated['storage'],
                'battery_health' => $validated['battery_health'],
                'imei_number' => $imei,
                'cost_price' => $validated['cost_price'],
                'selling_price' => $validated['selling_price'],
                'company_id' => $validated['company_id'],
                'group_id' => $validated['group_id'],
                'availability' => 'Available',
                'is_approve' => 'Not_Approved',
            ]);

            $mobile->user()->associate(auth()->user());
            $mobile->original_owner()->associate(auth()->user());
            $mobile->vendor_id = $validated['vendor_id'];
            $mobile->save();
        }

        // ✅ Accounting entry for vendor (if applicable)
        if (!empty($validated['vendor_id'])) {
            $vendorId = $validated['vendor_id'];
            $vendor = Vendor::find($vendorId);
            $vendorName = $vendor ? $vendor->name : 'Unknown Vendor';

            $unitCost = $validated['cost_price'];
            $totalUnits = count($validated['imeis']);
            $totalCost = $unitCost * $totalUnits;

            Accounts::create([
                'vendor_id' => $vendorId,
                'category' => 'CR',
                'amount' => $totalCost,
                'description' => "Purchased {$totalUnits} ({$validated['mobile_name']}) from {$vendorName} (Bulk Entry)",
            ]);
        }

        return redirect()->back()->with('success', 'All mobiles added and account updated successfully.');
    }


    // public function storeMobile(Request $request)
    // {
    //     $validatedData = $request->validate([
    //         'mobile_name' => 'required',
    //         'imei_number' => 'required',
    //         'sim_lock' => 'required|in:J.V,PTA,Non-PTA',
    //         'color' => 'required',
    //         'storage' => 'required',
    //         'cost_price' => 'required|numeric',
    //         'selling_price' => 'required|numeric',
    //         'company_id' => 'required|exists:companies,id',
    //         'group_id' => 'required|exists:groups,id',
    //         'vendor_id' => 'nullable|exists:vendors,id',
    //     ]);

    //     $userId = auth()->user()->id;
    //     $user = auth()->user();


    //     // Check if IMEI already exists
    //     $existingMobile = Mobile::where('imei_number', $validatedData['imei_number'])->first();
    //     if ($existingMobile) {
    //         return redirect()->back()->with('danger', 'A mobile with this IMEI number already exists.');
    //     }

    //     // Create new Mobile record
    //     $mobile = new Mobile($validatedData);
    //     $mobile->user_id = auth()->id();
    //     $mobile->original_owner_id = auth()->id();
    //     $mobile->added_by = auth()->id(); // 👈 Track who added the mobile
    //     $mobile->battery_health = $request->battery_health;
    //     $mobile->availability = 'Available';
    //     $mobile->is_approve = 'Not_Approved';
    //     $mobile->added_by = $userId;
    //     $mobile->save();

    //     $group = group::find($request->group_id);

    //     // Create vendor credit entry if vendor is present
    //     if ($request->filled('vendor_id')) {
    //         $vendor = Vendor::find($request->vendor_id);
    //         $vendorName = $vendor ? $vendor->name : 'Unknown Vendor';

    //         Accounts::create([
    //             'vendor_id' => $request->vendor_id,
    //             'category' => 'CR',
    //             'amount' => $request->cost_price,
    //             'description' => "Purchased mobile: {$mobile->mobile_name}",
    //             'created_by' => $userId,
    //         ]);
    //     }

    //       MobileHistory::create([
    //             'mobile_id' => $mobile->id,
    //             'mobile_name' => $mobile->mobile_name,
    //             'customer_name' => $vendorName,
    //             'battery_health' => $mobile->battery_health,
    //             'cost_price' => $mobile->cost_price,
    //             'selling_price' => $mobile->selling_price,
    //             'availability_status' => 'Purchased',
    //             'created_by' => $user->name, // Storing username here
    //             'group' => $group->name,

    //         ]);

    //     return redirect()->back()->with('success', 'Mobile created and account updated successfully.');
    // }

//     public function storeMobile(Request $request)
// {
//     // dd($request->all());
//     $validatedData = $request->validate([
//         'mobile_name' => 'required',
//         'imei_number' => 'required',
//         'sim_lock' => 'required|in:J.V,PTA,Non-PTA',
//         'color' => 'required',
//         'storage' => 'required',
//         'cost_price' => 'required|numeric',
//         'selling_price' => 'required|numeric',
//         'company_id' => 'required|exists:companies,id',
//         'group_id' => 'required|exists:groups,id',
//         'vendor_id' => 'nullable|exists:vendors,id',
//         'battery_health' => 'nullable|string'
//     ]);

//     $user = auth()->user();
//     $userId = $user->id;

//     // Check if IMEI already exists
//     if (Mobile::where('imei_number', $validatedData['imei_number'])->exists()) {
//         return redirect()->back()->with('danger', 'A mobile with this IMEI number already exists.');
//     }

//     // Create new Mobile record
//     $mobile = new Mobile([
//         'mobile_name' => $validatedData['mobile_name'],
//         'imei_number' => $validatedData['imei_number'],
//         'sim_lock' => $validatedData['sim_lock'],
//         'color' => $validatedData['color'],
//         'storage' => $validatedData['storage'],
//         'company_id' => $validatedData['company_id'],
//         'group_id' => $validatedData['group_id'],
//         'battery_health' => $validatedData['battery_health'],
//         'user_id' => $userId,
//         'original_owner_id' => $userId,
//         'added_by' => $userId,
//         'cost_price' => $validatedData['cost_price'],
//         'selling_price' => $validatedData['selling_price'],
//         'availability' => 'Available',
//         'is_approve' => 'Not_Approved'
//     ]);
//     $mobile->save();

//     // Create mobile transaction (purchase record)
//     MobileTransaction::create([
//         'mobile_id' => $mobile->id,
//         'category' => 'Purchase',
//         'cost_price' => $validatedData['cost_price'],
//         'vendor_id' => $request->vendor_id,
//         'transaction_date' => now(),
//         'user_id' => $userId,
//         'note' => 'Initial purchase entry',
//     ]);

//     // Vendor accounting (if vendor provided)
//     if ($request->filled('vendor_id')) {
//         Accounts::create([
//             'vendor_id' => $request->vendor_id,
//             'category' => 'CR',
//             'amount' => $validatedData['cost_price'],
//             'description' => "Purchased mobile: {$mobile->mobile_name}",
//             'created_by' => $userId,
//         ]);
//     }

//     // Add MobileHistory for tracking
//     $group = Group::find($request->group_id);
//     $vendor = $request->vendor_id ? Vendor::find($request->vendor_id) : null;

//     MobileHistory::create([
//         'mobile_id' => $mobile->id,
//         'mobile_name' => $mobile->mobile_name,
//         'customer_name' => $vendor ? $vendor->name : null,
//         'battery_health' => $mobile->battery_health,
//         'cost_price' => $validatedData['cost_price'], // kept for backward compatibility
//         'selling_price' => $validatedData['selling_price'],
//         'availability_status' => 'Purchased',
//         'created_by' => $user->name,
//         'group' => $group->name,
//     ]);

//     return redirect()->back()->with('success', 'Mobile created and purchase recorded successfully.');
// }

public function storeMobile(Request $request)
{
    $validatedData = $request->validate([
        'mobile_name'     => 'required',
        'imei_number'     => 'required',
        'sim_lock'        => 'required|in:J.V,PTA,Non-PTA',
        'color'           => 'required',
        'storage'         => 'required',
        'cost_price'      => 'required|numeric',
        'selling_price'   => 'required|numeric',
        'company_id'      => 'required|exists:companies,id',
        'group_id'        => 'required|exists:groups,id',
        'vendor_id'       => 'nullable|exists:vendors,id',
        'battery_health'  => 'nullable|string',
        'pay_amount'      => 'nullable|numeric|min:0',
    ]);

    $user = auth()->user();
    $userId = $user->id;

    // Check if IMEI already exists
    if (Mobile::where('imei_number', $validatedData['imei_number'])->exists()) {
        return redirect()->back()->with('danger', 'A mobile with this IMEI number already exists.');
    }

    // Create Mobile
    $mobile = new Mobile([
        'mobile_name'       => $validatedData['mobile_name'],
        'imei_number'       => $validatedData['imei_number'],
        'sim_lock'          => $validatedData['sim_lock'],
        'color'             => $validatedData['color'],
        'storage'           => $validatedData['storage'],
        'company_id'        => $validatedData['company_id'],
        'group_id'          => $validatedData['group_id'],
        'battery_health'    => $validatedData['battery_health'],
        'user_id'           => $userId,
        'original_owner_id' => $userId,
        'added_by'          => $userId,
        'cost_price'        => $validatedData['cost_price'],
        'selling_price'     => $validatedData['selling_price'],
        'availability'      => 'Available',
        'is_approve'        => 'Not_Approved'
    ]);
    $mobile->save();

    // Create Mobile Transaction
    MobileTransaction::create([
        'mobile_id'       => $mobile->id,
        'category'        => 'Purchase',
        'cost_price'      => $validatedData['cost_price'],
        'vendor_id'       => $request->vendor_id,
        'transaction_date'=> now(),
        'user_id'         => $userId,
        'note'            => 'Initial purchase entry',
    ]);

    // Vendor Accounting (Credit for full cost)
    if ($request->filled('vendor_id')) {
        Accounts::create([
            'vendor_id'   => $request->vendor_id,
            'category'    => 'CR',
            'amount'      => $validatedData['cost_price'],
            'description' => "Purchased mobile: {$mobile->mobile_name}",
            'created_by'  => $userId,
        ]);

        // Debit if payment was made
        if ($request->filled('pay_amount') && $request->pay_amount > 0) {
            Accounts::create([
                'vendor_id'   => $request->vendor_id,
                'category'    => 'DB',
                'amount'      => $request->pay_amount,
                'description' => "Partial payment for: {$mobile->mobile_name}",
                'created_by'  => $userId,
            ]);
        }
    }

    // Create Mobile History
    $group  = Group::find($validatedData['group_id']);
    $vendor = $request->vendor_id ? Vendor::find($request->vendor_id) : null;

    MobileHistory::create([
        'mobile_id'          => $mobile->id,
        'mobile_name'        => $mobile->mobile_name,
        'customer_name'      => $vendor ? $vendor->name : null,
        'battery_health'     => $mobile->battery_health,
        'cost_price'         => $validatedData['cost_price'],
        'selling_price'      => $validatedData['selling_price'],
        'availability_status'=> 'Purchased',
        'created_by'         => $user->name,
        'group'              => $group->name,
    ]);

    return redirect()->back()->with('success', 'Mobile created and purchase recorded successfully.');
}






    public function editMobile($id)
    {
        $filterId = Mobile::find($id);
        // dd($filterId);
        if (!$filterId) {

            return response()->json(['message' => 'Id not found'], 404);
        }

        return response()->json(['result' => $filterId]);

    }
    


    // public function sellMobile(Request $request)
    // {
    //         $group = group::find($request->group_id);
    //         // dd($group);

    //     if ($request->availability == 'Available') {
    //         return redirect()->back()->with('danger', 'Please select a different availability option.');
    //     }

    //     if (!$request->filled('customer_name') && !$request->filled('vendor_id')) {
    //         return redirect()->back()->with('danger', 'Enter customer name or select a vendor.');
    //     }

    //     $data = Mobile::findOrFail($request->id);
    //     $user = auth()->user();

    //     $data->selling_price = $request->input('selling_price');
    //     $data->availability = $request->input('availability');
    //     $data->sold_at = Carbon::now();
    //     $data->is_approve = $request->input('is_approve');
    //     $data->sold_by = $user->id;

    //     if ($request->availability === 'Sold') {
    //         if ($request->filled('vendor_id')) {
    //             // Sold to Vendor
    //             $vendorId = $request->vendor_id;
    //             $data->sold_vendor_id = $vendorId;

    //             $vendor = Vendor::find($vendorId);
    //             $vendorName = $vendor ? $vendor->name : 'Unknown Vendor';
    //             $data->customer_name = $vendorName;

    //             $sellingPrice = (float) $request->selling_price;
    //             $paidAmount = (float) $request->pay_amount;
    //             $mobileName = $data->mobile_name;

    //             // Accounts entries
    //             if ($sellingPrice > 0) {
    //                 Accounts::create([
    //                     'vendor_id' => $vendorId,
    //                     'category' => 'DB',
    //                     'amount' => $sellingPrice,
    //                     'description' => "We sold : {$mobileName}",
    //                     'created_by' => $user->id,
    //                 ]);
    //             }

    //             if ($paidAmount > 0) {
    //                 Accounts::create([
    //                     'vendor_id' => $vendorId,
    //                     'category' => 'CR',
    //                     'amount' => $paidAmount,
    //                     'description' => "Vendor paid for: {$mobileName}",
    //                     'created_by' => $user->id,
    //                 ]);
    //             }
    //         } else {
    //             // Sold to walk-in customer
    //             $data->customer_name = $request->input('customer_name');
    //             $data->sold_vendor_id = null;
    //         }

    //         $data->save();


    //         MobileHistory::create([
    //             'mobile_id' => $data->id,
    //             'mobile_name' => $data->mobile_name,
    //             'customer_name' => $data->customer_name,
    //             'battery_health' => $data->battery_health,
    //             'cost_price' => $data->cost_price,
    //             'selling_price' => $data->selling_price,
    //             'availability_status' => 'Sold',
    //             'created_by' => $user->name,
    //             'group' => $group->name,
    //         ]);
    //     } elseif ($request->availability === 'Pending') {
    //         if ($request->filled('vendor_id')) {
    //             // Pending to Vendor
    //             $vendorId = $request->vendor_id;
    //             $data->sold_vendor_id = $vendorId;
    //             $data->pending_by = $user->id;

    //             $vendor = Vendor::find($vendorId);
    //             $vendorName = $vendor ? $vendor->name : 'Unknown Vendor';
    //             $data->customer_name = $vendorName;
    //         } else {
    //             // Pending to walk-in customer
    //             $data->customer_name = $request->input('customer_name');
    //             $data->sold_vendor_id = null;
    //             $data->pending_by = $user->id;

    //         }

    //         $data->save();

    //         MobileHistory::create([
    //             'mobile_id' => $data->id,
    //             'mobile_name' => $data->mobile_name,
    //             'customer_name' => $data->customer_name,
    //             'battery_health' => $data->battery_health,
    //             'cost_price' => $data->cost_price,
    //             'selling_price' => $data->selling_price,
    //             'availability_status' => 'Pending',
    //             'created_by' => $user->name,
    //             'group' => $group->name,
    //         ]);
    //     }

    //     return redirect()->back()->with('success', 'Mobile sale processed successfully.');
    // }

    public function sellMobile(Request $request)
{
    $group = Group::find($request->group_id);

    if ($request->availability == 'Available') {
        return redirect()->back()->with('danger', 'Please select a different availability option.');
    }

    if (!$request->filled('customer_name') && !$request->filled('vendor_id')) {
        return redirect()->back()->with('danger', 'Enter customer name or select a vendor.');
    }

    $data = Mobile::findOrFail($request->id);
    $user = auth()->user();

    $data->availability = $request->input('availability');
    $data->sold_at = Carbon::now();
    $data->is_approve = $request->input('is_approve');
    $data->sold_by = $user->id;
    $data->save(); // Save availability, status, etc.

    // Prepare common transaction data
    $sellingPrice = (float) $request->selling_price;
    $paidAmount = (float) $request->pay_amount;
    $vendorId = $request->vendor_id;
    $customerName = $request->customer_name;

    // Record Sale Transaction
    if ($request->availability === 'Sold') {
        MobileTransaction::create([
            'mobile_id' => $data->id,
            'category' => 'Sale',
            'selling_price' => $sellingPrice,
            'cost_price' => $data->cost_price,
            'vendor_id' => $vendorId,
            'customer_name' => $vendorId ? null : $customerName,
            'transaction_date' => now(),
            'user_id' => $user->id,
            'note' => 'Sale recorded',
        ]);

        if ($vendorId) {
            $vendor = Vendor::find($vendorId);
            $mobileName = $data->mobile_name;

            // Vendor Account Entries
            if ($sellingPrice > 0) {
                Accounts::create([
                    'vendor_id' => $vendorId,
                    'category' => 'DB',
                    'amount' => $sellingPrice,
                    'description' => "We sold: {$mobileName}",
                    'created_by' => $user->id,
                ]);
            }

            if ($paidAmount > 0) {
                Accounts::create([
                    'vendor_id' => $vendorId,
                    'category' => 'CR',
                    'amount' => $paidAmount,
                    'description' => "Vendor paid for: {$mobileName}",
                    'created_by' => $user->id,
                ]);
            }
        }

        // Mobile history
        MobileHistory::create([
            'mobile_id' => $data->id,
            'mobile_name' => $data->mobile_name,
            'customer_name' => $vendorId ? ($vendor->name ?? 'Unknown Vendor') : $customerName,
            'battery_health' => $data->battery_health,
            'cost_price' => $data->cost_price,
            'selling_price' => $sellingPrice,
            'availability_status' => 'Sold',
            'created_by' => $user->name,
            'group' => $group->name,
        ]);
    } elseif ($request->availability === 'Pending') {
        // Pending logic, not a confirmed sale yet
        $data->pending_by = $user->id;
        $data->save();

        MobileHistory::create([
            'mobile_id' => $data->id,
            'mobile_name' => $data->mobile_name,
            'customer_name' => $vendorId ? (Vendor::find($vendorId)->name ?? 'Unknown Vendor') : $customerName,
            'battery_health' => $data->battery_health,
            'cost_price' => $data->cost_price,
            'selling_price' => $sellingPrice,
            'availability_status' => 'Pending',
            'created_by' => $user->name,
            'group' => $group->name,
        ]);
    }

    return redirect()->back()->with('success', 'Mobile sale processed successfully.');
}





    public function updateMobile(Request $request)
    {
        $data = Mobile::findOrFail($request->id);
        $password = $request->input('password');

        $masterPassword = MasterPassword::first();

        // Check against update_password instead of general password
        if ($password === $masterPassword->update_password) {
            $data->mobile_name = $request->input('mobile_name');
            $data->imei_number = $request->input('imei_number');
            $data->sim_lock = $request->input('sim_lock');
            $data->color = $request->input('color');
            $data->storage = $request->input('storage');
            $data->cost_price = $request->input('cost_price');
            $data->selling_price = $request->input('selling_price');
            $data->availability = $request->input('availability');
            $data->battery_health = $request->input('battery_health');
            $data->is_approve = $request->input('is_approve');
            $data->company_id = $request->input('company_id');
            // $data->vendor_id = $request->input('vendor_id');
            $data->group_id = $request->input('group_id');

            $data->save();

            return redirect()->back()->with('success', 'Mobile updated successfully.');
        } else {
            return redirect()->back()->with('danger', 'Incorrect update password.');
        }
    }


   



   

    // public function restoreMobile(Request $request)
    // {
    //     $group = group::find($request->group_id);
    //     dd($group);
    //     $data = Mobile::findOrFail($request->id);
    //     $user = auth()->user();

    //     // Log the restore details
    //     $restoreMobile = new Restore();
    //     $restoreMobile->mobile_name = $request->input('mobile_name');
    //     $restoreMobile->imei_number = $data->imei_number;
    //     $restoreMobile->customer_name = $request->customer_name;
    //     $restoreMobile->old_cost_price = $data->cost_price;
    //     $restoreMobile->old_selling_price = $data->selling_price;
    //     $restoreMobile->new_cost_price = $request->input('cost_price');
    //     $restoreMobile->new_selling_price = $request->input('selling_price');
    //     // $restoreMobile->new_selling_price = $request->input('selling_price');
    //     $restoreMobile->restore_by = $user->name;
    //     $restoreMobile->save();

    //     // Update mobile table with new data
    //     $data->cost_price = $request->input('cost_price');
    //     $data->selling_price = $request->input('selling_price');
    //     $data->availability = $request->input('availability');
    //     $data->customer_name = $request->input('customer_name');
    //     $data->battery_health = $request->input('battery_health');
    //     $data->group_id = $request->input('group_id');
    //     $data->sold_vendor_id = null;
    //     $data->sold_by = null;
    //     $data->pending_by = null;
    //     $data->customer_name = null;
    //     $data->sold_at = null;
    //     $data->is_approve = 'Not_Approved';
    //     $data->group_id = $request->input('group_id');
    //     $data->save();

    //     // Add mobile history
    //     MobileHistory::create([
    //         'mobile_id' => $data->id,
    //         'mobile_name' => $data->mobile_name,
    //         'customer_name' => $request->customer_name,
    //         'battery_health' => $data->battery_health,
    //         'cost_price' => $data->cost_price,
    //         'selling_price' => $data->selling_price,
    //         'availability_status' => 'Restored',
    //         'created_by' => $user->name,
    //         'group' => $group->name,
    //     ]);

    //     return redirect()->back()->with('success', 'Mobile Restored successfully.');
    // }

    public function restoreMobile(Request $request)
{
    $group = Group::findOrFail($request->group_id);
    $data = Mobile::findOrFail($request->id);
    $user = auth()->user();

    $oldCostPrice = optional($data->latestSaleTransaction)->cost_price ?? 0;
    $oldSellingPrice = optional($data->latestSaleTransaction)->selling_price ?? 0;


    // Log the restore details
    $restoreMobile = new Restore();
    $restoreMobile->mobile_name = $request->input('mobile_name');
    $restoreMobile->imei_number = $data->imei_number;
    $restoreMobile->customer_name = $request->input('customer_name');
    $restoreMobile->old_cost_price = $data->cost_price;
    $restoreMobile->old_selling_price = $data->selling_price;
    $restoreMobile->new_cost_price = $request->input('cost_price');
    $restoreMobile->new_selling_price = $request->input('selling_price');
    $restoreMobile->restore_by = $user->name;
    $restoreMobile->save();
    

    // Update mobile base data
    $data->availability = $request->input('availability');
    $data->battery_health = $request->input('battery_health');
    $data->group_id = $request->input('group_id');
    $data->sold_by = null;
    $data->cost_price = $request->input('cost_price');
    $data->selling_price = $request->input('selling_price');
    $data->pending_by = null;
    $data->sold_at = null;
    $data->added_by = $user->id;
    $data->is_approve = 'Not_Approved';
    $data->save();

    // Mobile History log
    MobileHistory::create([
        'mobile_id' => $data->id,
        'mobile_name' => $data->mobile_name,
        'customer_name' => $request->filled('vendor_id')
            ? optional(Vendor::find($request->vendor_id))->name
            : $request->input('customer_name'),
        'battery_health' => $data->battery_health,
        'cost_price' => $request->cost_price,
        'selling_price' => $request->selling_price,
        'availability_status' => 'Restored',
        'created_by' => $user->name,
        'group' => $group->name,
    ]);

    // Handle vendor-based restore
    if ($request->filled('vendor_id')) {
        $vendorId = $request->input('vendor_id');
        $vendor = Vendor::find($vendorId);

        // 1. Create Accounts Credit Entry
        Accounts::create([
            'vendor_id' => $vendorId,
            'category' => 'CR',
            'amount' => $request->cost_price,
            'description' => "Restored purchase of mobile: {$data->mobile_name}",
            'created_by' => $user->id,
        ]);

        // 2. Create a Purchase Transaction
        MobileTransaction::create([
            'mobile_id' => $data->id,
            'category' => 'Purchase',
            'cost_price' => $request->cost_price,
            'vendor_id' => $vendorId,
            'transaction_date' => now(),
            'user_id' => $user->id,
            'note' => 'Restored and repurchased from vendor',
        ]);
    }

    if ($request->filled('pay_amount') && $request->pay_amount > 0) {
        Accounts::create([
            'vendor_id' => $request->input('vendor_id') ?? null,
            'category' => 'DB',
            'amount' => $request->input('pay_amount'),
            'description' => "Payment made during restore for mobile: " . $request->input('mobile_name'),
            'created_by' => auth()->id(),
        ]);
    }

    return redirect()->back()->with('success', 'Mobile restored successfully.');
}


    public function pendingRestore(Request $request)
    {
        $group = group::find($request->group_id);
        $data = Mobile::findOrFail($request->id);
        // dd($request->id);
        $user = auth()->user();

        if ($request->availability == 'Pending') {
            return redirect()->back()->with('danger', 'Please Select a Different Option');
        }

        $data->cost_price = $request->input('cost_price');
        $data->selling_price = $request->input('selling_price');
        $data->availability = $request->input('availability');
        $data->battery_health = $request->input('battery_health');
        $data->group_id = $request->input('group_id');
        $data->save();

        MobileHistory::create([
            'mobile_id' => $data->id,
            'mobile_name' => $data->mobile_name,
            'customer_name' => $data->customer_name,
            'battery_health' => $data->battery_health,
            'cost_price' => $data->cost_price,
            'selling_price' => $data->selling_price,
            'availability_status' => 'Got back the mobile',
            'created_by' => $user->name,
            'group' =>$group->name,
        ]);
        return redirect()->back()->with('success', 'Mobile Restored successfully.');
    }

    public function receivedPendingRestore(Request $request)
    {
        $data = Mobile::findOrFail($request->id);
        // dd($request->id);

        if ($request->availability == 'Pending') {
            return redirect()->back()->with('danger', 'Please Select a Different Option');
        }

        $data->cost_price = $request->input('cost_price');
        $data->selling_price = $request->input('selling_price');
        $data->availability = $request->input('availability');
        $data->battery_health = $request->input('battery_health');
        $data->save();
        return redirect()->back()->with('success', 'Mobile Restored successfully.');
    }



    public function findMobile($id)
    {
        $filterId = Mobile::find($id);
        // dd($filterId);
        if (!$filterId) {

            return response()->json(['message' => 'Id not found'], 404);
        }

        return response()->json(['result' => $filterId]);

    }


    public function findApMobile($id)
    {
        $filterId = Mobile::find($id);
        // dd($filterId);
        if (!$filterId) {

            return response()->json(['message' => 'Id not found'], 404);
        }

        return response()->json(['result' => $filterId]);

    }


    public function transferMobile(Request $request)
    {
        // Validate the request data
        $request->validate([
            'to_user_id' => 'required',
            'mobile_id' => 'required',
            // Add other validation rules if needed
        ]);

        // Find the authenticated user
        $fromUser = auth()->user();

        // Find the user to transfer the mobile to
        $toUser = User::findOrFail($request->to_user_id);

        // Check if the transfer is being made to the same user
        if ($toUser->id === $fromUser->id) {
            return redirect()->back()->with('danger', 'Please select another user to transfer the mobile.');
        }

        // Find the mobile device to be transferred
        $mobile = Mobile::find($request->mobile_id);

        // Update the mobile device's ownership
        $mobile->user_id = $toUser->id;
        $mobile->is_transfer = true;
        $mobile->save();

        // Create the transfer record
        $transferRecord = new TransferRecord();
        $transferRecord->from_user_id = $fromUser->id;
        $transferRecord->to_user_id = $toUser->id;
        $transferRecord->mobile_id = $mobile->id;
        $transferRecord->transfer_time = Carbon::now(); // Set the current timestamp
        // $transferRecord->t_check = true;
        // Set other transfer record data if needed
        $transferRecord->save();




        return redirect()->back()->with('success', 'Mobile transferred successfully.');
    }


    public function moveToInventory(Request $request)
    {
        // dd($request);
        // Find the authenticated user
        $userId = auth()->user()->id;

        // Retrieve the mobile ID from the request
        $mobileId = $request->input('mobile_id');

        // Find the mobile
        $mobile = Mobile::find($mobileId);

        // Check if the mobile belongs to the authenticated user
        if ($mobile->original_owner_id == $userId) {
            $mobile->is_transfer = false;
            $mobile->save();

            return redirect()->back()->with('success', 'Mobile has been moved to main inventory.');
        } else {
            return redirect()->back()->with('danger', "Mobile can't be moved.");
        }
    }


    public function approve(Request $request)
    {
        $mobile = Mobile::findOrFail($request->id);
        $password = $request->input('password');
        $masterPassword = MasterPassword::first();
        // Check if the authenticated user ID matches the original owner ID
        if (auth()->user()->id === $mobile->original_owner_id && $password === $masterPassword->approve_password) {
            $mobile->is_approve = $request->input('is_approve');
            $mobile->save();

            return redirect()->back()->with('success', 'Mobile has been approved successfully.');
        } else {
            return redirect()->back()->with('danger', 'You cannot approve this mobile.');
        }
    }


    public function approveMobile(Request $request)
    {
        $mobile = Mobile::findOrFail($request->id);
        $password = $request->input('password');
        $masterPassword = MasterPassword::first();

        // Check if password matches the approve_password
        if ($password === $masterPassword->approve_password) {
            $mobile->is_approve = $request->input('is_approve');
            $mobile->save();

            return redirect()->back()->with('success', 'Mobile has been approved successfully.');
        } else {
            return redirect()->back()->with('danger', 'Incorrect approve password.');
        }
    }


    public function moveToOwner(Request $request)
    {
        $mobileId = $request->input('id');

        $mobile = Mobile::findOrFail($mobileId);
        $mobile->user_id = $mobile->original_owner_id;
        $mobile->is_transfer = false;
        $mobile->save();

        // Perform any additional actions or redirect as needed

        return redirect()->back()->with('success', 'Mobile transferred to the original owner successfully.');
    }

    public function otherInventory($id)
    {
        $mobileData = Mobile::where('user_id', $id)
            ->where('is_transfer', false)
            ->where('availability', 'Available')
            ->get();

        return view('otherinventory', ['mobileData' => $mobileData]);
    }

    public function otherTotalInventory($id)
    {
        $mobileData = Mobile::where('user_id', $id)
            ->where('availability', 'Available')
            ->get();

        return view('othertotalinventory', ['mobileData' => $mobileData]);
    }

    public function otherSoldInventory($id)
    {
        $mobileData = Mobile::where('user_id', $id)
            ->where('is_transfer', false)
            ->where('availability', 'Sold')
            ->get();

        return view('othersoldinventory', ['mobileData' => $mobileData]);
    }

    public function otherPendingInventory($id)
    {
        $mobileData = Mobile::where('user_id', $id)
            ->where('is_transfer', false)
            ->where('availability', 'Pending')
            ->get();

        return view('otherpendinginventory', ['mobileData' => $mobileData]);
    }

    public function otherTransferInventory($id)
    {
        $mobileData = TransferRecord::with('fromUser', 'toUser', 'mobile')
            ->whereIn('id', function ($query) use ($id) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('transfer_records')
                    ->groupBy('mobile_id');
            })
            ->where('to_user_id', $id)
            ->whereHas('mobile', function ($query) use ($id) {
                $query->where('user_id', $id)
                    ->where('availability', 'Available');
            })
            ->whereHas('mobile', function ($query) {
                $query->where('is_transfer', true);
            })
            ->get();

        return view('othertransferinventory', ['mobileData' => $mobileData]);
    }
    public function otherTransferSoldInventory($id)
    {
        $mobileData = TransferRecord::with('fromUser', 'toUser', 'mobile')
            ->whereIn('id', function ($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('transfer_records')
                    ->groupBy('mobile_id');
            })
            ->where('to_user_id', $id)
            ->whereHas('mobile', function ($query) use ($id) {
                $query->where('user_id', $id)
                    ->where('availability', 'Sold');
            })
            ->whereHas('mobile', function ($query) {
                $query->where('is_transfer', true);
            })
            ->get();

        return view('othertransfersoldinventory', ['mobileData' => $mobileData]);
    }




    public function destroy(Request $request)
    {
        $mobile = Mobile::find($request->id);

        if (!$mobile) {
            return redirect()->back()->with('danger', 'Mobile not found.');
        }

        $password = $request->input('password');
        $masterPassword = MasterPassword::first();

        // Check against delete_password
        if ($password === $masterPassword->delete_password) {
            $mobile->availability= 'Deleted';
            $mobile->save();
            return redirect()->back()->with('success', 'Mobile deleted successfully.');
        } else {
            return redirect()->back()->with('danger', 'Incorrect delete password.');
        }
    }




    public function searchFilter()
    {

        $company = company::all();
        $group = group::all();
        return view('searchFilter', compact('company', 'group'));
    }

    public function apiSearchMobiles(Request $request)
    {
        // Start query with eager loading of related models
        $query = Mobile::with(['company', 'group', 'vendor']);

        // Apply filters if present in request
        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        if ($request->filled('group_id')) {
            $query->where('group_id', $request->group_id);
        }

        if ($request->filled('availability')) {
            $query->where('availability', $request->availability);
        }

        // Get and transform results
        $mobiles = $query->orderBy('created_at', 'desc')->get()->map(function ($m) {
            return [
                'id' => $m->id,
                'mobile_name' => $m->mobile_name,
                'imei_number' => $m->imei_number,
                'sim_lock' => $m->sim_lock,
                'color' => $m->color,
                'storage' => $m->storage,
                'battery_health' => $m->battery_health,
                'cost_price' => $m->cost_price,
                'selling_price' => $m->selling_price,
                'availability' => $m->availability,
                'is_transfer' => $m->is_transfer,
                'created_at' => $m->created_at->format('Y-m-d'),
                'company_name' => optional($m->company)->name,
                'group_name' => optional($m->group)->name,
                'vendor_name' => optional($m->vendor)->name,
            ];
        });

        // Return as JSON
        return response()->json($mobiles);
    }

   public function Report(Request $request)
{
    $company = company::orderBy('name')->get();
    $group   = group::orderBy('name')->get();

    $perPage = (int) $request->input('per_page', 25);

    $base = Mobile::query()
        ->with([
            'vendor:id,name',
            'soldVendor:id,name',
            'company:id,name',
            'group:id,name',
            'latestSaleTransaction.vendor:id,name','latestVendorTransaction'
        ])
        ->when($request->filled('start_date'), fn($q) => $q->whereDate('created_at', '>=', $request->start_date))
        ->when($request->filled('end_date'),   fn($q) => $q->whereDate('created_at', '<=', $request->end_date))
        ->when($request->filled('availability'), fn($q) => $q->where('availability', $request->availability))
        ->when($request->filled('company_id'), fn($q) => $q->where('company_id', $request->company_id))
        ->when($request->filled('group_id'),   fn($q) => $q->where('group_id', $request->group_id))
        ->latest('created_at');

    $mobiles = (clone $base)->paginate($perPage)->withQueryString();

    // Summary (matches your previous logic)
    if ($request->availability === 'Sold') {
        $totalCost = (clone $base)->sum('cost_price');
        $totalSold = (clone $base)->sum('selling_price');
        $summary = ['label' => 'Total Profit from Sold Mobiles', 'value' => $totalSold - $totalCost];
    } elseif ($request->availability === 'Pending') {
        $totalCost = (clone $base)->sum('cost_price');
        $summary = ['label' => 'Total Cost of Pending Mobiles', 'value' => $totalCost];
    } else {
        $availableOnly = (clone $base);
        if ($request->availability !== 'Available') {
            $availableOnly->where('availability', 'Available');
        }
        $totalCost = $availableOnly->sum('cost_price');
        $summary = ['label' => 'Total Cost of Available Mobiles', 'value' => $totalCost];
    }

    $filters = [
        'start_date'  => $request->start_date,
        'end_date'    => $request->end_date,
        'availability'=> $request->availability,
        'company_id'  => $request->company_id,
        'group_id'    => $request->group_id,
        'per_page'    => $perPage,
    ];

    return view('report', compact('company', 'group', 'mobiles', 'summary', 'filters'));
}



    // public function multipleEntries()
    // {
    //     $companies = Company::all();
    //     $groups = Group::all();
    //     $vendors = Vendor::all();

    //     return view('multipleEntries', compact('companies', 'groups', 'vendors'));
    // }

    public function multipleEntries()
    {
        $companies = Company::all();
        $groups = Group::all();
        $vendors = Vendor::all();

        // If vendor is preselected (optional logic depending on your setup)
        $selectedVendorId = request()->vendor_id;

        $vendorBalance = null;
        $vendorStatus = null;

        if ($selectedVendorId) {
            $debit = Accounts::where('vendor_id', $selectedVendorId)->sum('debit');
            $credit = Accounts::where('vendor_id', $selectedVendorId)->sum('credit');
            $balance = $credit - $debit;

            $vendorBalance = abs($balance);
            $vendorStatus = $balance < 0 ? 'Debit' : ($balance > 0 ? 'Credit' : 'Settled');
        }

        return view('multipleEntries', compact('companies', 'groups', 'vendors', 'vendorBalance', 'vendorStatus'));
    }

    public function checkIMEI(Request $request)
    {
        $exists = Mobile::where('imei_number', $request->imei)->exists();
        return response()->json(['exists' => $exists]);
    }

    // public function storeMultipleMobiles(Request $request)
    // {
    //     $vendorId = $request->vendor_id;
    //     $mobiles = $request->mobiles;


    //     $vendor = Vendor::find($vendorId);
    //     $vendorName = $vendor ? $vendor->name : 'Unknown Vendor';

    //     foreach ($mobiles as $entry) {
    //         $mobile = new Mobile($entry);
    //         $mobile->user_id = auth()->id();
    //         $mobile->original_owner_id = auth()->id();
    //         $mobile->battery_health = $entry['battery_health'] ?? null;
    //         $mobile->availability = 'Available';
    //         $mobile->is_approve = 'Not_Approved';
    //         $mobile->vendor_id = $vendorId;
    //         $mobile->save();

    //         // Create mobile history
    //         MobileHistory::create([
    //             'mobile_id' => $mobile->id,
    //             'mobile_name' => $mobile->mobile_name,
    //             'customer_name' => $vendorName,
    //             'battery_health' => $mobile->battery_health,
    //             'cost_price' => $mobile->cost_price,
    //             'selling_price' => $mobile->selling_price,
    //             'availability_status' => 'Purchased',
    //         ]);
    //     }

    //     // Account entry for total cost
    //     if ($vendorId && isset($vendor)) {
    //         $totalCost = collect($mobiles)->sum('cost_price');

    //         Accounts::create([
    //             'vendor_id' => $vendorId,
    //             'category' => 'CR',
    //             'amount' => $totalCost,
    //             'description' => "Purchased " . count($mobiles) . " mobiles from {$vendor->name} (Bulk Entry)"
    //         ]);
    //     }

    //     return response()->json(['success' => true]);
    // }


    // public function storeMultipleMobiles(Request $request)
    // {
    //     $vendorId = $request->vendor_id;
    //     $mobiles = $request->mobiles;
    //     $user = auth()->user();

    //     $vendor = Vendor::find($vendorId);
    //     $vendorName = $vendor ? $vendor->name : 'Unknown Vendor';

    //     foreach ($mobiles as $entry) {
    //         $mobile = new Mobile($entry);
    //         $mobile->user_id = $user->id;
    //         $mobile->original_owner_id = $user->id;
    //         $mobile->battery_health = $entry['battery_health'] ?? null;
    //         $mobile->availability = 'Available';
    //         $mobile->is_approve = 'Not_Approved';
    //         $mobile->vendor_id = $vendorId;
    //         $mobile->added_by = $user->id;
    //         $mobile->save();

    //         $group = group::find($entry['group_id']);

    //         // Create mobile history
    //         MobileHistory::create([
    //             'mobile_id' => $mobile->id,
    //             'mobile_name' => $mobile->mobile_name,
    //             'customer_name' => $vendorName,
    //             'battery_health' => $mobile->battery_health,
    //             'cost_price' => $mobile->cost_price,
    //             'selling_price' => $mobile->selling_price,
    //             'availability_status' => 'Purchased',
    //             'created_by' => $user->name, // Storing username here
    //             'group' => $group->name,
    //         ]);
    //     }

    //     // Account entry for total cost
    //     if ($vendorId && $vendor) {
    //         $totalCost = collect($mobiles)->sum('cost_price');

    //         Accounts::create([
    //             'vendor_id' => $vendorId,
    //             'category' => 'CR',
    //             'amount' => $totalCost,
    //             'created_by' => $user->id, // Storing user ID here
    //             'description' => "Purchased " . count($mobiles) . " mobiles from {$vendorName} (Bulk Entry)"
    //         ]);
    //     }

    //     return response()->json(['success' => true]);
    // }

//     public function storeMultipleMobiles(Request $request)
// {
//     $vendorId = $request->vendor_id;
//     $mobiles = $request->mobiles;
//     $user = auth()->user();

//     $vendor = Vendor::find($vendorId);
//     $vendorName = $vendor ? $vendor->name : 'Unknown Vendor';

//     foreach ($mobiles as $entry) {
//         // Create Mobile record
//         $mobile = new Mobile($entry);
//         $mobile->user_id = $user->id;
//         $mobile->original_owner_id = $user->id;
//         $mobile->battery_health = $entry['battery_health'] ?? null;
//         $mobile->availability = 'Available';
//         $mobile->is_approve = 'Not_Approved';
//         $mobile->added_by = $user->id;
//         $mobile->save();

//         // Find Group
//         $group = Group::find($entry['group_id']);

//         // Create Purchase Transaction
//         MobileTransaction::create([
//             'mobile_id'     => $mobile->id,
//             'category'      => 'Purchase',
//             'cost_price'    => $entry['cost_price'],
//             'selling_price' => $entry['selling_price'] ?? null,
//             'vendor_id'     => $vendorId,
//             'transaction_date' => now(),
//             'user_id'       => $user->id,
//             'note'          => 'Bulk purchase entry',
//         ]);

//         // Create Mobile History
//         MobileHistory::create([
//             'mobile_id'       => $mobile->id,
//             'mobile_name'     => $mobile->mobile_name,
//             'customer_name'   => $vendorName,
//             'battery_health'  => $mobile->battery_health,
//             'cost_price'      => $entry['cost_price'],
//             'selling_price'   => $entry['selling_price'] ?? null,
//             'availability_status' => 'Purchased',
//             'created_by'      => $user->name,
//             'group'           => $group->name ?? 'Unknown',
//         ]);
//     }

//     // Account entry for total cost
//     if ($vendorId && $vendor) {
//         $totalCost = collect($mobiles)->sum('cost_price');

//         Accounts::create([
//             'vendor_id'  => $vendorId,
//             'category'   => 'CR',
//             'amount'     => $totalCost,
//             'created_by' => $user->id,
//             'description' => "Purchased " . count($mobiles) . " mobiles from {$vendorName} (Bulk Entry)"
//         ]);
//     }

//     return response()->json(['success' => true]);
// }
public function storeMultipleMobiles(Request $request)
{
    try {
        $vendorId = $request->vendor_id;
        $mobiles = $request->mobiles;
        $payAmount = $request->pay_amount;
        $user = auth()->user();

        $vendor = Vendor::find($vendorId);
        $vendorName = $vendor ? $vendor->name : 'Unknown Vendor';

        foreach ($mobiles as $entry) {
            $mobile = new Mobile($entry);
            $mobile->user_id = $user->id;
            $mobile->original_owner_id = $user->id;
            $mobile->battery_health = $entry['battery_health'] ?? null;
            $mobile->availability = 'Available';
            $mobile->is_approve = 'Not_Approved';
            $mobile->added_by = $user->id;
            $mobile->save();

            $group = Group::find($entry['group_id']);

            MobileTransaction::create([
                'mobile_id'     => $mobile->id,
                'category'      => 'Purchase',
                'cost_price'    => $entry['cost_price'],
                'selling_price' => $entry['selling_price'] ?? null,
                'vendor_id'     => $vendorId,
                'transaction_date' => now(),
                'user_id'       => $user->id,
                'note'          => 'Bulk purchase entry',
            ]);

            MobileHistory::create([
                'mobile_id'       => $mobile->id,
                'mobile_name'     => $mobile->mobile_name,
                'customer_name'   => $vendorName,
                'battery_health'  => $mobile->battery_health,
                'cost_price'      => $entry['cost_price'],
                'selling_price'   => $entry['selling_price'] ?? null,
                'availability_status' => 'Purchased',
                'created_by'      => $user->name,
                'group'           => $group->name ?? 'Unknown',
            ]);
        }

        if ($vendorId && $vendor) {
            $totalCost = collect($mobiles)->sum('cost_price');

            Accounts::create([
                'vendor_id'  => $vendorId,
                'category'   => 'CR',
                'amount'     => $totalCost,
                'created_by' => $user->id,
                'description' => "Purchased " . count($mobiles) . " mobiles from {$vendorName} (Bulk Entry)"
            ]);

            if ($payAmount > 0) {
                Accounts::create([
                    'vendor_id'  => $vendorId,
                    'category'   => 'DB',
                    'amount'     => $payAmount,
                    'created_by' => $user->id,
                    'description' => "Partial payment of Rs. {$payAmount} made to {$vendorName} at time of purchase"
                ]);
            }
        }

        return response()->json(['success' => true]);

    } catch (\Throwable $e) {
        // Log the error for Laravel logs
        \Log::error('storeMultipleMobiles error: ' . $e->getMessage(), [
            'line' => $e->getLine(),
            'file' => $e->getFile(),
            'trace' => $e->getTraceAsString()
        ]);

        // Return a readable error to frontend
        return response()->json([
            'success' => false,
            'message' => 'Server Error: ' . $e->getMessage(),
        ], 500);
    }
}





    public function filterMobiles(Request $request)
{
    $query = Mobile::where('availability', 'Available')
        ->where('is_transfer', false)
        ->with(['group', 'company', 'vendor', 'creator']);

    if ($request->filled('company_id')) {
        $query->where('company_id', $request->company_id);
    }

    if ($request->filled('group_id')) {
        $query->where('group_id', $request->group_id);
    }

    $mobiles = $query->get();

    $html = view('partials.mobile_table_rows', compact('mobiles'))->render();

    return response()->json(['html' => $html]);
}

public function soldTransactions()
{
    $transactions = MobileTransaction::with(['mobile.company', 'mobile.group', 'vendor', 'user'])
                    ->where('category', 'Sale')
                    ->latest()
                    ->get();

    return view('soldTransactions', compact('transactions'));
}

public function deletedMobiles(){
    $users = User::all();
    $companies = Company::all();
   
   

    $mobile = Mobile::where('availability', 'Deleted')
        ->where('is_transfer', false)->with(['group', 'company', 'vendor', 'creator','latestVendorTransaction.vendor'])
        ->get();
    // dd($mobile);
    return view('deleteinventory',compact('mobile'));
}

public function approveBulk(Request $request)
{
    // Only allow if logged in user is admin (id = 1 or 2)
    if (!in_array(auth()->id(), [1, 2])) {
        // For AJAX request, return JSON error
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => "You can't approve these mobiles"
            ], 403);
        }
        // For normal requests, redirect back with error
        return redirect()->back()->with('danger', "You can't approve these mobiles");
    }

    $ids = $request->input('mobile_ids', []);
    if (!empty($ids)) {
        Mobile::whereIn('id', $ids)->update(['is_approve' => 'Approved']);
        return response()->json(['success' => true]);
    }

    return response()->json(['success' => false, 'message' => 'No mobiles selected'], 400);
}

public function soldInventory(Request $request)
{
    // Read query string
    $perPage = (int) $request->input('per_page', 10);   // options we'll expose: 10, 25, 50, 100
    $search  = trim($request->input('q', ''));

    $query = Mobile::query()
        ->where('availability', 'Sold')
        ->where('is_approve', 'Not_Approved')
        ->with(['soldBy', 'latestSaleTransaction', 'group'])  // add 'group' to avoid N+1
        ->orderByDesc('sold_at');

    // Global search across relevant columns/relations
    if ($search !== '') {
        $query->where(function ($q) use ($search) {
            $q->where('mobile_name', 'like', "%{$search}%")
              ->orWhere('imei_number', 'like', "%{$search}%")
              ->orWhere('sim_lock', 'like', "%{$search}%")
              ->orWhere('color', 'like', "%{$search}%")
              ->orWhere('storage', 'like', "%{$search}%")
              ->orWhere('battery_health', 'like', "%{$search}%")
              ->orWhereHas('soldBy', fn($qq) => $qq->where('name', 'like', "%{$search}%"))
              ->orWhereHas('group',  fn($qq) => $qq->where('name', 'like', "%{$search}%"))
              ->orWhereHas('latestSaleTransaction', function ($qq) use ($search) {
                  $qq->where('customer_name', 'like', "%{$search}%")
                     ->orWhere('selling_price', 'like', "%{$search}%")
                     ->orWhere('cost_price', 'like', "%{$search}%");
              });
        });
    }

    // Paginate + keep q/per_page on links
    $mobiles = $query->paginate($perPage)->withQueryString();

    // (If you still need these in the view)
    $groups  = Group::all();
    $vendors = Vendor::all();

    return view('soldinventory', compact('mobiles', 'groups', 'vendors', 'search', 'perPage'));
}


}
