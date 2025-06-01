<?php

namespace App\Http\Controllers;

use App\Models\Accounts;
use App\Models\Mobile;
use App\Models\vendor;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function showVendors()
    {
        $vendors = Vendor::with('creator')->get(); // eager load the user who added the vendor
        // dd($vendors);
        return view('showVendors', compact('vendors'));
    }


    public function storeVendor(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'office_address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'mobile_no' => 'required|string|max:20',
            'CNIC' => 'nullable|string|max:25',
            'picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $userId = auth()->user()->id;
        // dd($userId);

        if (Vendor::where('name', $validated['name'])->exists()) {
            return redirect()->back()->withInput()->withErrors([
                'name' => 'Vendor with this name already exists.',
            ]);
        }

        if (Vendor::where('mobile_no', $validated['mobile_no'])->exists()) {
            return redirect()->back()->withInput()->withErrors([
                'mobile_no' => 'Vendor with this mobile number already exists.',
            ]);
        }

        if (!empty($validated['CNIC']) && Vendor::where('CNIC', $validated['CNIC'])->exists()) {
            return redirect()->back()->withInput()->withErrors([
                'CNIC' => 'Vendor with this CNIC already exists.',
            ]);
        }

        $filePath = null;
        if ($request->hasFile('picture')) {
            $file = $request->file('picture');
            $filePath = $file->store('vendor_pictures', 'public');
        }

        Vendor::create([
            'name' => $validated['name'],
            'office_address' => $validated['office_address'],
            'city' => $validated['city'],
            'mobile_no' => $validated['mobile_no'],
            'CNIC' => $validated['CNIC'],
            'picture' => $filePath,
            'created_by' => $userId, // 👈 track who added the vendor
        ]);

        return redirect()->back()->with('success', 'Vendor added successfully!');
    }



    public function editVendor($id)
    {
        $filterId = vendor::find($id);
        // dd($filterId);
        if (!$filterId) {

            return response()->json(['message' => 'Id not found'], 404);
        }

        return response()->json(['result' => $filterId]);

    }

    public function updateVendor(Request $request)
    {
        $data = vendor::findOrFail($request->id);

        // Delete old picture if a new one is uploaded
        if ($request->hasFile('picture')) {
            // Check and delete existing picture
            if ($data->picture && Storage::disk('public')->exists($data->picture)) {
                Storage::disk('public')->delete($data->picture);
            }

            // Store new picture
            $path = $request->file('picture')->store('vendor_pictures', 'public');
            $data->picture = $path;
        }

        // Update other vendor data
        $data->name = $request->input('name');
        $data->city = $request->input('city');
        $data->office_address = $request->input('office_address');
        $data->mobile_no = $request->input('mobile_no');
        $data->CNIC = $request->input('CNIC');

        $data->save();

        return redirect()->back()->with('success', 'Vendor updated successfully.');
    }


    public function destroyVendor(Request $request)
    {
        $vendor = Vendor::findOrFail($request->id);

        // Delete picture from storage if it exists
        if ($vendor->picture && Storage::disk('public')->exists($vendor->picture)) {
            Storage::disk('public')->delete($vendor->picture);
        }

        // Delete the vendor record
        $vendor->delete();

        return redirect()->back()->with('success', 'Vendor deleted successfully!');
    }

    public function showVRHistory($id)
    {
        $mobile = Mobile::where('sold_vendor_id', $id)->get();
        return view('showVRHistory', compact('mobile'));
    }

    public function showVSHistory($id)
    {
        $mobile = Mobile::where('vendor_id', $id)->get();
        return view('showVSHistory', compact('mobile'));
    }

    public function getBalance(Request $request)
    {
        $vendorId = $request->vendor_id;

        $credit = Accounts::where('vendor_id', $vendorId)->where('category', 'CR')->sum('amount');
        $debit = Accounts::where('vendor_id', $vendorId)->where('category', 'DB')->sum('amount');

        $balance = $credit - $debit;

        return response()->json([
            'balance' => abs($balance),
            'status' => $balance < 0 ? 'Debit' : ($balance > 0 ? 'Credit' : 'Settled')
        ]);
    }




}
