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

        $mobile = new Mobile($validatedData);
        $mobile->user()->associate(auth()->user());
        $mobile->original_owner()->associate(auth()->user());
        $mobile->battery_health = $request->battery_health;
        $mobile->availability = 'Available';
        $mobile->is_approve = 'Not_Approved';

        $mobile->save();

        // Create account entry if vendor is involved
        if ($mobile->vendor_id) {
            Accounts::create([
                'vendor_id' => $mobile->vendor_id,
                'category' => 'DB',
                'amount' => $mobile->cost_price,
                'description' => 'Purchased ' . $mobile->mobile_name,
            ]);
        }

        return redirect()->back()->with('success', 'Mobile created successfully.');
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



    public function sellMobile(Request $request)
{
    if ($request->availability == 'Available') {
        return redirect()->back()->with('danger', 'Please select a different availability option.');
    }

    if (!$request->filled('customer_name') && !$request->filled('vendor_id')) {
        return redirect()->back()->with('danger', 'Enter customer name or select a vendor.');
    }

    $data = Mobile::findOrFail($request->id);

    // Handle vendor sale
    if ($request->filled('vendor_id')) {
        $data->sold_vendor_id = $request->vendor_id;

        $vendor = Vendor::find($request->vendor_id);
        $historyVendorName = $vendor ? $vendor->name : 'Unknown Vendor';
        $data->customer_name = $historyVendorName;

        $sellingPrice = (float) $request->selling_price;
        $paidAmount = (float) $request->pay_amount;
        $mobileName = $data->mobile_name;

        // ✅ 1. Record the full amount vendor has to pay (Debit)
        if ($sellingPrice > 0) {
            Accounts::create([
                'vendor_id' => $request->vendor_id,
                'category' => 'DB',
                'amount' => $sellingPrice,
                'description' => "Vendor purchase: Mobile {$mobileName}",
            ]);
        }

        // ✅ 2. Record the amount vendor actually paid (Credit)
        if ($paidAmount > 0) {
            Accounts::create([
                'vendor_id' => $request->vendor_id,
                'category' => 'CR',
                'amount' => $paidAmount,
                'description' => "Vendor paid: Mobile {$mobileName}",
            ]);
        }
    } else {
        // Handle customer sale
        $data->customer_name = $request->input('customer_name');
        $data->sold_vendor_id = null;
    }

    // Common sale data
    $data->selling_price = $request->input('selling_price');
    $data->availability = $request->input('availability');
    $data->sold_at = Carbon::now();
    $data->is_approve = $request->input('is_approve');
    $data->save();

    // History entry
    $historyCustomerName = $data->sold_vendor_id
        ? ($vendor ? $vendor->name : 'Unknown Vendor')
        : $data->customer_name;

    MobileHistory::create([
        'mobile_id' => $data->id,
        'mobile_name' => $data->mobile_name,
        'customer_name' => $historyCustomerName,
        'battery_health' => $data->battery_health,
        'cost_price' => $data->cost_price,
        'selling_price' => $data->selling_price,
        'availability_status' => $data->availability,
    ]);

    return redirect()->back()->with('success', 'Mobile status changed successfully.');
}



    // public function sellMobile(Request $request)
    // {

    //     if ($request->availability == 'Available') {
    //         return redirect()->back()->with('danger', 'please select a different option');
    //     }

    //     if (!$request->filled('customer_name') && !$request->filled('vendor_id')) {
    //         return redirect()->back()->with('danger', 'Enter customer name or select a vendor.');
    //     }

    //     $data = Mobile::findOrFail($request->id);

    //     // Check if vendor_id is present
    //     if ($request->filled('vendor_id')) {
    //         // Vendor sale
    //         $data->sold_vendor_id = $request->vendor_id;
    //         $vendorName = vendor::find($data->sold_vendor_id);
    //         $historyVendorName = $vendorName ? $vendorName->name : 'Unknown Vendor';
    //         $data->customer_name = $historyVendorName; // Optional: clear customer field if vendor sale
    //     } else {
    //         // Customer sale
    //         $data->customer_name = $request->input('customer_name');
    //         $data->sold_vendor_id = null; // Optional: clear vendor field if customer sale
    //     }

    //     $data->selling_price = $request->input('selling_price');
    //     $data->availability = $request->input('availability');
    //     $data->sold_at = Carbon::now();
    //     $data->is_approve = $request->input('is_approve');

    //     $data->save();

    //     // Determine customer name for history
    //     if ($data->sold_vendor_id) {
    //         $vendor = vendor::find($data->sold_vendor_id);
    //         $historyCustomerName = $vendor ? $vendor->name : 'Unknown Vendor';
    //     } else {
    //         $historyCustomerName = $data->customer_name;
    //     }

    //     // Save history record
    //     MobileHistory::create([
    //         'mobile_id' => $data->id,
    //         'mobile_name' => $data->mobile_name,
    //         'customer_name' => $historyCustomerName,
    //         'battery_health' => $data->battery_health,
    //         'cost_price' => $data->cost_price,
    //         'selling_price' => $data->selling_price,
    //         'availability_status' => $data->availability,
    //     ]);

    //     return redirect()->back()->with('success', 'Mobile status changed successfully.');
    // }

    public function updateMobile(Request $request)
    {
        $data = Mobile::findOrFail($request->id);
        $password = $request->input('password');
        $masterPassword = MasterPassword::first();
        $mPass = $masterPassword->password;

        // Check if the authenticated user ID is 2
        if ($password == $mPass) {
            // Update publication data
            $data->mobile_name = $request->input('mobile_name');
            $data->imei_number = $request->input('imei_number');
            $data->sim_lock = $request->input('sim_lock');
            $data->color = $request->input('color');
            $data->storage = $request->input('storage');
            $data->cost_price = $request->input('cost_price');
            $data->selling_price = $request->input('selling_price');
            $data->availability = $request->input('availability');
            $data->customer_name = $request->input('customer_name');
            // $data->sold_at = now();
            $data->battery_health = $request->input('battery_health');
            $data->is_approve = $request->input('is_approve');
            $data->company_id = $request->input('company_id');
            $data->vendor_id = $request->input('vendor_id');
            $data->group_id = $request->input('group_id');

            $data->save();

            return redirect()->back()->with('success', 'Mobile updated successfully.');
        } else {
            return redirect()->back()->with('danger', "incorrect Password.");
        }
    }


    public function restoreMobile(Request $request)
    {
        $data = Mobile::findOrFail($request->id);

        $restoreMobile = new Restore();
        $restoreMobile->mobile_name = $request->input('mobile_name');
        $restoreMobile->imei_number = $request->input('imei_number');
        $restoreMobile->customer_name = $data->customer_name;
        $restoreMobile->old_cost_price = $data->cost_price;
        $restoreMobile->old_selling_price = $data->selling_price;
        $restoreMobile->new_cost_price = $request->input('cost_price');
        $restoreMobile->new_selling_price = $request->input('selling_price');
        $restoreMobile->restore_by = auth()->user()->name;
        $restoreMobile->save();

        // dd($request);
        $data->cost_price = $request->input('cost_price');
        $data->selling_price = $request->input('selling_price');
        $data->availability = $request->input('availability');
        $data->customer_name = $request->input('customer_name');
        $data->battery_health = $request->input('battery_health');
        $data->is_approve = 'Not_Approved';
        $data->save();

        MobileHistory::create([
            'mobile_id' => $data->id,
            'mobile_name' => $data->mobile_name,
            'customer_name' => $data->customer_name,
            'battery_health' => $data->battery_health,
            'cost_price' => $data->cost_price,
            'selling_price' => $data->selling_price,
            'availability_status' => $data->availability,
        ]);

        return redirect()->back()->with('success', 'Mobile Restored successfully.');

    }

    public function pendingRestore(Request $request)
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

        MobileHistory::create([
            'mobile_id' => $data->id,
            'mobile_name' => $data->mobile_name,
            'customer_name' => $data->customer_name,
            'battery_health' => $data->battery_health,
            'cost_price' => $data->cost_price,
            'selling_price' => $data->selling_price,
            'availability_status' => $data->availability,
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





    // public function updateMobile(Request $request)
// {
//     $data = Mobile::findOrFail($request->id);

    //     // Update mobile data
//     $data->mobile_name = $request->input('mobile_name');
//     $data->imei_number = $request->input('imei_number');
//     $data->sim_lock = $request->input('sim_lock');
//     $data->color = $request->input('color');
//     $data->storage = $request->input('storage');
//     $data->cost_price = $request->input('cost_price');
//     $data->selling_price = $request->input('selling_price');
//     $data->availability = $request->input('availability');
//     $data->sold_at = now();

    //     // Update the is_approve field
//     $data->is_approve = $request->input('is_approve');

    //     $data->save();

    //     return redirect()->back()->with('success', 'Mobile updated successfully.');
// }


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

    //     public function transferMobile(Request $request)
// {

    //     // Validate the request data
//     $request->validate([
//         'to_user_id' => 'required',
//         'mobile_id' => 'required',
//         // Add other validation rules if needed
//     ]);

    //     // Find the authenticated user
//     $fromUser = auth()->user();

    //     // Find the user to transfer the mobile to
//     $toUser = User::findOrFail($request->to_user_id);

    //     // Find the mobile device to be transferred
//     $mobile = Mobile::findOrFail($request->mobile_id);



    //     // Update the mobile device's ownership
//     $mobile->user_id = $toUser->id;
//     $mobile->is_transfer = true;
//     $mobile->save();

    //     // Create the transfer record
//     $transferRecord = new TransferRecord();
//     $transferRecord->from_user_id = $fromUser->id;
//     $transferRecord->to_user_id = $toUser->id;
//     $transferRecord->mobile_id = $mobile->id;
//     $transferRecord->transfer_time = Carbon::now(); // Set the current timestamp
//     // Set other transfer record data if needed
//     $transferRecord->save();

    //     return redirect()->back()->with('success', 'Mobile Trnsfered successfully.');
// }

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





    // public function findTransferId($id)
// {
//     $filterId = Mobile::find($id);
//     // dd($filterId);
//     if (!$filterId) {

    //         return response()->json(['message' => 'Id not found'], 404);
//     }

    //     return response()->json(['result' => $filterId]);

    // }


    public function approve(Request $request)
    {
        $mobile = Mobile::findOrFail($request->id);

        // Check if the authenticated user ID matches the original owner ID
        if (auth()->user()->id === $mobile->original_owner_id) {
            $mobile->is_approve = $request->input('is_approve');
            $mobile->save();

            return redirect()->back()->with('success', 'Mobile has been approved successfully.');
        } else {
            return redirect()->back()->with('danger', 'You cannot approve this mobile.');
        }
    }

    public function approveMobile(Request $request)
    {
        $data = Mobile::findOrFail($request->id);

        // Check if the authenticated user has ID 3
        if (auth()->user()->id === 3) {
            $data->is_approve = $request->input('is_approve');
            $data->save();
            return redirect()->back()->with('success', 'Mobile Approved successfully.');
        } else {
            return redirect()->back()->with('danger', 'You cannot approve this mobile.');
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
        $filterId = Mobile::find($request->id);

        $password = $request->input('password');
        $masterPassword = MasterPassword::first();
        $mPass = $masterPassword->password;

        // Check if the authenticated user ID is 2
        if ($password == $mPass) {
            $filterId->delete();
            return redirect()->back()->with('success', 'Mobile Deleted Successfully');
        } else {
            return redirect()->back()->with('danger', "Incorrect Password.");
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




}
