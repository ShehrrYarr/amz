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


    public function storeMobile(Request $request)
    {
        $validatedData = $request->validate([
            'mobile_name' => 'required',
            'imei_number' => 'required',
            'sim_lock' => 'required|in:J.V,PTA,Non-PTA',
            'color' => 'required',
            'storage' => 'required',
            'cost_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'company_id' => 'required|exists:companies,id',
            'group_id' => 'required|exists:groups,id',
            'vendor_id' => 'nullable|exists:vendors,id',
        ]);

        $userId = auth()->user()->id;

        // Check if IMEI already exists
        $existingMobile = Mobile::where('imei_number', $validatedData['imei_number'])->first();
        if ($existingMobile) {
            return redirect()->back()->with('danger', 'A mobile with this IMEI number already exists.');
        }

        // Create new Mobile record
        $mobile = new Mobile($validatedData);
        $mobile->user_id = auth()->id();
        $mobile->original_owner_id = auth()->id();
        $mobile->added_by = auth()->id(); // 👈 Track who added the mobile
        $mobile->battery_health = $request->battery_health;
        $mobile->availability = 'Available';
        $mobile->is_approve = 'Not_Approved';
        $mobile->added_by = $userId;
        $mobile->save();

        // Create vendor credit entry if vendor is present
        if ($request->filled('vendor_id')) {
            $vendor = Vendor::find($request->vendor_id);
            $vendorName = $vendor ? $vendor->name : 'Unknown Vendor';

            Accounts::create([
                'vendor_id' => $request->vendor_id,
                'category' => 'CR',
                'amount' => $request->cost_price,
                'description' => "Purchased mobile: {$mobile->mobile_name}",
                'created_by' => $userId,
            ]);
        }

        return redirect()->back()->with('success', 'Mobile created and account updated successfully.');
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
    //     if ($request->availability == 'Available') {
    //         return redirect()->back()->with('danger', 'Please select a different availability option.');
    //     }

    //     if (!$request->filled('customer_name') && !$request->filled('vendor_id')) {
    //         return redirect()->back()->with('danger', 'Enter customer name or select a vendor.');
    //     }

    //     $data = Mobile::findOrFail($request->id);
    //     $user = auth()->user();


    //     if ($request->filled('vendor_id') && $request->availability !== 'Pending') {
    //         $vendorId = $request->vendor_id;
    //         $data->sold_vendor_id = $vendorId;

    //         $vendor = Vendor::find($vendorId);
    //         $vendorName = $vendor ? $vendor->name : 'Unknown Vendor';
    //         $data->customer_name = $vendorName;

    //         $sellingPrice = (float) $request->selling_price;
    //         $paidAmount = (float) $request->pay_amount;
    //         $mobileName = $data->mobile_name;

    //         // Credit: Vendor owes you
    //         if ($sellingPrice > 0) {
    //             Accounts::create([
    //                 'vendor_id' => $vendorId,
    //                 'category' => 'DB',
    //                 'amount' => $sellingPrice,
    //                 'description' => "Vendor purchased mobile: {$mobileName}",
    //                 'created_by' => $user->id,
    //             ]);
    //         }

    //         // Debit: Vendor paid you (reducing their debt)
    //         if ($paidAmount > 0) {
    //             Accounts::create([
    //                 'vendor_id' => $vendorId,
    //                 'category' => 'CR',
    //                 'amount' => $paidAmount,
    //                 'description' => "Vendor paid for: {$mobileName}",
    //                 'created_by' => $user->id,
    //             ]);
    //         }
    //     } else {
    //         // Sold to walk-in customer (no vendor accounting)
    //         $data->customer_name = $request->input('customer_name');
    //         $data->sold_vendor_id = null;
    //     }

    //     // Update mobile sale details
    //     $data->selling_price = $request->input('selling_price');
    //     $data->availability = $request->input('availability');
    //     $data->sold_at = Carbon::now();
    //     $data->is_approve = $request->input('is_approve');
    //     $data->sold_by = $user->id;
    //     $data->save();

    //     // Record mobile history
    //     $historyCustomerName = $data->sold_vendor_id
    //         ? ($vendor ? $vendor->name : 'Unknown Vendor')
    //         : $data->customer_name;

    //     if ($data->availability == 'Sold') {
    //         MobileHistory::create([
    //             'mobile_id' => $data->id,
    //             'mobile_name' => $data->mobile_name,
    //             'customer_name' => $historyCustomerName,
    //             'battery_health' => $data->battery_health,
    //             'cost_price' => $data->cost_price,
    //             'selling_price' => $data->selling_price,
    //             'availability_status' => 'Sold',
    //             'created_by' => $user->name,
    //         ]);
    //     } elseif ($data->availability == 'Pending') {
    //         MobileHistory::create([
    //             'mobile_id' => $data->id,
    //             'mobile_name' => $data->mobile_name,
    //             'customer_name' => $historyCustomerName,
    //             'battery_health' => $data->battery_health,
    //             'cost_price' => $data->cost_price,
    //             'selling_price' => $data->selling_price,
    //             'availability_status' => 'Pending',
    //             'created_by' => $user->name,
    //         ]);
    //     }



    //     return redirect()->back()->with('success', 'Mobile status changed and account updated successfully.');
    // }


    public function sellMobile(Request $request)
    {
        if ($request->availability == 'Available') {
            return redirect()->back()->with('danger', 'Please select a different availability option.');
        }

        if (!$request->filled('customer_name') && !$request->filled('vendor_id')) {
            return redirect()->back()->with('danger', 'Enter customer name or select a vendor.');
        }

        $data = Mobile::findOrFail($request->id);
        $user = auth()->user();

        $data->selling_price = $request->input('selling_price');
        $data->availability = $request->input('availability');
        $data->sold_at = Carbon::now();
        $data->is_approve = $request->input('is_approve');
        $data->sold_by = $user->id;

        if ($request->availability === 'Sold') {
            if ($request->filled('vendor_id')) {
                // Sold to Vendor
                $vendorId = $request->vendor_id;
                $data->sold_vendor_id = $vendorId;

                $vendor = Vendor::find($vendorId);
                $vendorName = $vendor ? $vendor->name : 'Unknown Vendor';
                $data->customer_name = $vendorName;

                $sellingPrice = (float) $request->selling_price;
                $paidAmount = (float) $request->pay_amount;
                $mobileName = $data->mobile_name;

                // Accounts entries
                if ($sellingPrice > 0) {
                    Accounts::create([
                        'vendor_id' => $vendorId,
                        'category' => 'DB',
                        'amount' => $sellingPrice,
                        'description' => "Vendor purchased mobile: {$mobileName}",
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
            } else {
                // Sold to walk-in customer
                $data->customer_name = $request->input('customer_name');
                $data->sold_vendor_id = null;
            }

            $data->save();

            MobileHistory::create([
                'mobile_id' => $data->id,
                'mobile_name' => $data->mobile_name,
                'customer_name' => $data->customer_name,
                'battery_health' => $data->battery_health,
                'cost_price' => $data->cost_price,
                'selling_price' => $data->selling_price,
                'availability_status' => 'Sold',
                'created_by' => $user->name,
            ]);
        } elseif ($request->availability === 'Pending') {
            if ($request->filled('vendor_id')) {
                // Pending to Vendor
                $vendorId = $request->vendor_id;
                $data->sold_vendor_id = $vendorId;
                $data->pending_by = $user->id;

                $vendor = Vendor::find($vendorId);
                $vendorName = $vendor ? $vendor->name : 'Unknown Vendor';
                $data->customer_name = $vendorName;
            } else {
                // Pending to walk-in customer
                $data->customer_name = $request->input('customer_name');
                $data->sold_vendor_id = null;
            }

            $data->save();

            MobileHistory::create([
                'mobile_id' => $data->id,
                'mobile_name' => $data->mobile_name,
                'customer_name' => $data->customer_name,
                'battery_health' => $data->battery_health,
                'cost_price' => $data->cost_price,
                'selling_price' => $data->selling_price,
                'availability_status' => 'Pending',
                'created_by' => $user->name,
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
            $data->customer_name = $request->input('customer_name');
            $data->battery_health = $request->input('battery_health');
            $data->is_approve = $request->input('is_approve');
            $data->company_id = $request->input('company_id');
            $data->vendor_id = $request->input('vendor_id');
            $data->group_id = $request->input('group_id');

            $data->save();

            return redirect()->back()->with('success', 'Mobile updated successfully.');
        } else {
            return redirect()->back()->with('danger', 'Incorrect update password.');
        }
    }


    // public function restoreMobile(Request $request)
    // {
    //     $data = Mobile::findOrFail($request->id);

    //     $restoreMobile = new Restore();
    //     $restoreMobile->mobile_name = $request->input('mobile_name');
    //     $restoreMobile->imei_number = $request->input('imei_number');
    //     $restoreMobile->customer_name = $data->customer_name;
    //     $restoreMobile->old_cost_price = $data->cost_price;
    //     $restoreMobile->old_selling_price = $data->selling_price;
    //     $restoreMobile->new_cost_price = $request->input('cost_price');
    //     $restoreMobile->new_selling_price = $request->input('selling_price');
    //     $restoreMobile->restore_by = auth()->user()->name;
    //     $restoreMobile->save();

    //     // dd($request);
    //     $data->cost_price = $request->input('cost_price');
    //     $data->selling_price = $request->input('selling_price');
    //     $data->availability = $request->input('availability');
    //     $data->customer_name = $request->input('customer_name');
    //     $data->battery_health = $request->input('battery_health');
    //     $data->is_approve = 'Not_Approved';
    //     $data->save();

    //     MobileHistory::create([
    //         'mobile_id' => $data->id,
    //         'mobile_name' => $data->mobile_name,
    //         'customer_name' => $data->customer_name,
    //         'battery_health' => $data->battery_health,
    //         'cost_price' => $data->cost_price,
    //         'selling_price' => $data->selling_price,
    //         'availability_status' => 'Restored',
    //     ]);

    //     return redirect()->back()->with('success', 'Mobile Restored successfully.');

    // }

    public function restoreMobile(Request $request)
    {
        $data = Mobile::findOrFail($request->id);
        $user = auth()->user();

        // Log the restore details
        $restoreMobile = new Restore();
        $restoreMobile->mobile_name = $request->input('mobile_name');
        $restoreMobile->imei_number = $request->input('imei_number');
        $restoreMobile->customer_name = $data->customer_name;
        $restoreMobile->old_cost_price = $data->cost_price;
        $restoreMobile->old_selling_price = $data->selling_price;
        $restoreMobile->new_cost_price = $request->input('cost_price');
        $restoreMobile->new_selling_price = $request->input('selling_price');
        $restoreMobile->restore_by = $user->name;
        $restoreMobile->save();

        // Update mobile table with new data
        $data->cost_price = $request->input('cost_price');
        $data->selling_price = $request->input('selling_price');
        $data->availability = $request->input('availability');
        $data->customer_name = $request->input('customer_name');
        $data->battery_health = $request->input('battery_health');
        $data->sold_vendor_id = null;
        $data->sold_by = null;
        $data->pending_by = null;
        $data->customer_name = null;
        $data->sold_at = null;
        $data->is_approve = 'Not_Approved';
        $data->save();

        // Add mobile history
        MobileHistory::create([
            'mobile_id' => $data->id,
            'mobile_name' => $data->mobile_name,
            'customer_name' => $request->customer_name,
            'battery_health' => $data->battery_health,
            'cost_price' => $data->cost_price,
            'selling_price' => $data->selling_price,
            'availability_status' => 'Restored',
            'created_by' => $user->name,
        ]);

        return redirect()->back()->with('success', 'Mobile Restored successfully.');
    }

    public function pendingRestore(Request $request)
    {
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
                $query->select(\DB::raw('MAX(id)'))
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
                $query->select(\DB::raw('MAX(id)'))
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
            $mobile->delete();
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

    public function fetch(Request $request)
    {
        $query = Mobile::with(['vendor', 'soldVendor', 'company', 'group']);

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->filled('availability')) {
            $query->where('availability', $request->availability);
        }

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        if ($request->filled('group_id')) {
            $query->where('group_id', $request->group_id);
        }

        $mobiles = $query->get();

        // Calculate summary
        $summary = '';
        if ($request->availability === 'Available' || !$request->availability) {
            $summary = [
                'label' => 'Total Cost of Available Mobiles',
                'value' => $mobiles->sum('cost_price'),
            ];
        } elseif ($request->availability === 'Pending') {
            $summary = [
                'label' => 'Total Cost of Pending Mobiles',
                'value' => $mobiles->sum('cost_price'),
            ];
        } elseif ($request->availability === 'Sold') {
            $totalCost = $mobiles->sum('cost_price');
            $totalSold = $mobiles->sum('selling_price');
            $summary = [
                'label' => 'Total Profit from Sold Mobiles',
                'value' => $totalSold - $totalCost,
            ];
        }

        return response()->json([
            'summary' => $summary,
            'mobiles' => $mobiles,
            'availability' => $request->availability,
        ]);
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


    public function storeMultipleMobiles(Request $request)
    {
        $vendorId = $request->vendor_id;
        $mobiles = $request->mobiles;
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
            $mobile->vendor_id = $vendorId;
            $mobile->added_by = $user->id;
            $mobile->save();

            // Create mobile history
            MobileHistory::create([
                'mobile_id' => $mobile->id,
                'mobile_name' => $mobile->mobile_name,
                'customer_name' => $vendorName,
                'battery_health' => $mobile->battery_health,
                'cost_price' => $mobile->cost_price,
                'selling_price' => $mobile->selling_price,
                'availability_status' => 'Purchased',
                'created_by' => $user->name, // Storing username here
            ]);
        }

        // Account entry for total cost
        if ($vendorId && $vendor) {
            $totalCost = collect($mobiles)->sum('cost_price');

            Accounts::create([
                'vendor_id' => $vendorId,
                'category' => 'CR',
                'amount' => $totalCost,
                'created_by' => $user->id, // Storing user ID here
                'description' => "Purchased " . count($mobiles) . " mobiles from {$vendorName} (Bulk Entry)"
            ]);
        }

        return response()->json(['success' => true]);
    }



}
