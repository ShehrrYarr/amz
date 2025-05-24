<?php

namespace App\Http\Controllers;

use App\Models\Restore;
use Illuminate\Http\Request;

class RestoreController extends Controller
{
     public function __construct()
     {
         $this->middleware('auth');
     }
    public function restoreMobiles(){
        $restoreMobiles = Restore::all();

        return view('restoremobile',compact('restoreMobiles'));
    }
}
