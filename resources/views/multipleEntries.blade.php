@extends('user_navbar')
@section('content')

<style>
    .select2-container .select2-selection--single {
        height: 38px !important;
        padding: 5px 10px;
    }

    ,
    #savingOverlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100vw;
        height: 100vh;
        background-color: rgba(255, 255, 255, 0.8);
        z-index: 9999;
        backdrop-filter: blur(3px);
        -webkit-backdrop-filter: blur(3px);
        display: none;
        justify-content: center;
        align-items: center;
        font-size: 1.5rem;
        font-weight: bold;
        color: #333;
    }
</style>



<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row"></div>
        <div class="content-body">
            @if (session('success'))
            <div class="alert alert-success" id="successMessage">{{ session('success') }}</div>
            @endif
            @if (session('danger'))
            <div class="alert alert-danger" id="dangerMessage" style="color: red;">{{ session('danger') }}</div>
            @endif

            <div class="col-12 latest-update-tracking mt-1">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h4 class="text-bold-500">Multiple Entries</h4>
                    </div>

                    <div class="container">
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
                            <div class="my-2" id="vendorBalanceText">
                                <strong>Vendor Balance:</strong>
                                <span id="vendorBalanceValue" class="badge badge-danger">CREDIT - Rs. 0</span>
                            </div>

                            <div>
                                <strong>Adjusted Balance After Purchase:</strong>
                                <span id="adjustedBalance">
                                    <span class="badge badge-secondary">Rs. 0</span>
                                </span>
                            </div>

                            <div class="row mb-3 mt-2">
                                <div class="col-md-4">
                                    <label for="pay_amount"><strong>Pay Amount:</strong></label>
                                    <input type="number" min="0" step="0.01" id="pay_amount" class="form-control"
                                        placeholder="Enter amount paid to vendor now">
                                </div>
                            </div>




                            <div class="card p-3">
                                <h5>Add Mobile Entry</h5>
                                <div class="row">
                                    <div class="col-md-3"><input type="text" class="form-control"
                                            placeholder="Mobile Name" id="mobile_name"></div>

                                    <div class="col-md-3">
                                        <select class="form-control" id="company_id">
                                            <option value="">Select Company</option>
                                            @foreach ($companies as $company)
                                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <select class="form-control" id="group_id">
                                            <option value="">Select Group</option>
                                            @foreach ($groups as $group)
                                            <option value="{{ $group->id }}">{{ $group->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <select class="form-control" id="sim_lock">
                                            <option value="">SIM Lock</option>
                                            <option value="J.V">J.V</option>
                                            <option value="PTA">PTA</option>
                                            <option value="Non-PTA">Non-PTA</option>
                                        </select>
                                    </div>


                                </div>
                                <div class="row mt-2">

                                    <div class="col-md-3"><input type="text" class="form-control" placeholder="Color"
                                            id="color"></div>
                                    <div class="col-md-3"><input type="text" class="form-control" placeholder="Storage"
                                            id="storage"></div>
                                    <div class="col-md-3"><input type="text" class="form-control"
                                            placeholder="Battery Health" id="battery_health"></div>
                                    <div class="col-md-3"><input type="number" class="form-control"
                                            placeholder="Cost Price" id="cost_price"></div>

                                    <!-- //Company -->
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-3"><input type="number" class="form-control"
                                            placeholder="Selling Price" id="selling_price"></div>

                                    <div class="col-md-3"><input type="text" class="form-control" placeholder="IMEI"
                                            id="imei_number" maxlength="15"></div>
                                </div>


                                <div class="row mt-2">
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



                </div>
            </div>
        </div>
    </div>
</div>

<!-- Saving Overlay -->
<div id="savingOverlay"
    style="
                                                                                                            display: none;
                                                                                                            position: fixed;
                                                                                                            top: 0;
                                                                                                            left: 0;
                                                                                                            width: 100vw;
                                                                                                            height: 100vh;
                                                                                                            background-color: rgba(255, 255, 255, 0.8);
                                                                                                            z-index: 9999;
                                                                                                            backdrop-filter: blur(3px);
                                                                                                            -webkit-backdrop-filter: blur(3px);
                                                                                                            display: none;
                                                                                                            justify-content: center;
                                                                                                            align-items: center;
                                                                                                            text-align: center;
                                                                                                            flex-direction: column;
                                                                                                            font-size: 1.5rem;
                                                                                                            font-weight: bold;
                                                                                                            color: #333;">

    <div id="savingSpinner" class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
        <span class="visually-hidden">Saving...</span>
    </div>
    <div id="savingText" class="mt-3">Saving the entries, please wait...</div>
</div>


<script>
    let previewData = [];
        let lastMobile = {}; // Store last entered mobile data

        $(document).ready(function () {

            // Initialize Select2
            $('#vendor_id').select2({
                placeholder: "Select a vendor",
                allowClear: true,
                width: '100%'
            });

            // Lock vendor after selection
            $('#vendor_id').on('change', function () {
                if ($(this).val()) {
                    $(this).prop('disabled', true).trigger("change.select2");
                    $('.select2-selection').css('pointer-events', 'none');
                    $('.select2-selection__arrow').hide();
                }
            });

            //Vendor Debot and credit handeler
            $('#vendor_id').on('change', function () {
                const vendorId = $(this).val();

                if (!vendorId) return;

                $.ajax({
                    url: '{{ route("getVendorBalance") }}',
                    method: 'GET',
                    data: { vendor_id: vendorId },
                    success: function (res) {
                        const badgeClass = res.status === 'Credit' ? 'badge-danger' :
                            res.status === 'Debit' ? 'badge-success' :
                                'badge-secondary';

                        $('#vendorBalanceValue')
                            .attr('class', `badge ${badgeClass}`)
                            .text(`${res.status.toUpperCase()} - Rs. ${res.balance}`);

                        updateAdjustedBalance();
                    }
                });
            });




            // Add Mobile Button
            $('#addMobileBtn').click(function () {
                const imei = $('#imei_number').val().trim();

                if (!imei || imei.length !== 15) {
                    alert('IMEI must be 15 digits');
                    return;
                }

                if (previewData.find(m => m.imei_number === imei)) {
                    alert('This IMEI already added in the list.');
                    return;
                }

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
                            selling_price: parseFloat($('#selling_price').val()) || 0,
                            company_id: $('#company_id').val(),
                            group_id: $('#group_id').val()
                        };

                        previewData.push(mobile);
                        lastMobile = { ...mobile }; // Save last values (except IMEI)
                        updatePreviewTable();
                        clearFields();
                    }
                });
            });

            // Update preview table
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
                                                                                                        <td><button class="btn btn-danger btn-sm" onclick="removeRow(${index})">Delete</button></td>
                                                                                                    </tr>
                                                                                                `);
                });

                $('#totalCost').text(totalCost.toFixed(2));
                $('#totalSell').text(totalSell.toFixed(2));
                $('#totalProfit').text((totalSell - totalCost).toFixed(2));

                updateAdjustedBalance(totalCost);
            }

            // function updateAdjustedBalance() {
            //     const vendorText = $('#vendorBalanceValue').text().trim();
            //     const totalCost = parseFloat($('#totalCost').text()) || 0;

            //     const balanceMatch = vendorText.match(/(CREDIT|DEBIT)\s*-\s*Rs\.\s*([0-9.]+)/i);
            //     if (!balanceMatch) {
            //         $('#adjustedBalance').html('<span class="badge badge-secondary">Settled - Rs. 0</span>');
            //         return;
            //     }

            //     const type = balanceMatch[1].toUpperCase();
            //     let currentBalance = parseFloat(balanceMatch[2]);
            //     let adjustedBalance = 0;
            //     let finalType = '';
            //     let badgeClass = '';

            //     if (type === 'CREDIT') {
            //         adjustedBalance = currentBalance + totalCost;
            //         finalType = 'CREDIT';
            //         badgeClass = 'badge-danger';
            //     } else {
            //         adjustedBalance = currentBalance - totalCost;

            //         if (adjustedBalance > 0) {
            //             finalType = 'DEBIT';
            //             badgeClass = 'badge-success';
            //         } else if (adjustedBalance < 0) {
            //             finalType = 'CREDIT';
            //             badgeClass = 'badge-danger';
            //             adjustedBalance = Math.abs(adjustedBalance);
            //         } else {
            //             finalType = 'Settled';
            //             badgeClass = 'badge-secondary';
            //         }
            //     }

            //     $('#adjustedBalance').html(`<span class="badge ${badgeClass}">${finalType} - Rs. ${adjustedBalance.toFixed(2)}</span>`);
            // }


            function updateAdjustedBalance(totalCostOverride = null) {
    const vendorText = $('#vendorBalanceValue').text().trim();
    const totalCost = totalCostOverride !== null ? totalCostOverride : (parseFloat($('#totalCost').text()) || 0);
    const payAmount = parseFloat($('#pay_amount').val()) || 0;

    const balanceMatch = vendorText.match(/(CREDIT|DEBIT)\s*-\s*Rs\.\s*([0-9.]+)/i);
    if (!balanceMatch) {
        $('#adjustedBalance').html('<span class="badge badge-secondary">Settled - Rs. 0</span>');
        return;
    }

    const type = balanceMatch[1].toUpperCase();
    let currentBalance = parseFloat(balanceMatch[2]);
    let adjustedBalance = 0;
    let finalType = '';
    let badgeClass = '';

    if (type === 'CREDIT') {
        adjustedBalance = currentBalance + totalCost - payAmount;
        finalType = adjustedBalance > 0 ? 'CREDIT' : adjustedBalance < 0 ? 'DEBIT' : 'Settled';
    } else {
        adjustedBalance = currentBalance - totalCost - payAmount;
        finalType = adjustedBalance > 0 ? 'DEBIT' : adjustedBalance < 0 ? 'CREDIT' : 'Settled';
    }

    adjustedBalance = Math.abs(adjustedBalance);
    badgeClass = finalType === 'CREDIT' ? 'badge-danger' :
                 finalType === 'DEBIT' ? 'badge-success' :
                 'badge-secondary';

    $('#adjustedBalance').html(`<span class="badge ${badgeClass}">${finalType} - Rs. ${adjustedBalance.toFixed(2)}</span>`);
}


$('#pay_amount').on('input', function () {
    updateAdjustedBalance();
});



            // Remove row
            window.removeRow = function (index) {
                previewData.splice(index, 1);
                updatePreviewTable();
            };

            // Clear and prefill fields
            function clearFields() {
                $('#imei_number').val('').focus();

                $('#mobile_name').val(lastMobile.mobile_name || '');
                $('#sim_lock').val(lastMobile.sim_lock || '');
                $('#color').val(lastMobile.color || '');
                $('#storage').val(lastMobile.storage || '');
                $('#battery_health').val(lastMobile.battery_health || '');
                $('#cost_price').val(lastMobile.cost_price || '');
                $('#selling_price').val(lastMobile.selling_price || '');
                $('#company_id').val(lastMobile.company_id || '');
                $('#group_id').val(lastMobile.group_id || '');
            }

            // Submit all
            $('#multiMobileForm').submit(function (e) {
                e.preventDefault();

                if (!previewData.length) {
                    alert("Please add at least one mobile.");
                    return;
                }

                const submitBtn = $(this).find('button[type="submit"]');
                submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');

                // Show overlay and spinner
                $('#savingOverlay').show();
                $('#savingText').text('Saving the entries, please wait...');
                $('#savingSpinner').removeClass('d-none');

                $.ajax({
                url: '{{ route("storeMultipleMobiles") }}',
                method: 'POST',
                data: {
                vendor_id: $('#vendor_id').val(),
                mobiles: previewData,
                pay_amount: $('#pay_amount').val(),
                _token: '{{ csrf_token() }}'
                },
                success: function () {
                $('#savingSpinner').removeClass('spinner-border').html('✅');
                $('#savingText').text('All mobiles stored successfully.');
                setTimeout(() => {
                location.reload();
                }, 1500);
                },
                error: function (xhr) {
                $('#savingOverlay').hide();
                submitBtn.prop('disabled', false).html('Submit All');
                
                // Log the full error response in the console
                console.error("Server Error:", xhr);
                
                // Display user-friendly message
                let message = "An unexpected error occurred.";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
                }
                
                alert(message);
                }
                });
            });


            // Keyboard shortcuts
            $('#imei_number').keypress(function (e) {
                if (e.which === 13) {
                    $('#addMobileBtn').click();
                    return false;
                }
            });

            $('#cost_price, #selling_price').keydown(function (e) {
                if (e.ctrlKey && e.key === 'Enter') {
                    $('#addMobileBtn').click();
                    return false;
                }
            });

            $(document).keydown(function (e) {
                if (e.key === "Escape") {
                    $('#imei_number').val('').focus();
                }

                if (e.ctrlKey && e.key === 's') {
                    e.preventDefault();
                    $('#multiMobileForm').submit();
                }
            });

        });

       

</script>




@endsection