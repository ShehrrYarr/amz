<?php

use App\Http\Controllers\AccountsController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\MasterPasswordController;
use App\Http\Controllers\MobileController;
use App\Http\Controllers\MobileHistoryController;
use App\Http\Controllers\VendorController;
use App\Models\TransferRecord;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Publication;
use App\Models\Mobile;
use App\Models\company;
use App\Models\group;
use App\Models\vendor;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. Theseso
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes();

Route::get('/', function () {
    $totalPublications = Publication::get()->count();
    $totalUsers = User::get();
    return view('home', compact('totalUsers', 'totalPublications'));

});



Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/publication', [App\Http\Controllers\PublicationController::class, 'index'])->name('publication');
Route::post('/storepublication', [App\Http\Controllers\PublicationController::class, 'store'])->name('storepublication');
Route::get('/editpublication/{id}', [App\Http\Controllers\PublicationController::class, 'edit'])->name('editpublication');
Route::get('/managepublication', [App\Http\Controllers\PublicationController::class, 'show'])->name('managepublication');
Route::post('/updatepublication', [App\Http\Controllers\PublicationController::class, 'update'])->name('updatepublication');

Route::get('/downloadpublication/{publication}', [App\Http\Controllers\PublicationController::class, 'downloadPublication'])->name('downloadpublication');
Route::get('/adminthread', [App\Http\Controllers\AdminThreadController::class, 'index'])->name('adminthread');
Route::get('/fetchthread/{user_id}', [App\Http\Controllers\AdminThreadController::class, 'fetchThread'])->name('fetchthread');
Route::get('/publicationallocation', [App\Http\Controllers\PublicationController::class, 'publicationAllocation'])->name('publicationallocation');
Route::get('/unlinkpublication/{pivot_id}', [App\Http\Controllers\PublicationController::class, 'unlinkPublication'])->name('unlinkpublication');
Route::get('/verifyallocation/{pubid}/{userid}', [App\Http\Controllers\PublicationController::class, 'verifyAllocation'])->name('verifyallocation');
Route::post('/storeallocation', [App\Http\Controllers\PublicationController::class, 'storeAllocation'])->name('storeallocation');
//user access
Route::get('/publicationdetails/{pub_id}', [App\Http\Controllers\PublicationController::class, 'publicationDetails'])->name('publicationdetails');
Route::get('/userthread', [App\Http\Controllers\UserThreadController::class, 'index'])->name('userthread');
Route::get('/sendmessage/{message}/{chat_id}', [App\Http\Controllers\UserThreadController::class, 'store'])->name('sendmessage');
Route::get('/userpublications', [App\Http\Controllers\PublicationController::class, 'index'])->name('userpublications');
Route::post('/logout', [App\Http\Controllers\HomeController::class, 'logout'])->name('logout');
//media routes
Route::get('/createmedia', [App\Http\Controllers\MediaController::class, 'index'])->name('createmedia');
Route::post('/storemedia', [App\Http\Controllers\MediaController::class, 'store'])->name('storemedia');
Route::get('/editmedia/{id}', [App\Http\Controllers\MediaController::class, 'edit'])->name('editmedia');
Route::post('/updatemedia', [App\Http\Controllers\MediaController::class, 'update'])->name('updatecategory');
Route::get('/deletemedia/{id}', [App\Http\Controllers\MediaController::class, 'destroy'])->name('deletemedia');
//Media Category Routes
Route::get('/mediacategory', [App\Http\Controllers\MediaCategoryController::class, 'index'])->name('mediacategory');
Route::post('/storecategory', [App\Http\Controllers\MediaCategoryController::class, 'store'])->name('storecategory');
Route::get('/editcategory/{id}', [App\Http\Controllers\MediaCategoryController::class, 'edit'])->name('editCategory');
Route::post('/update', [App\Http\Controllers\MediaCategoryController::class, 'update'])->name('updatecategory');
Route::get('/deletecategory/{id}', [App\Http\Controllers\MediaCategoryController::class, 'destroy'])->name('deletecategory');
Route::put('/approve', [App\Http\Controllers\MobileController::class, 'approve'])->name('approve');
Route::put('/movetoowner', [App\Http\Controllers\MobileController::class, 'moveToOwner'])->name('moveToOwner');
Route::put('/approveMobile', [App\Http\Controllers\MobileController::class, 'approveMobile'])->name('approveMobile');
Route::put('/pendingrestore', [App\Http\Controllers\MobileController::class, 'pendingRestore'])->name('pendingRestore');
Route::put('/receivedpendingrestore', [App\Http\Controllers\MobileController::class, 'receivedPendingRestore'])->name('receivedPendingRestore');



// Manage Mobile Inventory
// Route::get('/manageinventory', function () {
//     $users=User::all();
//     $mobile = Mobile::where('user_id', auth()->user()->id)->where('availability', 'Available')->get();
//     return view('mobileinventory',compact('mobile','users'));
// });
// Route::get('/manageinventory', function () {
//     $users = User::all();
//     $totalCostPrice = DB::table('mobiles')->sum('cost_price');
//     $mobile = Mobile::where('user_id', auth()->user()->id)
//         ->where('availability', 'Available')
//         ->where('is_transfer', false) // Add the condition for is_transfer
//         ->get();
//     return view('mobileinventory', compact('mobile', 'users','totalCostPrice'));
// })->middleware('auth');

Route::get('/index', [App\Http\Controllers\UserController::class, 'index'])->name('user.index')->middleware('auth');


Route::get('/manageinventory', function () {

    $users = User::all();
    $companies = Company::all();
    $groups = Group::all();
    $vendors = Vendor::all();
    $totalCostPrice = DB::table('mobiles')
        ->where('user_id', auth()->user()->id)
        ->where('availability', 'Available')
        ->where('is_transfer', false)
        ->sum('cost_price');

    $mobile = Mobile::where('availability', 'Available')
        ->where('is_transfer', false)->with(['group', 'company', 'vendor','creator'])
        ->get();
    // dd($mobile);



    return view('mobileinventory', compact('mobile', 'users', 'totalCostPrice', 'companies', 'groups', 'vendors'));
})->middleware('auth')->name('homeRoute');





Route::get('/managerecentinventory', function () {
    $users = User::all();
    $fifteenDaysAgo = Carbon::now()->subDays(20);

    $totalCostPrice = DB::table('mobiles')
        ->where('user_id', auth()->user()->id)
        ->where('availability', 'Available')
        ->where('is_transfer', false)
        ->sum('cost_price');

    $mobile = Mobile::where('user_id', auth()->user()->id)
        // ->where('availability', ['Available', 'Sold'])
        ->where('is_transfer', false)
        ->where('created_at', '>=', $fifteenDaysAgo)
        ->get();

    $transferMobiles = TransferRecord::with('fromUser', 'toUser', 'mobile')
        ->where('from_user_id', Auth::id())
        ->where('created_at', '>=', $fifteenDaysAgo)
        ->get();

    return view('managerecentinventory', compact('mobile', 'users', 'totalCostPrice', 'transferMobiles'));
})->middleware('auth');



Route::get('/soldinventory', function () {
    $mobile = Mobile::where('availability', 'Sold')
        ->where('is_transfer', false)
        ->where('is_approve', 'Not_Approved')->with('soldBy')
        ->get();

        // dd($mobile);

    // Calculate the sum of the profit for the $mobile collection
    $totalProfitMobile = $mobile->sum(function ($mobile) {
        return $mobile->selling_price - $mobile->cost_price;
    });

    // Calculate the sum of the cost_price for the $mobile collection
    $sumCostPriceMobile = $mobile->sum('cost_price');

    // Calculate the sum of the selling_price for the $mobile collection
    $sumSellingPriceMobile = $mobile->sum('selling_price');

    $transferMobiles = TransferRecord::with('fromUser', 'toUser', 'mobile')
        ->whereIn('id', function ($query) {
            $query->select(\DB::raw('MAX(id)'))
                ->from('transfer_records')
                ->groupBy('mobile_id');
        })
        ->where('to_user_id', Auth::id())
        ->whereHas('mobile', function ($query) {
            $query->where('user_id', Auth::id())
                ->where('availability', 'Sold')
                ->where('is_approve', 'Not_Approved');
        })
        ->whereHas('mobile', function ($query) {
            $query->where('is_transfer', true);
        })
        ->get();

    // Calculate the sum of the profit for the $transferMobiles collection
    $totalProfitTransfer = $transferMobiles->sum(function ($transferMobile) {
        return $transferMobile->mobile->selling_price - $transferMobile->mobile->cost_price;
    });

    // Calculate the sum of the selling_price for the $transferMobiles collection
    $sumSellingPriceTransfer = $transferMobiles->sum('mobile.selling_price');

    // Calculate the sum of the cost_price for the $transferMobiles collection
    $sumCostPriceTransfer = $transferMobiles->sum('mobile.cost_price');

    // Calculate the overall profit
    $overAllProfit = $totalProfitMobile + $totalProfitTransfer;

    return view('soldinventory', compact('mobile', 'transferMobiles', 'totalProfitMobile', 'totalProfitTransfer', 'sumCostPriceMobile', 'sumSellingPriceTransfer', 'sumCostPriceTransfer', 'overAllProfit', 'sumSellingPriceMobile'));
})->middleware('auth');

Route::get('/soldapprovedinventory', function () {

    $mobile = Mobile::where('user_id', auth()->user()->id)->where('availability', 'Sold')->where('is_transfer', false)
        ->where('is_approve', 'Approved')
        ->get();

    $startOfWeek = Carbon::now()->startOfWeek(Carbon::FRIDAY);
    $endOfWeek = Carbon::now()->endOfWeek(Carbon::FRIDAY);

    $profit = Mobile::where('user_id', auth()->user()->id)
        ->where('availability', 'Sold')
        ->where('is_transfer', false)
        ->where('is_approve', 'Approved')
        ->whereBetween('sold_at', [$startOfWeek, $endOfWeek])
        ->sum('selling_price') - Mobile::where('user_id', auth()->user()->id)
            ->where('availability', 'Sold')
            ->where('is_transfer', false)
            ->where('is_approve', 'Approved')
            ->whereBetween('sold_at', [$startOfWeek, $endOfWeek])
            ->sum('cost_price');
    return view('soldapprovedinventory', compact('mobile', 'profit'));
})->middleware('auth');


Route::get('/pendinginventory', function () {

    $mobile = Mobile::where('availability', 'Pending')->where('is_transfer', false)
        ->where('is_approve', 'Not_Approved')->with('pendingBy')
        ->get();
        // dd($mobile);
    return view('pendinginventory', compact('mobile'));
})->middleware('auth');

Route::get('/receivedpendinginventory', function () {

    $mobile = Mobile::where('user_id', auth()->user()->id)->where('availability', 'Pending')->where('is_transfer', true)
        ->where('is_approve', 'Not_Approved')
        ->get();
    return view('receivedpendinginventory', compact('mobile'));
})->middleware('auth');

Route::post('/storemobile', [App\Http\Controllers\MobileController::class, 'storeMobile'])->name('storeMobile');
Route::get('/multipleentries', [App\Http\Controllers\MobileController::class, 'multipleEntries'])->name('multipleEntries');
Route::get('/editmobile/{id}', [App\Http\Controllers\MobileController::class, 'editMobile'])->name('editMobile');
Route::put('/updatemobile', [App\Http\Controllers\MobileController::class, 'updateMobile'])->name('updateMobile');
Route::put('/restoremobile', [App\Http\Controllers\MobileController::class, 'restoreMobile'])->name('restoreMobile');
Route::put('/sellmobile', [App\Http\Controllers\MobileController::class, 'sellMobile'])->name('sellMobile');
Route::get('/findmobile/{id}', [App\Http\Controllers\MobileController::class, 'findMobile'])->name('findMobile');
Route::post('/transfermobile', [App\Http\Controllers\MobileController::class, 'transferMobile'])->name('transferMobile');
Route::post('/moveToInventory', [App\Http\Controllers\MobileController::class, 'moveToInventory'])->name('moveToInventory');
Route::get('/findapmobile/{id}', [App\Http\Controllers\MobileController::class, 'findApMobile'])->name('findApMobile');
Route::get('/deletemobile', [App\Http\Controllers\MobileController::class, 'destroy'])->name('deleteMobile');

Route::get('/restoremobiles', [App\Http\Controllers\RestoreController::class, 'restoreMobiles'])->name('restoremobiles');

Route::post('/check-imei', [MobileController::class, 'checkIMEI'])->name('checkIMEI');
Route::post('/store-multiple-mobiles', [MobileController::class, 'storeMultipleMobiles'])->name('storeMultipleMobiles');




// Transfer Inventory


Route::get('/transferedinventory', function () {
    $users = User::all();

    $transferMobiles = TransferRecord::with('fromUser', 'toUser', 'mobile')
        ->whereIn('id', function ($query) {
            $query->select(\DB::raw('MAX(id)'))
                ->from('transfer_records')
                ->groupBy('mobile_id');
        })
        ->where('to_user_id', Auth::id())
        ->whereHas('mobile', function ($query) {
            $query->where('user_id', Auth::id())
                ->where('availability', 'Available');
        })
        ->whereHas('mobile', function ($query) {
            $query->where('is_transfer', true);
        })
        ->get();

    return view('transferedinventory', compact('transferMobiles', 'users'));
})->middleware('auth');



Route::get('/recenttransferedinventory', function () {
    $users = User::all();
    $fifteenDaysAgo = Carbon::now()->subDays(17);

    $transferMobiles = TransferRecord::with('fromUser', 'toUser', 'mobile')
        ->whereIn('id', function ($query) {
            $query->select(\DB::raw('MAX(id)'))
                ->from('transfer_records')
                ->groupBy('mobile_id');
        })
        ->where('to_user_id', Auth::id())
        ->whereHas('mobile', function ($query) {
            $query->where('user_id', Auth::id())
                ->where('availability', 'Available');
        })
        ->whereHas('mobile', function ($query) {
            $query->where('is_transfer', true);
        })
        ->where('created_at', '>=', $fifteenDaysAgo)
        ->get();

    $recentReceivedClicked = session('recentReceivedClicked', false);

    return view('recenttransferedinventory', compact('transferMobiles', 'users', 'recentReceivedClicked'));
})->middleware('auth')->name('recentTransferInventory');



Route::get('/receivedtoday', function () {
    $users = User::all();
    $today = Carbon::today();

    $transferMobiles = TransferRecord::with('fromUser', 'toUser', 'mobile')
        ->whereIn('id', function ($query) {
            $query->select(\DB::raw('MAX(id)'))
                ->from('transfer_records')
                ->groupBy('mobile_id');
        })
        ->where('to_user_id', Auth::id())
        ->whereHas('mobile', function ($query) {
            $query->where('user_id', Auth::id())
                ->where('availability', 'Available');
        })
        ->whereHas('mobile', function ($query) {
            $query->where('is_transfer', true);
        })
        ->whereDate('transfer_time', '>=', $today)
        ->get();

    $recentReceivedClicked = session('recentReceivedClicked', false);

    return view('receivedtoday', compact('transferMobiles', 'users', 'recentReceivedClicked'));
})->middleware('auth')->name('receivedtoday');



Route::get('/transferinventory', function () {
    $users = User::all();
    $transferMobiles = TransferRecord::with('fromUser', 'toUser', 'mobile')
        ->where('from_user_id', Auth::id())->get();
    // dd($transferMobiles);
    return view('transferinventory', compact('transferMobiles', 'users'));
})->middleware('auth');





Route::get('/soldtransferinventory', function () {
    $users = User::all();

    $transferMobiles = TransferRecord::with('fromUser', 'toUser', 'mobile')
        ->whereIn('id', function ($query) {
            $query->select(\DB::raw('MAX(id)'))
                ->from('transfer_records')
                ->groupBy('mobile_id');
        })
        ->where('to_user_id', Auth::id())
        ->whereHas('mobile', function ($query) {
            $query->where('user_id', Auth::id())
                ->where('availability', 'Sold')
                ->where('is_approve', 'Not_Approved');
        })
        ->whereHas('mobile', function ($query) {
            $query->where('is_transfer', true);
        })
        ->get();

    $transferMobiles->each(function ($transferMobile) {
        $transferMobile->profit = $transferMobile->mobile->selling_price - $transferMobile->mobile->cost_price;
    });

    $totalProfit = $transferMobiles->sum('profit');
    $totalCostPrice = $transferMobiles->sum(function ($transferMobile) {
        return $transferMobile->mobile->cost_price;
    });

    return view('soldtransferinventory', compact('transferMobiles', 'users', 'totalProfit', 'totalCostPrice'));
})->middleware('auth');


// Sold Approve

Route::get('/soldapprovetransferinventory', function () {
    $users = User::all();
    $transferMobiles = TransferRecord::with('fromUser', 'toUser', 'mobile')
        ->whereIn('id', function ($query) {
            $query->select(\DB::raw('MAX(id)'))
                ->from('transfer_records')
                ->groupBy('mobile_id');
        })
        ->where('to_user_id', Auth::id())
        ->whereHas('mobile', function ($query) {
            $query->where('user_id', Auth::id())
                ->where('availability', 'Sold')
                ->where('is_approve', 'Approved'); // Add this condition
        })
        ->whereHas('mobile', function ($query) {
            $query->where('is_transfer', true);
        })
        ->get();
    return view('soldapprovetransferinventory', compact('transferMobiles', 'users'));
})->middleware('auth');




Route::get('/totalinventory', function () {
    $users = User::all();


    // Fetch available mobile devices owned by the authenticated user
    $result = Mobile::where('original_owner_id', Auth::id())
        ->get();


    return view('totalinventory', compact('result', 'users'));
})->middleware('auth');



Route::get('/allinventory', function () {
    $users = User::all();
    $mobile = Mobile::where('user_id', auth()->user()->id)
        ->where('is_transfer', false)
        ->get();

    $transferMobiles = TransferRecord::with('fromUser', 'toUser', 'mobile')
        ->whereIn('id', function ($query) {
            $query->select(\DB::raw('MAX(id)'))
                ->from('transfer_records')
                ->groupBy('mobile_id');
        })
        ->where('to_user_id', Auth::id())
        ->whereHas('mobile', function ($query) {
            $query->where('user_id', Auth::id())
                ->where('availability', 'Available');
        })
        ->whereHas('mobile', function ($query) {
            $query->where('is_transfer', true);
        })
        ->get();

    $result = $mobile->concat($transferMobiles);
    return view('allinventory', compact('result', 'users'));
})->middleware('auth');


Route::get('/otherinventory/{id}', [App\Http\Controllers\MobileController::class, 'otherInventory'])->name('otherInventory');
Route::get('/othertotalinventory/{id}', [App\Http\Controllers\MobileController::class, 'otherTotalInventory'])->name('otherTotalInventory');

Route::get('/othersoldinventory/{id}', [App\Http\Controllers\MobileController::class, 'otherSoldInventory'])->name('othersoldinventory');
Route::get('/otherpendinginventory/{id}', [App\Http\Controllers\MobileController::class, 'otherPendingInventory'])->name('otherpendinginventory');
Route::get('/othertransferinventory/{id}', [App\Http\Controllers\MobileController::class, 'otherTransferInventory'])->name('otherTransferInventory');
Route::get('/othertransfersoldinventory/{id}', [App\Http\Controllers\MobileController::class, 'otherTransferSoldInventory'])->name('otherTransferSoldInventory');
Route::post('/mobiles/export', 'App\Http\Controllers\MobileController@exportMobiles')
    ->name('mobiles.export');
Route::post('/mobiles/export-sold', 'App\Http\Controllers\MobileController@exportSoldMobiles')
    ->name('mobiles.exportSold');





//vendor routes
Route::get('/showvendors', [App\Http\Controllers\VendorController::class, 'showVendors'])->name('showvendors');
Route::post('/vendors/store', [VendorController::class, 'storeVendor'])->name('storeVendor');
Route::get('/editvendor/{id}', [App\Http\Controllers\VendorController::class, 'editVendor'])->name('editvendor');
Route::put('/updatevendor', [VendorController::class, 'updateVendor'])->name('updateVendor');
Route::post('/deletevendor', [VendorController::class, 'destroyVendor'])->name('destroyVendor');
Route::get('/showvrHistory/{id}', [VendorController::class, 'showVRHistory'])->name('showVRHistory');
Route::get('/showvsHistory/{id}', [VendorController::class, 'showVSHistory'])->name('showVSHistory');
Route::get('/vendor-balance/{id}', [VendorController::class, 'getBalance'])->name('vendor.balance');
Route::get('/vendor-balance', [VendorController::class, 'getBalance'])->name('getVendorBalance');





//company routes
Route::get('/showcompanies', [App\Http\Controllers\CompanyController::class, 'showCompanies'])->name('showcompanies');
Route::post('/company/store', [CompanyController::class, 'storeCompany'])->name('storeCompany');
Route::get('/editcompany/{id}', [App\Http\Controllers\CompanyController::class, 'editCompany'])->name('editcompany');
Route::put('/updatecompany', [CompanyController::class, 'updateCompany'])->name('updateCompany');
Route::post('/deletecompany', [CompanyController::class, 'destroyCompany'])->name('destroyCompany');

//group routes
Route::get('/showgroups', [App\Http\Controllers\GroupController::class, 'showGroups'])->name('showgroups');
Route::post('/group/store', [GroupController::class, 'storeGroup'])->name('storeGroup');
Route::get('/editgroup/{id}', [App\Http\Controllers\GroupController::class, 'editGroup'])->name('editGroup');
Route::put('/updategroup', [GroupController::class, 'updateGroup'])->name('updateGroup');
Route::post('/deletegroup', [GroupController::class, 'destroyGroup'])->name('destroyGroup');

//password routes
Route::get('/showpassword', [App\Http\Controllers\MasterPasswordController::class, 'showPassword'])->name('showpassword');
Route::post('/password/update', [MasterPasswordController::class, 'updatePassword'])->name('updatePassword');


//mobileHistory Routes
Route::get('/history/{id}', [MobileHistoryController::class, 'showHistory'])->name('showHistory');

//Search filter Routes
Route::get('/searchfilter', [MobileController::class, 'searchFilter'])->name('searchfilter');
Route::get('/search-mobiles', [MobileController::class, 'apiSearchMobiles'])->name('api.searchMobiles');

//Accounts Routes
Route::get('/accounts/{id}', [AccountsController::class, 'showAccounts'])->name('showAccounts');
Route::post('/credit', [AccountsController::class, 'creditAmount'])->name('creditAmount');
Route::post('/debit', [AccountsController::class, 'debitAmount'])->name('debitAmount');

//Bulk entry routes
Route::post('/mobiles/bulk-store', [MobileController::class, 'bulkStoreMobile'])->name('bulkStoreMobile');

//Report Routes
Route::get('/report/fetch', [MobileController::class, 'fetch'])->name('report.fetch');
Route::get('/report', function () {
    $company = company::all ();
    $group = group::all ();
    return view('report',compact('company','group')); })->middleware('auth');
