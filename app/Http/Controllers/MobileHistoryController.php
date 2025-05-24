<?php

namespace App\Http\Controllers;

use App\Models\Mobile;
use App\Models\MobileHistory;
use Illuminate\Http\Request;

class MobileHistoryController extends Controller
{
    public function __construct()
     {
         $this->middleware('auth');
     }
   public function showHistory($id){
    $history = MobileHistory::where('mobile_id',$id)->get();
    $mobile =Mobile::where('id',$id)->first();
    $mobileName = $mobile->mobile_name;
   
    return view('mobileHistory',compact('history','mobileName'));
   }
}
