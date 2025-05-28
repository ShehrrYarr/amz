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
                    <div class="card ">
                        <div class="card-header latest-update-heading d-flex justify-content-between">
                            <h4 class="latest-update-heading-title text-bold-500">Multiple Entries</h4>

                        </div>

                        <div class="container">
                            <h3>Add Multiple Mobiles</h3>

                            <form id="multiMobileForm">
                                @csrf

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label>Vendor</label>
                                        <select id="vendor_id" name="vendor_id" class="form-control" required>
                                            <option value="">Select Vendor</option>
                                            @foreach($vendors as $vendor)
                                                <option value="{{ $vendor->id }}">{{ $vendor->name }} ({{ $vendor->mobile_no }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="card p-3">
                                    <h5>Add Mobile Entry</h5>
                                    <div class="row">
                                        <div class="col-md-3"><input type="text" class="form-control"
                                                placeholder="Mobile Name" id="mobile_name"></div>
                                        <div class="col-md-3"><input type="text" class="form-control" placeholder="IMEI"
                                                id="imei_number" maxlength="15"></div>
                                        <div class="col-md-2">
                                            <select class="form-control" id="sim_lock">
                                                <option value="">SIM Lock</option>
                                                <option value="J.V">J.V</option>
                                                <option value="PTA">PTA</option>
                                                <option value="Non-PTA">Non-PTA</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2"><input type="text" class="form-control" placeholder="Color"
                                                id="color"></div>
                                        <div class="col-md-2"><input type="text" class="form-control" placeholder="Storage"
                                                id="storage"></div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-2"><input type="text" class="form-control"
                                                placeholder="Battery Health" id="battery_health"></div>
                                        <div class="col-md-2"><input type="number" class="form-control"
                                                placeholder="Cost Price" id="cost_price"></div>
                                        <div class="col-md-2"><input type="number" class="form-control"
                                                placeholder="Selling Price" id="selling_price"></div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-success w-100" id="addMobileBtn">Add
                                                Mobile</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive mt-4">
                                    <table class="table table-bordered" id="previewTable">
                                        <thead>
                                            <tr>
                                                <th>Mobile Name</th>
                                                <th>IMEI</th>
                                                <th>SIM Lock</th>
                                                <th>Color</th>
                                                <th>Storage</th>
                                                <th>Battery</th>
                                                <th>Cost</th>
                                                <th>Sell</th>
                                                <th>Profit</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>

                                <div class="mt-3">
                                    <strong>Total Cost: </strong><span id="totalCost">0</span> |
                                    <strong>Total Sell: </strong><span id="totalSell">0</span> |
                                    <strong>Profit: </strong><span id="totalProfit">0</span>
                                </div>

                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary">Submit All</button>
                                </div>
                            </form>
                        </div>



                        <script>
                            let previewData = [];

                            $('#vendor_id').on('change', function () {
                                if ($(this).val()) {
                                    $(this).prop('disabled', true);
                                }
                            });

                            $('#addMobileBtn').click(function () {
                                const imei = $('#imei_number').val().trim();

                                if (!imei || imei.length !== 15) {
                                    alert('IMEI must be 15 digits');
                                    return;
                                }

                                // Check for duplicate in previewData
                                if (previewData.find(m => m.imei_number === imei)) {
                                    alert('This IMEI already added in the list.');
                                    return;
                                }

                                // AJAX check in DB
                                $.ajax({
                                    url: '{{ route("checkIMEI") }}',
                                    method: 'POST',
                                    data: {
                                        imei: imei,
                                        _token: '{{ csrf_token() }}'
                                    },
                                    success: function (res) {
                                        if (res.exists) {
                                            alert('This IMEI already exists in the database.');
                                            return;
                                        }

                                        const mobile = {
                                            mobile_name: $('#mobile_name').val(),
                                            imei_number: imei,
                                            sim_lock: $('#sim_lock').val(),
                                            color: $('#color').val(),
                                            storage: $('#storage').val(),
                                            battery_health: $('#battery_health').val(),
                                            cost_price: parseFloat($('#cost_price').val()) || 0,
                                            selling_price: parseFloat($('#selling_price').val()) || 0
                                        };
                                        previewData.push(mobile);
                                        updatePreviewTable();
                                        clearFields();
                                    }
                                });
                            });

                            function updatePreviewTable() {
                                const tbody = $('#previewTable tbody');
                                tbody.empty();

                                let totalCost = 0, totalSell = 0;

                                previewData.forEach((item, index) => {
                                    const profit = item.selling_price - item.cost_price;
                                    totalCost += item.cost_price;
                                    totalSell += item.selling_price;

                                    tbody.append(`
                                            <tr>
                                                <td>${item.mobile_name}</td>
                                                <td>${item.imei_number}</td>
                                                <td>${item.sim_lock}</td>
                                                <td>${item.color}</td>
                                                <td>${item.storage}</td>
                                                <td>${item.battery_health}</td>
                                                <td>${item.cost_price.toFixed(2)}</td>
                                                <td>${item.selling_price.toFixed(2)}</td>
                                                <td>${profit.toFixed(2)}</td>
                                                <td>
                                                    <button class="btn btn-danger btn-sm" onclick="removeRow(${index})">Delete</button>
                                                </td>
                                            </tr>
                                        `);
                                });

                                $('#totalCost').text(totalCost.toFixed(2));
                                $('#totalSell').text(totalSell.toFixed(2));
                                $('#totalProfit').text((totalSell - totalCost).toFixed(2));
                            }

                            function removeRow(index) {
                                previewData.splice(index, 1);
                                updatePreviewTable();
                            }

                            function clearFields() {
                                $('#imei_number').val('');
                                $('#mobile_name, #sim_lock, #color, #storage, #battery_health, #cost_price, #selling_price').val('');
                                $('#imei_number').focus();
                            }

                            $('#multiMobileForm').submit(function (e) {
                                e.preventDefault();

                                if (!previewData.length) {
                                    alert("Please add at least one mobile.");
                                    return;
                                }

                                $.ajax({
                                    url: '{{ route("storeMultipleMobiles") }}',
                                    method: 'POST',
                                    data: {
                                        vendor_id: $('#vendor_id').val(),
                                        mobiles: previewData,
                                        _token: '{{ csrf_token() }}'
                                    },
                                    success: function (res) {
                                        alert('Mobiles stored successfully.');
                                        location.reload();
                                    },
                                    error: function () {
                                        alert('An error occurred.');
                                    }
                                });
                            });
                        </script>

                    </div>
                </div>


            </div>
        </div>
    </div>

















@endsection