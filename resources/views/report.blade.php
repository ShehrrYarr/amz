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
        $('#filterButton').click(function () {
            let start = $('#start_date').val();
            let end = $('#end_date').val();
            let availability = $('#availability').val();

            $.ajax({
                url: "{{ route('report.fetch') }}",
                method: "GET",
                data: {
                    start_date: start,
                    end_date: end,
                    availability: availability
                },
                success: function (response) {
                    // Summary
                    $('#reportResults').html(`<h5>${response.summary.label}: <strong>Rs. ${Number(response.summary.value).toLocaleString()}</strong></h5>`);

                    // Clear existing table
                    $('#mobileTable tbody').html('');

                    response.mobiles.forEach(function (mobile) {
                        let customer = '-';
                        if (response.availability === 'Sold') {
                            if (mobile.sold_vendor) {
                                customer = mobile.sold_vendor.name;
                            } else if (mobile.customer_name) {
                                customer = mobile.customer_name;
                            }
                        }

                        const row = `
                        <tr>
                            <td>${mobile.created_at}</td>
                            <td>${mobile.sold_at ?? '-'}</td>
                            <td>${mobile.mobile_name}</td>
                            <td>${mobile.company?.name ?? '-'}</td>
                            <td>${mobile.group?.name ?? '-'}</td>
                            <td>${mobile.vendor?.name ?? '-'}</td>
                            <td>${customer}</td>
                            <td>${mobile.imei_number}</td>
                            <td>${mobile.sim_lock}</td>
                            <td>${mobile.color}</td>
                            <td>${mobile.storage}</td>
                            <td>${mobile.battery_health ?? '-'}</td>
                            <td>Rs. ${Number(mobile.cost_price).toLocaleString()}</td>
                            <td>Rs. ${Number(mobile.selling_price).toLocaleString()}</td>
                            <td>-</td>
                            <td>${mobile.availability}</td>
                        </tr>
                    `;
                        $('#mobileTable tbody').append(row);
                    });

                    // Hide/show columns
                    toggleColumnVisibility(1, response.availability === 'Sold'); // Sold at
                    toggleColumnVisibility(6, response.availability === 'Sold'); // Customer Name
                },
                error: function () {
                    $('#reportResults').html('<div class="alert alert-danger">Error loading report</div>');
                }
            });
        });

        function toggleColumnVisibility(index, show) {
            $('#mobileTable tr').each(function () {
                const cell = $(this).find('td, th').eq(index);
                show ? cell.show() : cell.hide();
            });
        }

    </script>




@endsection