<?php

namespace App\Http\Controllers;

use App\Models\MasterPassword;
use Illuminate\Http\Request;

class MasterPasswordController extends Controller
{
    public function showPassword()
{
    if (auth()->user()->id == 1) {
        $masterPassword = MasterPassword::first();
        return view('showPassword', compact('masterPassword'));
    } else {
        return redirect()->route('homeRoute')->with('danger', 'You do not have the permission to enter this page.');
    }
}

public function updatePassword(Request $request){
    $currentPassword = MasterPassword::first();
    $currentPassword->password = $request->input('password');
    $currentPassword->updated_at =now();
    $currentPassword->save();
    return redirect()->back()->with('success','Master Password Updated successfully');
}

}
