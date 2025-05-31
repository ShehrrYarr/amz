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

                <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-12 latest-update-tracking mt-1 ">


                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label>Start Date</label>
                            <input type="date" id="start_date" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>End Date</label>
                            <input type="date" id="end_date" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label>Availability</label>
                            <select id="availability" class="form-control">
                                <option value="">-- Select --</option>
                                <option value="Available">Available</option>
                                <option value="Pending">Pending</option>
                                <option value="Sold">Sold</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label>Company</label>
                            <select id="company_id" class="form-control">
                                <option value="">-- Select Company --</option>
                                @foreach($company as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">

                        <div class="col-md-2">
                            <label>Group</label>
                            <select id="group_id" class="form-control">
                                <option value="">-- Select Group --</option>
                                @foreach($group as $g)
                                    <option value="{{ $g->id }}">{{ $g->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button class="btn btn-primary w-100" id="filterButton">Generate Report</button>
                        </div>
                    </div>

                    <div id="reportResults" class="mt-4">
                        <!-- Report will be loaded here -->
                    </div>
                </div>


                <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-12 latest-update-tracking mt-1">
                    <div class="card">
                        <div class="card-header latest-update-heading d-flex justify-content-between">
                            <h4 class="latest-update-heading-title text-bold-500">Custom Report</h4>

                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered zero-configuration" id="mobileTable">
                                <thead>
                                    <tr>
                                        <th>Added at</th>
                                        <th>Sold at</th>
                                        <th>Mobile Name</th>
                                        <th>Company</th>
                                        <th>Group</th>
                                        <th>Vendor Name</th>
                                        <th>Customer Name</th>
                                        <th>IMEI#</th>
                                        <th>SIM Lock</th>
                                        <th>Color</th>
                                        <th>Storage</th>
                                        <th>Battery Health</th>
                                        <th>Cost Price</th>
                                        <th>Selling Price</th>
                                        <th>Mobile History</th>
                                        <th>Availability</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            const table = $('#mobileTable').DataTable({
                destroy: true,
                ordering: true,
                paging: true,
                searching: true,
                info: true
            });

            $('#filterButton').click(function () {
                let start = $('#start_date').val();
                let end = $('#end_date').val();
                let availability = $('#availability').val();
                let company_id = $('#company_id').val();
                let group_id = $('#group_id').val();

                $.ajax({
                    url: "{{ route('report.fetch') }}",
                    method: "GET",
                    data: {
                        start_date: start,
                        end_date: end,
                        availability: availability,
                        company_id: company_id,
                        group_id: group_id
                    },
                    success: function (response) {
                        // Show summary
                        $('#reportResults').html(`
                        <h5>${response.summary.label}: 
                        <strong>Rs. ${Number(response.summary.value).toLocaleString()}</strong></h5>
                    `);

                        // Clear existing rows in DataTable
                        table.clear();

                        // Append rows
                        response.mobiles.forEach(function (mobile) {
                            let customer = '-';
                            if (response.availability === 'Sold') {
                                customer = mobile.sold_vendor?.name || mobile.customer_name || '-';
                            }

                            table.row.add([
                                mobile.created_at,
                                mobile.sold_at ?? '-',
                                mobile.mobile_name,
                                mobile.company?.name ?? '-',
                                mobile.group?.name ?? '-',
                                mobile.vendor?.name ?? '-',
                                customer,
                                mobile.imei_number,
                                mobile.sim_lock,
                                mobile.color,
                                mobile.storage,
                                mobile.battery_health ?? '-',
                                `Rs. ${Number(mobile.cost_price).toLocaleString()}`,
                                `Rs. ${Number(mobile.selling_price).toLocaleString()}`,
                                '-', // For Mobile History
                                mobile.availability
                            ]);
                        });

                        table.draw();

                        // Show/hide columns
                        toggleColumnVisibility(table, 1, response.availability === 'Sold'); // Sold at
                        toggleColumnVisibility(table, 6, response.availability === 'Sold'); // Customer Name
                    },
                    error: function () {
                        $('#reportResults').html('<div class="alert alert-danger">Error loading report</div>');
                    }
                });
            });

            function toggleColumnVisibility(table, columnIndex, visible) {
                table.column(columnIndex).visible(visible);
            }
        });



    </script>




@endsection