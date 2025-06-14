@extends('user_navbar')
@section('content')
{{-- Approve Modal --}}
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
                <form class="form" id="approvemobile" action="{{ route('approveMobile') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-body">

                        <div class="mb-1">
                            <label for="mobile_name" class="form-label">Mobile Name</label>
                            <input class="form-control" type="hidden" name="id" id="id">
                            <input type="text" class="form-control" id="mobile_name" name="mobile_name" required
                                readonly>
                        </div>

                        <div class="mb-1" style="display: none;">
                            <label for="imei_number" class="form-label">IMEI Number</label>
                            <input type="text" class="form-control" id="imei_number" name="imei_number" required>
                        </div>

                        <div class="mb-1" style="display: none;">
                            <label for="availability" class="form-label">Availability</label>
                            <select class="form-control" id="availability" name="availability" required>
                                <option value="Available">Available</option>
                                <option value="Sold">Sold</option>
                            </select>
                        </div>

                        <div class="mb-1" style="display: none;">
                            <label for="sim_lock" class="form-label">SIM Lock</label>
                            <select class="form-control" id="sim_lock" name="sim_lock" required>
                                <option value="J.V">J.V</option>
                                <option value="PTA">PTA</option>
                                <option value="Non-PTA">Non-PTA</option>
                            </select>
                        </div>

                        <div class="mb-1" style="display: none;">
                            <label for="color" class="form-label">Color</label>
                            <input type="text" class="form-control" id="color" name="color" required>
                        </div>

                        <div class="mb-1" style="display: none;">
                            <label for="storage" class="form-label">Storage</label>
                            <input type="text" class="form-control" id="storage" name="storage" required>
                        </div>
                        <div class="mb-1" style="display: none;">
                            <label for="battery_health" class="form-label">Battery Health</label>
                            <input type="text" class="form-control" id="battery_health" name="battery_health" required>
                        </div>


                        <div class="mb-1" style="display: none;">
                            <label for="cost_price" class="form-label">Cost Price</label>
                            <input type="number" class="form-control" id="cost_price" name="cost_price" required>
                        </div>

                        <div class="mb-1" style="display: none;">
                            <label for="selling_price" class="form-label">Selling Price</label>
                            <input type="number" class="form-control" id="selling_price" name="selling_price" required>
                        </div>
                        <div class="mb-1">
                            <label for="is_approve" class="form-label">Approve Status</label>
                            <select class="form-control" id="is_approve" name="is_approve" required>
                                <option value="Approved">Approve</option>
                                <option value="Not_Approved">Not Approve</option>
                            </select>
                        </div>

                        <div class="mb-1">
                            <label for="password" class="form-label">Approve Password</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn btn-warning mr-1" data-dismiss="modal">
                            <i class="feather icon-x"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary" id="approveButton">
                            <i class="fa fa-check-square-o"></i> Approve
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
{{-- End Approve Modal --}}


{{-- Restore Modal --}}
<div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Restore Mobile</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form" id="restoremobile" action="{{ route('restoreMobile') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-body">

                        <div class="mb-1">
                            <label for="mobile_name" class="form-label">Mobile Name</label>
                            <input class="form-control" type="hidden" name="id" id="rid">
                            <input type="text" class="form-control" id="rmobile_name" name="mobile_name" readonly>
                            <input type="text" class="form-control" id="rimei_number" name="imei_number" hidden>

                        </div>

                        <div class="mb-1" style="display: none">
                            <label for="availability" class="form-label">Availability</label>
                            <select class="form-control" id="ravailability" name="availability" required>
                                <option value="Available">Available</option>
                                <option value="Sold">Sold</option>
                            </select>
                        </div>
                        <div class="mb-1">
                            <label for="group_id" class="form-label">Group</label>
                            <select class="form-control" id="rgroup_id" name="group_id" required>
                                @foreach($groups as $group)
                                <option value="{{ $group->id }}">{{ $group->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-1">
                            <label for="vendor_id" class="form-label">Vendor (Optional)</label>
                            <select class="form-control select2" id="rvendor_id" name="vendor_id" style="width: 100%;">
                                <option value="">-- Select Vendor --</option>
                                @foreach ($vendors as $vendor)
                                <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                @endforeach
                            </select>
                        </div>


                        <div class="mb-1" id="customerNameWrapper">
                            <label for="customer_name" class="form-label">Customer Name</label>

                            <input type="text" class="form-control" id="customer_name" name="customer_name">
                        </div>

                        <div class="mb-1">
                            <label for="battery_health" class="form-label">Battery Health</label>
                            <input type="text" class="form-control" id="rbattery_health" name="battery_health" required>
                        </div>


                        <div class="mb-1">
                            <label for="cost_price" class="form-label">Cost Price</label>
                            <input type="number" class="form-control" id="rcost_price" name="cost_price" required>
                        </div>

                        <div class="mb-1">
                            <label for="selling_price" class="form-label">Selling Price</label>
                            <input type="number" class="form-control" id="rselling_price" name="selling_price" required>
                        </div>

                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn btn-warning mr-1" data-dismiss="modal">
                            <i class="feather icon-x"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary" id="restoreButton">
                            <i class="fa fa-check-square-o"></i> Restore
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
{{-- End Restore Modal --}}

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
                <form class="form" action="{{ route('mobiles.exportSold') }}" method="post"
                    enctype="multipart/form-data">
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

{{-- Owner Transfer Modal --}}
<div class="modal fade" id="exampleModal6" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Transfer to Owner</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form" id="restoremobile" action="{{ route('moveToOwner') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-body">

                        <div class="mb-1">
                            <label for="mobile_name" class="form-label">Mobile Name</label>
                            <input class="form-control" type="hidden" name="id" id="oid">
                            <input type="text" class="form-control" id="omobile_name" name="mobile_name" disabled>
                        </div>



                        <div class="mb-1">
                            <label for="battery_health" class="form-label">Battery Health</label>
                            <input type="text" class="form-control" id="obattery_health" name="battery_health" readonly>
                        </div>


                        <div class="mb-1">
                            <label for="cost_price" class="form-label">Cost Price</label>
                            <input type="number" class="form-control" id="ocost_price" name="cost_price" readonly>
                        </div>

                        <div class="mb-1">
                            <label for="selling_price" class="form-label">Selling Price</label>
                            <input type="number" class="form-control" id="oselling_price" name="selling_price" readonly>
                        </div>

                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn btn-warning mr-1" data-dismiss="modal">
                            <i class="feather icon-x"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-check-square-o"></i> Transfer
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
{{-- End Owner Transfer Modal --}}



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

            <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-12 latest-update-tracking mt-1">
                <div class="card">
                    <div class="card-header latest-update-heading d-flex justify-content-between">
                        <h4 class="latest-update-heading-title text-bold-500">Sold Mobiles</h4>
                        <div>
                            <a href="#" class="btn btn-primary gradient-button3 ml-1" id="exportPDF">Download
                                PDF</a>
                        </div>

                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered zero-configuration" id="soldTable">
                            <thead>
                                <tr>
                                    <th>Sold at</th>
                                    <th>Sold By</th>
                                    <th>Mobile Name</th>
                                    <th>IMEI#</th>
                                    <th>Group</th>
                                    <th>SIM Lock</th>
                                    <th>Approve</th>
                                    <th>Color</th>
                                    <th>Storage</th>
                                    <th>Battery Health</th>
                                    <th>Cost Price</th>
                                    <th>Selling Price</th>
                                    <th>Customer Name</th>

                                    <th>Availability</th>
                                    <th>Mobile History</th>


                                    <th>Restore Purchase</th>



                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mobiles as $key)
                                <tr>
                                    <!--<td>{{ $key->sold_at }}</td>-->
                                    <td>{{ \Carbon\Carbon::parse($key->sold_at)->format(' Y-m-d / h:i ') }}</td>
                                    <td>{{ $key->soldBy->name }}</td>
                                    <td>{{ $key->mobile_name }}</td>
                                    <td>{{ $key->imei_number }}</td>
                                    <td>{{ $key->group->name }}</td>
                                    <td>{{ $key->sim_lock }}</td>
                                    <td><a href="" onclick="approve({{ $key->id }})" data-toggle="modal"
                                            data-target="#exampleModal1">
                                            @if ($key->is_approve == 'Not_Approved')
                                            <span class="badge badge-danger">{{ $key->is_approve }}</span>
                                            @else
                                            <span class="badge badge-success">{{ $key->is_approve }}</span>
                                            @endif
                                        </a></td>
                                    <td>{{ $key->color }}</td>
                                    <td>{{ $key->storage }}</td>
                                    <td>{{ $key->battery_health}}</td>
                                    <td>{{ optional($key->latestSaleTransaction)->cost_price ?? 'N/A' }}</td>
                                    <td>{{ optional($key->latestSaleTransaction)->selling_price ?? 'N/A' }}</td>
                                    <td>{{ optional($key->latestSaleTransaction)->customer_name
                                        ?? optional($key->latestSaleTransaction->vendor)->name
                                        ?? 'N/A' }}</td>
                                    <td>{{ $key->availability }}</td>
                                    <td>
                                        <a href="{{ route('showHistory', $key->id) }}" class="btn btn-sm btn-warning">
                                            <i class="fa fa-eye"></i>

                                        </a>

                                    </td>
                                    <td><a href="" onclick="restore({{ $key->id }})" data-toggle="modal"
                                            data-target="#exampleModal2">
                                            <i class="fa fa-exchange" style="font-size: 20px"></i></a></td>




                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>






            <!-- <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-12 latest-update-tracking mt-1">
                        <div class="card">
                            <div class="card-header latest-update-heading d-flex justify-content-between">
                                <h4 class="latest-update-heading-title text-bold-500">Sold Profit</h4>
                                <h4>Rs {{$totalProfitMobile}}</h4>
                            </div>
                            <div class="card-header latest-update-heading d-flex justify-content-between">
                                <h4 class="latest-update-heading-title text-bold-500">Received Sold Profit</h4>
                                <h4>Rs {{$totalProfitTransfer}}</h4>
                            </div>
                            <div class="card-header latest-update-heading d-flex justify-content-between">
                                <h4 class="latest-update-heading-title text-bold-500">Over All Proftt</h4>
                                <h4>Rs {{$overAllProfit}}</h4>
                            </div>
                        </div>

                    </div> -->

            <!-- <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-12 latest-update-tracking mt-1">
                        <div class="card">
                            <div class="card-header latest-update-heading d-flex justify-content-between">
                                <h4 class="latest-update-heading-title text-bold-500">Total Cost price of Mobiles</h4>
                                <h4>Rs {{$sumCostPriceMobile}}</h4>
                            </div>
                            <div class="card-header latest-update-heading d-flex justify-content-between">
                                <h4 class="latest-update-heading-title text-bold-500">Total Sold price of Mobiles</h4>
                                <h4>Rs {{$sumSellingPriceMobile}}</h4>
                            </div>
                        </div>

                    </div> -->
            <!-- <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-12 latest-update-tracking mt-1">
                        <div class="card">
                            <div class="card-header latest-update-heading d-flex justify-content-between">
                                <h4 class="latest-update-heading-title text-bold-500">Total Cost Price of Received Mobiles</h4>
                                <h4>Rs {{$sumCostPriceTransfer}}</h4>
                            </div>
                            <div class="card-header latest-update-heading d-flex justify-content-between">
                                <h4 class="latest-update-heading-title text-bold-500">Total Sold Price of Received Mobiles</h4>
                                <h4>Rs {{$sumSellingPriceTransfer}}</h4>
                            </div>
                        </div>

                    </div> -->


            <!-- <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-12 latest-update-tracking mt-1">
                        <div class="card">
                            <div class="card-header latest-update-heading d-flex justify-content-between">
                                <h4 class="latest-update-heading-title text-bold-500">Download The Sold Mobiles</h4>
                                <a style="font-size: 25px" href="" data-toggle="modal" data-target="#exampleModal5"><i
                                        style="color:red;" class="fa fa-download"></i></a>
                            </div>
                        </div>

                    </div> -->

        </div>
    </div>
</div>
<script>
    //Genrate PDF

        document.getElementById("exportPDF").addEventListener("click", function () {
            var tableContent = document.getElementById("soldTable").outerHTML;
            var iframe = document.createElement("iframe");
            iframe.style.display = "none";
            document.body.appendChild(iframe);
            iframe.contentDocument.open();
            iframe.contentDocument.write(tableContent);
            iframe.contentDocument.close();

            iframe.contentWindow.print();
            document.body.removeChild(iframe);
        });
        //end Genrate PDF

        //Disable Mobile Approve Button Function

        $(document).ready(function () {
            $('#approvemobile').on('submit', function () {
                // Change button text to "Saving..."
                $('#approveButton').html('<i class="fa fa-spinner fa-spin"></i> Approving...').prop('disabled', true);
            });
        });

        // End Disable Mobile pprove Button Function

        //Disable Mobile Owner Button Function

        $(document).ready(function () {
            $('#restoremobile').on('submit', function () {
                // Change button text to "Saving..."
                $('#restoreButton').html('<i class="fa fa-spinner fa-spin"></i> Restoring...').prop('disabled', true);
            });
        });

        // End Disable Mobile Owner Button Function

        //  approve Function
        function approve(value) {
            console.log(value);
            var id = value;
            $.ajax({
                type: "GET",
                url: '/findapmobile/' + id,
                success: function (data) {
                    $("#approvemobile").trigger("reset");

                    $('#id').val(data.result.id);
                    $('#mobile_name').val(data.result.mobile_name);
                    $('#imei_number').val(data.result.imei_number);
                    $('#sim_lock').val(data.result.sim_lock);
                    $('#color').val(data.result.color);
                    $('#storage').val(data.result.storage);
                    $('#battery_health').val(data.result.battery_health);
                    $('#customer_name').val(data.result.customer_name);
                    $('#cost_price').val(data.result.cost_price);
                    $('#availability').val(data.result.availability);
                    $('#selling_price').val(data.result.selling_price);
                    $('#is_approve').val(data.result.is_approve);




                },
                error: function (error) {
                    console.log('Error:', error);
                }
            });
        }

        // End Edit Function
        //  restore Function
        function restore(value) {
            console.log(value);
            var id = value;
            $.ajax({
                type: "GET",
                url: '/findapmobile/' + id,
                success: function (data) {
                    $("#restoremobile").trigger("reset");

                    $('#rid').val(data.result.id);
                    $('#rmobile_name').val(data.result.mobile_name);
                    $('#rimei_number').val(data.result.imei_number);
                    $('#rbattery_health').val(data.result.battery_health);
                    $('#rcost_price').val(data.result.cost_price);
                    $('#rselling_price').val(data.result.selling_price);
                    $('#ravailability').val(Available);
                    $('#rsold_id').val(data.result.sold_id);
                },
                error: function (error) {
                    console.log('Error:', error);
                }
            });
        }

        // End Restore Function

        //Owner Transfer
        function owner(value) {
            console.log(value);
            var id = value;
            $.ajax({
                type: "GET",
                url: '/findapmobile/' + id,
                success: function (data) {
                    $("#restoremobile").trigger("reset");

                    $('#oid').val(data.result.id);
                    $('#omobile_name').val(data.result.mobile_name);
                    $('#obattery_health').val(data.result.battery_health);
                    $('#ocost_price').val(data.result.cost_price);
                    $('#oselling_price').val(data.result.selling_price);

                },
                error: function (error) {
                    console.log('Error:', error);
                }
            });
        }
        //Owner Transfer End



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
        // Initialize Select2
        $('#rvendor_id').select2({
            placeholder: "-- Select Vendor --",
            allowClear: true,
            width: 'resolve'
        });

        // Initial check on load
        toggleCustomerName();

        // Trigger change event listener
        $('#rvendor_id').on('change', function () {
            toggleCustomerName();
        });

        function toggleCustomerName() {
            const vendorSelected = $('#rvendor_id').val();
            if (vendorSelected) {
                $('#customerNameWrapper').hide();
                $('#customer_name').val('');
            } else {
                $('#customerNameWrapper').show();
            }
        }
    });
</script>
@endsection