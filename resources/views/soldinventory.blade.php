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
                            <label for="pay_amount" class="form-label">Pay Amount</label>
                            <input type="number" step="0.01" class="form-control" id="pay_amount" name="pay_amount" >
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

<style>
    /* Card polish */
    .latest-update-tracking .card {
        border: 0;
        border-radius: 16px;
        box-shadow: 0 10px 20px rgba(0, 0, 0, .04);
    }

    .latest-update-heading {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #f1f3f5;
    }

    /* Filter bar controls */
    #perPage,
    form[action="{{ url()->current() }}"] .form-control[type="search"] {
        border-radius: 8px;
    }

    /* Table look */
    #soldTable thead th {
        position: sticky;
        top: 0;
        background: #f8fafc;
        z-index: 1;
        font-weight: 600;
    }

    #soldTable td,
    #soldTable th {
        vertical-align: middle;
    }

    #soldTable tbody tr:hover {
        background: #f9fafb;
    }

    #soldTable th:first-child,
    #soldTable td:first-child {
        width: 36px;
        text-align: center;
    }

    #soldTable td {
        white-space: nowrap;
    }

    /* Softer status badges (scoped to this table only) */
    #soldTable .badge-danger {
        background: #fee2e2;
        color: #b91c1c;
        border: 1px solid #fecaca;
    }

    #soldTable .badge-success {
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }

    /* Pagination styling */
    .pagination .page-link {
        border-radius: 8px;
        border: 0;
        box-shadow: 0 1px 0 rgba(0, 0, 0, .05);
    }

    .pagination .page-item.active .page-link {
        background: #12b886;
        /* teal accent */
    }

    /* Checkbox sizing */
    #soldTable input[type="checkbox"] {
        width: 16px;
        height: 16px;
    }

    /* Make the table container edges match the card */
    .table-responsive {
      border-radius: 0 0 16px 16px;
    overflow-x: auto; /* allow horizontal scroll */
    overflow-y: hidden; /* keep vertical tidy */
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

            <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-12 latest-update-tracking mt-1">
                <div class="card">
                    <div class="card-header latest-update-heading d-flex justify-content-between">
                        <h4 class="latest-update-heading-title text-bold-500">Sold Mobiles</h4>
                       

                    </div>
                    <form method="GET" action="{{ url()->current() }}" class="row ml-1 g-2 align-items-center mb-3">
                        <div class="col-auto">
                            <label for="perPage" class="form-label mb-0 small text-muted">Show</label>
                        </div>
                        <div class="col-auto">
                            <select name="per_page" id="perPage" class="form-select form-select-sm" onchange="this.form.submit()">
                                @foreach([10,25,50,100,500,1000,1500,2000,2500,3000] as $n)
                                <option value="{{ $n }}" {{ (int)($perPage ?? 10)===$n ? 'selected' : '' }}>{{ $n }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-auto">
                            <span class="small text-muted">entries</span>
                        </div>
                    
                        <div class="col ms-auto">
                            <input type="search" name="q" value="{{ $search ?? '' }}" class="form-control form-control-sm"
                                placeholder="Search IMEI, mobile, seller, group, customer, price…">
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-sm btn-primary" type="submit">Search</button>
                            <a href="{{ url()->current() }}" class="btn btn-sm btn-outline-secondary">Reset</a>
                        </div>
                    </form>
                    <div class="table-responsive">
                       
                        <table class="table table-hover table-bordered align-middle" id="soldTable">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="select-all"></th>
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
                                    <td>
                                        <input type="checkbox" class="row-checkbox" value="{{ $key->id }}">
                                    </td>
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
                    <div class="d-flex justify-content-between align-items-center mt-1 ml-1">
                        <div class="small text-muted">
                            @if ($mobiles->total() > 0)
                            Showing <strong>{{ $mobiles->firstItem() }}</strong>–<strong>{{ $mobiles->lastItem() }}</strong>
                            of <strong>{{ $mobiles->total() }}</strong> results
                            @else
                            No results found
                            @endif
                        </div>
                    
                        {{-- If your project uses Bootstrap pagination views, keep this line.
                        If not, replace with: {{ $mobiles->onEachSide(1)->links() }} --}}
                        {{ $mobiles->onEachSide(1)->links('pagination::bootstrap-4') }}
                    </div>
                </div>
                <button id="approve-selected" class="btn btn-success mb-2">Approve Selected</button>
            </div>




        </div>
    </div>
</div>
<script>
    

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


    // Select-all functionality
    $('#select-all').on('change', function() {
    $('.row-checkbox').prop('checked', this.checked);
    });
    
    // Uncheck 'select-all' if any single checkbox is unchecked
    $(document).on('change', '.row-checkbox', function() {
    if(!this.checked) {
    $('#select-all').prop('checked', false);
    }
    });
    
    // Approve selected
    $('#approve-selected').on('click', function(e) {
    e.preventDefault();
    let selected = [];
    $('.row-checkbox:checked').each(function() {
    selected.push($(this).val());
    });
    
    if(selected.length === 0) {
    alert('Please select at least one mobile to approve.');
    return;
    }
    
    // Send AJAX POST to approve route
    $.ajax({
    url: '{{ route("approveBulkMobiles") }}',
    type: 'POST',
    data: {
    _token: '{{ csrf_token() }}',
    mobile_ids: selected
    },
    success: function(response) {
    location.reload(); // Or handle as needed
    },
    error: function() {
    alert('There was an error approving the selected mobiles.');
    }
    });
    });
</script>
@endsection