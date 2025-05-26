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

                            <div class="mb-1">
                                <label for="vendor_id" class="form-label">Vendor</label>
                                <select class="form-control" id="vendor_id" name="vendor_id" required>
                                    @foreach($vendors as $vendor)
                                        <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                    @endforeach
                                </select>
                            </div>

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
                                <label for="password" class="form-label">Password</label>
                                <input type="text" class="form-control" name="password" required>
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
                                <input type="text" class="form-control" name="password" required>
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

                            <div class="mb-1">
                                <label for="vendor_id" class="form-label">Vendor</label>
                                <select class="form-control" name="vendor_id">
                                    <option value="">Select Vendor</option>
                                    @foreach ($vendors as $vendor)
                                        <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
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
                                <select class="form-control" name="vendor_id" required>
                                    <option value="">Select Vendor</option>
                                    @foreach ($vendors as $vendor)
                                        <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
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
                                    <option value="J.V">J.V</option>
                                    <option value="PTA">PTA</option>
                                    <option value="Non-PTA">Non-PTA</option>
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

    <style>
        .gradient-button3 {
            background: linear-gradient(to right, #74a8e0, #1779e2);
            border-color: #007bff;
            color: white;
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
                                        <th>Transfer</th>
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







                                            <td>{{ $key->mobile_name }}</td>
                                            <td>{{ $key->company->name ?? 'N/A' }}</td>
                                            <td>{{ $key->group->name ?? 'N/A' }}</td>
                                            <td>{{ $key->vendor->name ?? 'N/A' }}</td>


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
                                            <td><a href="" onclick="transfer({{ $key->id }})" data-toggle="modal"
                                                    data-target="#exampleModal2">
                                                    <i class="fa fa-exchange" style="font-size: 20px"></i></a></td>
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
            $('select[name="vendor_id"]').on('change', function () {
                let vendorSelected = $(this).val();

                if (vendorSelected) {
                    // Vendor selected: Hide customer_name and show pay_amount
                    $('#customer_name').closest('.mb-1').hide();
                    $('#payAmountContainer').show();
                } else {
                    // Vendor not selected: Show customer_name and hide pay_amount
                    $('#customer_name').closest('.mb-1').show();
                    $('#payAmountContainer').hide();
                }
            });
        });

    </script>
@endsection