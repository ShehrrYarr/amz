@extends('user_navbar')
@section('content')


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
                        <h4 class="latest-update-heading-title text-bold-500">Sold Transactions</h4>
                        

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
                                    <th>Color</th>
                                    <th>Storage</th>
                                    <th>Battery Health</th>
                                    <th>Cost Price</th>
                                    <th>Selling Price</th>
                                    <th>Customer Name</th>

                                    <th>Mobile History</th>





                                </tr>
                            </thead>
                           <tbody>
                                @foreach ($transactions as $txn)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($txn->transaction_date)->format('Y-m-d / h:i A') }}</td>
                                    <td>{{ $txn->user->name ?? 'N/A' }}</td>
                                    <td>{{ $txn->mobile->mobile_name ?? 'N/A' }}</td>
                                    <td>{{ $txn->mobile->imei_number ?? 'N/A' }}</td>
                                    <td>{{ $txn->mobile->group->name ?? 'N/A' }}</td>
                                    <td>{{ $txn->mobile->sim_lock ?? 'N/A' }}</td>
                                    <td>{{ $txn->mobile->color ?? 'N/A' }}</td>
                                    <td>{{ $txn->mobile->storage ?? 'N/A' }}</td>
                                    <td>{{ $txn->mobile->battery_health ?? 'N/A' }}</td>
                                    <td>{{ $txn->cost_price }}</td>
                                    <td>{{ $txn->selling_price }}</td>
                                    <td>
                                        {{ $txn->customer_name
                                        ?? ($txn->vendor->name ?? 'N/A') }}
                                    </td>
                                    <td>
                                        <a href="{{ route('showHistory', $txn->mobile_id) }}" class="btn btn-sm btn-warning">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </td>
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
                                <h4>Rs </h4>
                            </div>
                            <div class="card-header latest-update-heading d-flex justify-content-between">
                                <h4 class="latest-update-heading-title text-bold-500">Received Sold Profit</h4>
                                <h4>Rs </h4>
                            </div>
                            <div class="card-header latest-update-heading d-flex justify-content-between">
                                <h4 class="latest-update-heading-title text-bold-500">Over All Proftt</h4>
                                <h4>Rs </h4>
                            </div>
                        </div>

                    </div> -->

            <!-- <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-12 latest-update-tracking mt-1">
                        <div class="card">
                            <div class="card-header latest-update-heading d-flex justify-content-between">
                                <h4 class="latest-update-heading-title text-bold-500">Total Cost price of Mobiles</h4>
                                <h4>Rs </h4>
                            </div>
                            <div class="card-header latest-update-heading d-flex justify-content-between">
                                <h4 class="latest-update-heading-title text-bold-500">Total Sold price of Mobiles</h4>
                                <h4>Rs </h4>
                            </div>
                        </div>

                    </div> -->
            <!-- <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-12 latest-update-tracking mt-1">
                        <div class="card">
                            <div class="card-header latest-update-heading d-flex justify-content-between">
                                <h4 class="latest-update-heading-title text-bold-500">Total Cost Price of Received Mobiles</h4>
                                <h4>Rs </h4>
                            </div>
                            <div class="card-header latest-update-heading d-flex justify-content-between">
                                <h4 class="latest-update-heading-title text-bold-500">Total Sold Price of Received Mobiles</h4>
                                <h4>Rs </h4>
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