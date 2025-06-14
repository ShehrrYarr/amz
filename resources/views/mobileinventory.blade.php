@extends('user_navbar')
@section('content')
{{-- Edit Modal --}}

<div class="modal fade" id="exampleModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Mobile</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form" id="editmobile" action="{{ route('updateMobile') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-body">

                        <div class="mb-1">
                            <label for="mobile_name" class="form-label">Mobile Name</label>
                            <input class="form-control" type="hidden" name="id" id="id" value="Update">
                            <input type="text" class="form-control" id="mobile_name" name="mobile_name" required>
                        </div>
                        <div class="mb-1">
                            <label for="company_id" class="form-label">Company</label>
                            <select class="form-control" id="company_id" name="company_id" required>
                                @foreach($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- <div class="mb-1">
                            <label for="vendor_id" class="form-label">Vendor</label>
                            <select class="form-control" id="vendor_id" name="vendor_id" required>
                                @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                @endforeach
                            </select>
                        </div> --}}

                        <div class="mb-1">
                            <label for="group_id" class="form-label">Group</label>
                            <select class="form-control" id="group_id" name="group_id" required>
                                @foreach($groups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-1">
                            <label for="imei_number" class="form-label">IMEI Number</label>
                            <input type="text" class="form-control" id="imei_number" name="imei_number" required>
                        </div>

                        <div class="mb-1" style="display: none">
                            <label for="availability" class="form-label">Availability</label>
                            <select class="form-control" id="availability" name="availability" required>
                                <option value="Available">Available</option>
                                <option value="Sold">Sold</option>
                            </select>
                        </div>



                        <div class="mb-1">
                            <label for="sim_lock" class="form-label">SIM Lock</label>
                            <select class="form-control" id="sim_lock" name="sim_lock" required>
                                <option value="J.V">J.V</option>
                                <option value="PTA">PTA</option>
                                <option value="Non-PTA">Non-PTA</option>
                            </select>
                        </div>
                        <div class="mb-1" style="display: none">
                            <label for="is_approve" class="form-label">SIM Lock</label>
                            <select class="form-control" id="is_approve" name="is_approve">
                                <option value="Approved">Approved</option>
                                <option selected value="Not_Approved">Not_Approved</option>

                            </select>
                        </div>

                        <div class="mb-1">
                            <label for="color" class="form-label">Color</label>
                            <input type="text" class="form-control" id="color" name="color" required>
                        </div>

                        <div class="mb-1">
                            <label for="storage" class="form-label">Storage</label>
                            <input type="text" class="form-control" id="storage" name="storage" required>
                        </div>
                        <div class="mb-1">
                            <label for="battery_health" class="form-label">Battery Health</label>
                            <input type="text" class="form-control" id="battery_health" name="battery_health" required>
                        </div>

                        <div class="mb-1">
                            <label for="cost_price" class="form-label">Cost Price</label>
                            <input type="number" class="form-control" id="cost_price" name="cost_price" required>
                        </div>

                        <div class="mb-1">
                            <label for="selling_price" class="form-label">Selling Price</label>
                            <input type="number" class="form-control" id="selling_price" name="selling_price" required>
                        </div>

                        <div class="mb-1">
                            <label for="password" class="form-label">Edit Password</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn btn-warning mr-1" data-dismiss="modal">
                            <i class="feather icon-x"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-check-square-o"></i> Save
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

{{-- End Edit Modal --}}


{{-- Delete Modal --}}

<div class="modal fade" id="exampleModal4" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Delete this mobile?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form" id="deletemobile" action="{{ route('deleteMobile') }}" method="get"
                    enctype="multipart/form-data">
                    @csrf


                    <div class="form-body">

                        <div class="mb-1">
                            <label for="mobile_name" class="form-label">Mobile Name</label>
                            <input class="form-control" type="hidden" name="id" id="did" value="Update">
                            <input type="text" class="form-control" id="dmobile_name" name="mobile_name" readonly>
                        </div>
                        <div class="mb-1">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>



                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn btn-warning mr-1" data-dismiss="modal">
                            <i class="feather icon-x"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-check-square-o"></i> Yes
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

{{-- End Delete Modal --}}


{{-- Edit For Sold Modal --}}

<div class="modal fade" id="exampleModal3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Sold Mobile</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form" id="soldmobile" action="{{ route('sellMobile') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-body">

                        <div class="mb-1">
                            <label for="mobile_name" class="form-label">Mobile Name</label>
                            <input class="form-control" type="hidden" name="id" id="sid" value="Update">
                            <input type="text" class="form-control" id="smobile_name" name="mobile_name" required
                                readonly>
                        </div>

                        <div class="mb-1" style="display: none">
                            <label for="imei_number" class="form-label">IMEI Number</label>
                            <input type="text" class="form-control" id="simei_number" name="imei_number" required>
                        </div>

                        <div class="mb-1">
                            <label for="availability" class="form-label">Availability</label>
                            <select class="form-control" id="savailability" name="availability" required>
                                <option value="Available">Available</option>
                                <option value="Sold">Sold</option>
                                <option value="Pending">Pending</option>
                            </select>
                        </div>
                        <div class="mb-1" style="display: none;">
                            <label for="group_id" class="form-label">Group</label>
                            <select class="form-control" id="sgroup_id" name="group_id" required>
                                @foreach($groups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-1">
                            <label for="vendor_id" class="form-label">Vendor</label>
                            <select class="form-control" name="vendor_id" id="vendorSelect">
                                <option value="">Select Vendor</option>
                                @foreach ($vendors as $vendor)
                                <option value="{{ $vendor->id }}">{{ $vendor->name }} ({{ $vendor->mobile_no }})
                                </option>
                                @endforeach
                            </select>
                        </div>


                        <div class="mb-1" id="payAmountContainer" style="display: none;">
                            <label for="pay_amount" class="form-label">Pay Amount</label>
                            <input type="number" class="form-control" name="pay_amount" id="pay_amount">
                        </div>


                        <div class="mb-1">
                            <label for="customer_name" class="form-label">Customer Name</label>

                            <input type="text" class="form-control" id="customer_name" name="customer_name">
                        </div>

                        <div class="mb-1" style="display: none">
                            <label for="sim_lock" class="form-label">SIM Lock</label>
                            <select class="form-control" id="ssim_lock" name="sim_lock" required>
                                <option value="J.V">J.V</option>
                                <option value="PTA">PTA</option>
                                <option value="Non-PTA">Non-PTA</option>
                            </select>
                        </div>
                        <div class="mb-1" style="display: none">
                            <label for="is_approve" class="form-label">SIM Lock</label>
                            <select class="form-control" id="sis_approve" name="is_approve">
                                <option value="Approved">Approved</option>
                                <option selected value="Not_Approved">Not_Approved</option>

                            </select>
                        </div>

                        <div class="mb-1" style="display: none">
                            <label for="color" class="form-label">Color</label>
                            <input type="text" class="form-control" id="scolor" name="color" required>
                        </div>

                        <div class="mb-1" style="display: none">
                            <label for="storage" class="form-label">Storage</label>
                            <input type="text" class="form-control" id="sstorage" name="storage" required>
                        </div>
                        <div class="mb-1" style="display: none">
                            <label for="battery_health" class="form-label">Battery Health</label>
                            <input type="text" class="form-control" id="sbattery_health" name="battery_health">
                        </div>

                        <div class="mb-1" style="display: none">
                            <label for="cost_price" class="form-label">Cost Price</label>
                            <input type="number" class="form-control" id="scost_price" name="cost_price" required>
                        </div>

                        <div class="mb-1">
                            <label for="selling_price" class="form-label">Selling Price</label>
                            <input type="number" class="form-control" id="sselling_price" name="selling_price" required>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn btn-warning mr-1" data-dismiss="modal">
                            <i class="feather icon-x"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary" id="soldButton">
                            <i class="fa fa-check-square-o"></i> Save
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

{{-- End Edit For Sold Modal --}}


{{-- Modal --}}
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Mobile</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form" id="storeMobile" action="{{ route('storeMobile') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="form-body">

                        <div class="mb-1">
                            <label for="mobile_name" class="form-label">Mobile Name</label>
                            <input type="text" class="form-control" name="mobile_name" required>
                        </div>
                        <div class="mb-1">
                            <label for="company_id" class="form-label">Company</label>
                            <select class="form-control" name="company_id" required>
                                <option value="">Select Company</option>
                                @foreach ($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-1">
                            <label for="group_id" class="form-label">Group</label>
                            <select class="form-control" name="group_id" required>
                                <option value="">Select Group</option>
                                @foreach ($groups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-1">
                            <label for="vendor_id" class="form-label">Vendor</label>
                            <select class="form-control" name="vendor_id" id="vendorSelect1">
                                <option value="">Select Vendor</option>
                                @foreach ($vendors as $vendor)
                                <option value="{{ $vendor->id }}">{{ $vendor->name }} ({{ $vendor->mobile_no }})
                                </option>
                                @endforeach
                            </select>
                        </div>


                        <div class="mb-1">
                            <label for="imei_number" class="form-label">IMEI Number</label>
                            <input type="text" class="form-control" name="imei_number" required pattern="\d{15}"
                                maxlength="15" minlength="15" title="IMEI number must be exactly 15 digits">
                        </div>


                        <div class="mb-1">
                            <label for="sim_lock" class="form-label">SIM Lock</label>
                            <select class="form-control" name="sim_lock" required>
                                <option value="PTA">PTA</option>
                                <option value="Non-PTA">Non-PTA</option>
                                <option value="J.V">J.V</option>
                            </select>
                        </div>


                        <div class="mb-1">
                            <label for="color" class="form-label">Color</label>
                            <input type="text" class="form-control" name="color" required>
                        </div>

                        <div class="mb-1">
                            <label for="storage" class="form-label">Storage</label>
                            <input type="text" class="form-control" name="storage" required>
                        </div>
                        <div class="mb-1">
                            <label for="battery_health" class="form-label">Battery Health</label>
                            <input type="text" class="form-control" name="battery_health">
                        </div>

                        <div class="mb-1">
                            <label for="cost_price" class="form-label">Cost Price</label>
                            <input type="number" class="form-control" name="cost_price" required>
                        </div>

                        <div class="mb-1">
                            <label for="selling_price" class="form-label">Selling Price</label>
                            <input type="number" class="form-control" name="selling_price" required>
                        </div>
                        <div class="mb-1">
                            <label for="pay_amount" class="form-label">Pay Amount (Optional)</label>
                            <input type="number" class="form-control" name="pay_amount"
                                placeholder="Enter amount paid to vendor">
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn btn-warning mr-1" data-dismiss="modal">
                            <i class="feather icon-x"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary" id="storeButton">
                            <i class="fa fa-check-square-o"></i> Save
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
{{-- End Modal --}}

{{-- Transfer Modal --}}
<div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel2">Transfer Mobile</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form" id="transferMobile" action="{{ route('transferMobile') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="form-body">

                        <div class="mb-1">
                            <label for="mobile_name" class="form-label">Mobile Name</label>
                            <input class="form-control" type="hidden" name="mobile_id" id="tid">

                            <input type="text" class="form-control" id="tmobile_name" name="mobile_name" disabled>
                        </div>



                        <div class="mb-1">
                            <label for="sim_lock" class="form-label">Transfer To</label>
                            <select class="form-control" id="to_user_id" name="to_user_id" required>
                                @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn btn-warning mr-1" data-dismiss="modal">
                            <i class="feather icon-x"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary" id="transferButton">
                            <i class="fa fa-check-square-o"></i> Save
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
{{-- End Transfer Modal --}}

{{-- Download Modal --}}
<div class="modal fade" id="exampleModal5" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel2">Select Dates</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form" action="{{ route('mobiles.export') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="form-body">

                        <div class="mb-1">
                            <label for="start_date" class="form-label">Start Date</label>


                            <input type="date" class="form-control" id="start_date" name="start_date">
                        </div>

                        <div class="mb-1">
                            <label for="end_date" class="form-label">End Date</label>


                            <input type="date" class="form-control" id="end_date" name="end_date">
                        </div>





                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn btn-warning mr-1" data-dismiss="modal">
                            <i class="feather icon-x"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-check-square-o"></i> Download
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
{{-- End Download Modal --}}

<!-- Multiple entries Modal -->

<!-- <div class="modal fade" id="bulkMobileModal" tabindex="-1" role="dialog" aria-labelledby="bulkMobileLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <form id="bulkMobileForm" method="POST" action="{{ route('bulkStoreMobile') }}">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title" id="bulkMobileLabel">Add Multiple Mobiles</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span>&times;</span>
                                    </button>
                                </div>

                                <div class="modal-body">
                                    <div class="form-row">
                                        <div class="col-md-6 mb-2">
                                            <label>Mobile Name</label>
                                            <input type="text" name="mobile_name" class="form-control" required>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label>SIM Lock</label>
                                            <select name="sim_lock" class="form-control" required>
                                                <option value="J.V">J.V</option>
                                                <option value="PTA">PTA</option>
                                                <option value="Non-PTA">Non-PTA</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label>Color</label>
                                            <input type="text" name="color" class="form-control" required>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label>Storage</label>
                                            <input type="text" name="storage" class="form-control" required>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label>Battery Health</label>
                                            <input type="text" name="battery_health" class="form-control">
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label>Cost Price</label>
                                            <input type="number" name="cost_price" class="form-control" required>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label>Selling Price</label>
                                            <input type="number" name="selling_price" class="form-control" required>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label>Company</label>
                                            <select name="company_id" class="form-control" required>
                                                <option value="">Select Company</option>
                                                @foreach($companies as $company)
                                                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label>Group</label>
                                            <select name="group_id" class="form-control" required>
                                                <option value="">Select Group</option>
                                                @foreach($groups as $group)
                                                    <option value="{{ $group->id }}">{{ $group->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label>Vendor</label>
                                            <select name="vendor_id" class="form-control">
                                                <option value="">Select Vendor</option>
                                                @foreach ($vendors as $vendor)
                                                    <option value="{{ $vendor->id }}">{{ $vendor->name }} ({{ $vendor->mobile_no }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label>Number of Mobiles</label>
                                            <input type="number" id="imeiCount" class="form-control" min="1" placeholder="e.g. 5">
                                        </div>
                                    </div>

                                    <div id="imeiFields" class="mt-3"></div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                        <i class="fa fa-times"></i> Cancel
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-save"></i> Save All
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div> -->


<style>
    .gradient-button3 {
        background: linear-gradient(to right, #74a8e0, #1779e2);
        border-color: #007bff;
        color: white;
    }

    .gradient-button4 {
        background: linear-gradient(to right, rgb(224, 116, 116), rgb(226, 23, 23));
        border-color: rgb(255, 0, 0);
        color: white;
    }

    ,
    .select2-container .select2-selection--single {
        height: 38px !important;
        padding: 5px 10px;
    }
</style>


<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
            @if (session('success'))
            <div class="alert alert-success" id="successMessage">
                {{ session('success') }}
            </div>
            @endif

            @if (session('danger'))
            <div class="alert alert-danger" id="dangerMessage" style="color: red;">
                {{ session('danger') }}
            </div>
            @endif

            <button type="button" class="btn btn-primary gradient-button3 ml-1" data-toggle="modal"
                data-target="#exampleModal">
                <i class="feather icon-smartphone" style="font-size: 20px;"></i>
            </button>

            <a href="/multipleentries" type="button" class="btn btn-primary gradient-button4 ml-1">
                <i class="feather icon-copy" style="font-size: 20px;"></i>
            </a>
            <div class="row mb-2 mt-2">
                <div class="col-lg-3 col-md-6 col-sm-12 mb-1">
                    <select id="companyFilter" class="form-control">
                        <option value="">All Companies</option>
                        @foreach ($companies as $company)
                        <option value="{{ $company->id }}">{{ $company->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-12 mb-1">
                    <select id="groupFilter" class="form-control">
                        <option value="">All Groups</option>
                        @foreach ($groups as $group)
                        <option value="{{ $group->id }}">{{ $group->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 mb-1">
                    <button id="filterBtn" class="btn btn-primary w-100">Filter</button>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 mb-1">
                    <button type="button" id="resetFilterBtn" class="btn btn-secondary w-100">Reset</button>
                </div>
            </div>




            <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-12 latest-update-tracking mt-1">
                <div class="card">
                    <div class="card-header latest-update-heading d-flex justify-content-between">
                        <h4 class="latest-update-heading-title text-bold-500">Available Mobiles</h4>

                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered zero-configuration" id="mobileTable">
                            <thead>
                                <tr>
                                    {{-- <th>ID</th> --}}
                                    <th>Added at</th>
                                    <th>Added By</th>
                                    <th>Mobile Name</th>
                                    <th>Company</th>
                                    <th>Group</th>
                                    <th>Vendor</th>
                                    <th>IMEI#</th>
                                    <th>SIM Lock</th>
                                    <th>Color</th>
                                    <th>Storage</th>
                                    <th>Battery Health</th>
                                    <th>Cost Price</th>
                                    <th>Selling Price</th>
                                    <th>Mobile History</th>
                                    <th>Availability</th>
                                    <!-- <th>Transfer</th> -->
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mobile as $key)
                                <tr>
                                    {{-- <td>{{ $key->id }}</td> --}}
                                    {{-- <td>{{ $key->created_at }}</td> --}}
                                    <!--<td>{{ \Carbon\Carbon::parse($key->created_at)->tz('Asia/Karachi')->format('d h:i A, M ,Y') }}</td>-->
                                    <td>{{ \Carbon\Carbon::parse($key->created_at)->format(' Y-m-d / h:i ') }}</td>
                                    <!--<td>{{ \Carbon\Carbon::parse($key->created_at)->diffForHumans() }}</td>-->






                                    <td>{{ $key->creator->name ?? 'N/A' }}</td>

                                    <td>{{ $key->mobile_name }}</td>
                                    <td>{{ $key->company->name ?? 'N/A' }}</td>
                                    <td>{{ $key->group->name ?? 'N/A' }}</td>
                                    <td>{{ $key->latestVendorTransaction->vendor->name ?? 'N/A' }}</td>


                                    <td>{{ $key->imei_number }}</td>
                                    <td>{{ $key->sim_lock }}</td>
                                    <td>{{ $key->color }}</td>
                                    <td>{{ $key->storage }}</td>
                                    <td>{{ $key->battery_health }}</td>
                                    <td>{{ $key->cost_price }}</td>
                                    <td>{{ $key->selling_price }}</td>
                                    <td>
                                        <a href="{{ route('showHistory', $key->id) }}" class="btn btn-sm btn-warning">
                                            <i class="fa fa-eye"></i>

                                        </a>

                                    </td>
                                    <td>
                                        <a href="" onclick="sold({{ $key->id }})" data-toggle="modal"
                                            data-target="#exampleModal3">
                                            <span class="badge badge-success">{{ $key->availability }}</span>

                                        </a>

                                    </td>
                                    <!-- <td><a href="" onclick="transfer({{ $key->id }})" data-toggle="modal"
                                                    data-target="#exampleModal2">
                                                    <i class="fa fa-exchange" style="font-size: 20px"></i></a></td> -->
                                    <td>
                                        <a href="" onclick="edit({{ $key->id }})" data-toggle="modal"
                                            data-target="#exampleModal1">
                                            <i class="feather icon-edit"></i></a> |
                                        <a href="" onclick="deletefn({{ $key->id }})" data-toggle="modal"
                                            data-target="#exampleModal4"><i style="color:red"
                                                class="feather icon-trash"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-12 latest-update-tracking mt-1">
                <div class="card">
                    <div class="card-header latest-update-heading d-flex justify-content-between">
                        <h4 class="latest-update-heading-title text-bold-500">Download The Inventory</h4>
                        <a style="font-size: 25px" href="" data-toggle="modal" data-target="#exampleModal5"><i
                                style="color:red;" class="fa fa-download"></i></a>
                    </div>
                </div>

            </div>

            <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-12 latest-update-tracking mt-1">
                <div class="card">
                    <div class="card-header latest-update-heading d-flex justify-content-between">
                        <h4 class="latest-update-heading-title text-bold-500">Total Credit Cost</h4>
                        <h3>Rs <strong>{{ $totalCostPrice }}</strong></h3>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
<script>
    //Start dataTable

        $(document).ready(function () {
            $('#mobileTable').DataTable({
                order: [
                    [0, 'desc']
                ]
            });
        });
        //End dataTable
        //Disable Mobile Button Function

        $(document).ready(function () {
            $('#storeMobile').on('submit', function () {
                // Change button text to "Saving..."
                $('#storeButton').html('<i class="fa fa-spinner fa-spin"></i> Saving...').prop('disabled', true);
            });
        });

        // End Disable Mobile  Button Function

        //Disable Mobile Sold Button Function

        $(document).ready(function () {
            $('#soldmobile').on('submit', function () {
                // Change button text to "Saving..."
                $('#soldButton').html('<i class="fa fa-spinner fa-spin"></i> Selling...').prop('disabled', true);
            });
        });

        // End Disable Mobile Sold Button Function

        //Disable Mobile Transfer Button Function

        $(document).ready(function () {
            $('#transferMobile').on('submit', function () {
                // Change button text to "Saving..."
                $('#transferButton').html('<i class="fa fa-spinner fa-spin"></i> Selling...').prop('disabled', true);
            });
        });

        // End Disable Mobile Teansfer Button Function

        //  Edit Function
        function edit(value) {
            console.log(value);
            var id = value;
            $.ajax({
                type: "GET",
                url: '/editmobile/' + id,
                success: function (data) {
                    $("#editmobile").trigger("reset");

                    $('#id').val(data.result.id);
                    $('#mobile_name').val(data.result.mobile_name);
                    $('#imei_number').val(data.result.imei_number);
                    $('#sim_lock').val(data.result.sim_lock);
                    $('#color').val(data.result.color);
                    $('#battery_health').val(data.result.battery_health);
                    $('#storage').val(data.result.storage);
                    $('#cost_price').val(data.result.cost_price);
                    $('#availability').val(data.result.availability);
                    $('#selling_price').val(data.result.selling_price);
                    $('#company_id').val(data.result.company_id);
                    $('#vendor_id').val(data.result.vendor_id);
                    $('#group_id').val(data.result.group_id);


                },
                error: function (error) {
                    console.log('Error:', error);
                }
            });
        }

        // End Edit Function

        //  Delete fn Function
        function deletefn(value) {
            console.log(value);
            var id = value;
            $.ajax({
                type: "GET",
                url: '/editmobile/' + id,
                success: function (data) {
                    $("#deletemobile").trigger("reset");

                    $('#did').val(data.result.id);
                    $('#dmobile_name').val(data.result.mobile_name);



                },
                error: function (error) {
                    console.log('Error:', error);
                }
            });
        }

        // End Delete Function



        //  Edit for Sold Function
        function sold(value) {
            console.log(value);
            var id = value;
            $.ajax({
                type: "GET",
                url: '/editmobile/' + id,
                success: function (data) {
                    $("#soldmobile").trigger("reset");

                    $('#sid').val(data.result.id);
                    $('#smobile_name').val(data.result.mobile_name);
                    $('#simei_number').val(data.result.imei_number);
                    $('#ssim_lock').val(data.result.sim_lock);
                    $('#scolor').val(data.result.color);
                    $('#sstorage').val(data.result.storage);
                    $('#sbattery_health').val(data.result.battery_health);
                    $('#scost_price').val(data.result.cost_price);
                    $('#savailability').val(data.result.availability);
                    $('#sgroup_id').val(data.result.group_id);
                    $('#sselling_price').val(data.result.selling_price);


                },
                error: function (error) {
                    console.log('Error:', error);
                }
            });
        }

        // End Edit For Sold Function

        // Transfer Function
        function transfer(value) {
            console.log(value);

            var id = value;
            $.ajax({
                type: "GET",
                url: '/findmobile/' + id,
                success: function (data) {
                    $("#transfermobile").trigger("reset");

                    $('#tid').val(data.result.id);
                    $('#tmobile_name').val(data.result.mobile_name);
                    // console.log(data.result.mobile_name);


                },
                error: function (error) {
                    console.log('Error:', error);
                }
            });
        }
        // End Transfer Function

        // End Sold Function

        //Message Time Out
        setTimeout(function () {
            document.getElementById('successMessage').style.display = 'none';
        }, 5000); // 15 seconds in milliseconds
        //End Message Time Out
        //Message Time Out
        setTimeout(function () {
            document.getElementById('dangerMessage').style.display = 'none';
        }, 5000); // 15 seconds in milliseconds
        //End Message Time Out

        $(document).ready(function () {
            function togglePayAndCustomerFields() {
                const availability = $('#savailability').val();
                const vendorSelected = $('#vendorSelect').val();

                if (availability === 'Pending') {
                    // If pending, hide both fields
                    $('#payAmountContainer').hide();
                    // $('#customer_name').closest('.mb-1').hide();
                } else if (vendorSelected) {
                    // If vendor selected, show Pay Amount and hide Customer Name
                    $('#payAmountContainer').show();
                    $('#customer_name').closest('.mb-1').hide();
                } else {
                    // No vendor selected, show Customer Name and hide Pay Amount
                    $('#payAmountContainer').hide();
                    $('#customer_name').closest('.mb-1').show();
                }
            }

            // Initial run on page load
            togglePayAndCustomerFields();

            // On vendor select change
            $('#vendorSelect').on('change', function () {
                togglePayAndCustomerFields();
            });

            // On availability select change
            $('#savailability').on('change', function () {
                togglePayAndCustomerFields();
            });
        });


        $(document).ready(function () {
            $('#vendorSelect').select2({
                placeholder: "Select a vendor",
                allowClear: true,
                width: '100%' // Optional to make it responsive
            });
        });

        $(document).ready(function () {
            $('#vendorSelect1').select2({
                placeholder: "Select a vendor",
                allowClear: true,
                width: '100%' // Optional to make it responsive
            });
        });

        //function to handle multiple IMEI inputs

        document.addEventListener('DOMContentLoaded', function () {
            const imeiCountInput = document.getElementById('imeiCount');
            const imeiFieldsContainer = document.getElementById('imeiFields');

            imeiCountInput.addEventListener('input', function () {
                const count = parseInt(this.value);
                imeiFieldsContainer.innerHTML = ''; // Clear previous fields

                if (count > 0) {
                    for (let i = 1; i <= count; i++) {
                        const wrapper = document.createElement('div');
                        wrapper.className = 'mb-2';
                        wrapper.innerHTML = `
                                            <label for="imei_${i}">IMEI #${i}</label>
                                            <input type="text" id="imei_${i}" name="imeis[]" class="form-control imei-input"
                                                required pattern="\\d{15}" minlength="15" maxlength="15"
                                                placeholder="Enter 15-digit IMEI or scan">
                                            <small class="text-danger d-none" id="duplicate_${i}">Duplicate IMEI detected!</small>
                                        `;
                        imeiFieldsContainer.appendChild(wrapper);
                    }

                    updateScannedCount();
                    addIMEIListeners();
                }
            });

            function addIMEIListeners() {
                const imeiInputs = document.querySelectorAll('.imei-input');

                imeiInputs.forEach((input, index) => {
                    input.addEventListener('input', function () {
                        // Remove previous error
                        document.getElementById(`duplicate_${index + 1}`).classList.add('d-none');

                        const currentValue = this.value.trim();
                        let duplicate = false;

                        imeiInputs.forEach((other, i) => {
                            if (i !== index && other.value.trim() === currentValue && currentValue !== '') {
                                duplicate = true;
                            }
                        });

                        if (duplicate) {
                            document.getElementById(`duplicate_${index + 1}`).classList.remove('d-none');
                            this.classList.add('is-invalid');
                        } else {
                            this.classList.remove('is-invalid');
                        }

                        updateScannedCount();
                    });

                    input.addEventListener('keypress', function (e) {
                        // If Enter is pressed and length is 15, move to next
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            if (this.value.length === 15 && index + 1 < imeiInputs.length) {
                                imeiInputs[index + 1].focus();
                            }
                        }
                    });

                    // Optional: autofocus first field
                    if (index === 0) input.focus();
                });
            }

            function updateScannedCount() {
                const count = document.querySelectorAll('.imei-input')
                    .filter(input => input.value.trim().length === 15).length;

                let countDisplay = document.getElementById('scannedCount');
                if (!countDisplay) {
                    countDisplay = document.createElement('div');
                    countDisplay.id = 'scannedCount';
                    countDisplay.className = 'alert alert-info mt-2';
                    imeiFieldsContainer.parentNode.insertBefore(countDisplay, imeiFieldsContainer.nextSibling);
                }

                countDisplay.innerHTML = `<strong>${count}</strong> IMEIs scanned`;
            }
        });

        // Polyfill for NodeList.prototype.filter (in older browsers)
        if (!NodeList.prototype.filter) {
            NodeList.prototype.filter = Array.prototype.filter;
        }





        $('#filterBtn').click(function () {
            let companyId = $('#companyFilter').val();
            let groupId = $('#groupFilter').val();

            $.ajax({
                url: '{{ route("filter.mobiles") }}',
                type: 'GET',
                data: {
                    company_id: companyId,
                    group_id: groupId
                },
                success: function (response) {
                    $('#mobileTable tbody').html(response.html);
                },
                error: function () {
                    alert('Something went wrong. Please try again.');
                }
            });
        });


        $('#resetFilterBtn').on('click', function () {
            $('#companyFilter').val('');
            $('#groupFilter').val('');

            $.ajax({
                url: '{{ route("filter.mobiles") }}',
                method: 'GET',
                data: {
                    company_id: '',
                    group_id: ''
                },
                success: function (response) {
                    $('#mobileTable tbody').html(response.html);
                },
                error: function () {
                    alert('Something went wrong while resetting. Please try again.');
                }
            });
        });


</script>
@endsection