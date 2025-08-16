@extends('user_navbar')
@section('content')
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-header row"></div>
        <div class="content-body">
            <div class="container-fluid">
                <h2 class="mb-4">Point of Sale (POS)</h2>
                <div class="row g-4">
                    <!-- Available Mobiles Table -->
                    <div class="col-md-7">
                        <div class="card shadow h-100">
                            <div class="card-header bg-primary text-white">
                                <strong>Available Mobiles</strong>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered" id="mobiles-table">
                                        <thead>
                                            <tr>
                                                <th>Mobile Name</th>
                                                <th>IMEI</th>
                                                <th>Color</th>
                                                <th>Storage</th>
                                                <th>Selling Price</th>
                                                <th>Add</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($mobiles as $mobile)
                                            <tr>
                                                <td>{{ $mobile->mobile_name }}</td>
                                                <td>{{ $mobile->imei_number }}</td>
                                                <td>{{ $mobile->color }}</td>
                                                <td>{{ $mobile->storage }}</td>
                                                <td>{{ number_format($mobile->selling_price, 0, '.', '') }}</td>
                                                <td>
                                                    <button type="button"
                                                        class="btn btn-sm btn-success add-to-cart-btn">Add</button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cart Table -->
                    <div class="col-md-5">
                        <div class="card shadow h-100">
                            <div class="card-header bg-success text-white">
                                <strong>Cart (To Sell)</strong>
                            </div>
                            <div class="card-body">
                                <!-- Vendor/Customer Selection -->
                                <div class="mb-3">
                                    <label for="vendor_id" class="form-label">Vendor (optional):</label>
                                    <select class="form-select" id="vendor_id" name="vendor_id">
                                        <option value="">Select Vendor</option>
                                        @foreach($vendors as $vendor)
                                        <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                        @endforeach
                                    </select>
                                    <div id="vendor-balance" class="text-primary small mt-1" style="display:none;">
                                        Balance: <span id="vendor-balance-value">0</span>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="customer_name" class="form-label">Customer (walk-in):</label>
                                    <input type="text" class="form-control" id="customer_name" name="customer_name"
                                        placeholder="Type customer name">
                                </div>
                                <!-- Cart Table -->
                                <div class="table-responsive">
                                    <table class="table" id="cart-table">
                                        <thead>
                                            <tr>
                                                <th>Mobile Name</th>
                                                <th>IMEI</th>
                                                <th>Price</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- JS will fill this -->
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Discount</label>
                                    <input type="number" min="0" class="form-control" id="discount" value="0">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Total</label>
                                    <input type="text" class="form-control bg-light" id="total" readonly>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Pay Amount</label>
                                    <input type="number" min="0" class="form-control" id="pay_amount" value="0">
                                    {{-- <div class="mb-2">
                                        <label class="form-label">Due / Change</label>
                                        <input type="text" class="form-control bg-light" id="balance_due" readonly>
                                    </div> --}}
                                </div>
                                <button class="btn btn-primary w-100" id="finalize-sale-btn">Finalize Sale</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>  


<script>
    $(function() {
    $('#mobiles-table').DataTable({ order: [] });

    let cart = {};

    // Add to cart
    $(document).on('click', '.add-to-cart-btn', function() {
        let tr = $(this).closest('tr');
        let mobile = {
            mobile_name: tr.find('td').eq(0).text().trim(),
            imei_number: tr.find('td').eq(1).text().trim(),
            color: tr.find('td').eq(2).text().trim(),
            storage: tr.find('td').eq(3).text().trim(),
            selling_price: parseFloat(tr.find('td').eq(4).text().replace(/,/g,'')) || 0
        };
        if (cart[mobile.imei_number]) {
            alert('Already in cart');
            return;
        }
        cart[mobile.imei_number] = mobile;
        renderCart();
    });

    // Remove from cart
    $(document).on('click', '.remove-from-cart-btn', function() {
        let imei = $(this).data('imei');
        delete cart[imei];
        renderCart();
    });

    // When user edits a price in the cart, update totals + sync to cart
    $(document).on('input', '#cart-table .price-input', function() {
        const tr = $(this).closest('tr');
        const imei = tr.data('imei');
        const price = parseFloat($(this).val()) || 0;
        if (cart[imei]) cart[imei].selling_price = price;
        updateTotals();
    });

    // Render cart rows
    function renderCart() {
        let tbody = $('#cart-table tbody');
        tbody.empty();
        $.each(cart, function(imei, m) {
            tbody.append(`
                <tr data-imei="${m.imei_number}">
                    <td>${m.mobile_name}</td>
                    <td>${m.imei_number}</td>
                    <td>
                        <input type="number" step="0.01" min="0"
                               class="form-control price-input" style="width: 110px;"
                               value="${m.selling_price}">
                    </td>
                    <td>
                        <button class="btn btn-danger btn-sm remove-from-cart-btn" data-imei="${m.imei_number}">
                            Remove
                        </button>
                    </td>
                </tr>
            `);
        });
        updateTotals();
    }

    // Sum live prices from the table, apply discount, update total & balance
    function updateTotals() {
        let total = 0;
        $('#cart-table tbody tr').each(function() {
            const val = parseFloat($(this).find('.price-input').val()) || 0;
            total += val;
        });

        let discount = parseFloat($('#discount').val()) || 0;
        // Optional: prevent discount from exceeding total
        if (discount > total) discount = total;

        const finalTotal = total - discount;
        $('#total').val(finalTotal);

        updateBalanceDue();
    }

    function updateBalanceDue() {
        let total = parseFloat($('#total').val()) || 0;
        let pay = parseFloat($('#pay_amount').val()) || 0;
        let balance = pay - total;
        let text = balance < 0
            ? `Due: ${Math.abs(balance).toLocaleString()}`
            : `Change: ${balance.toLocaleString()}`;
        $('#balance_due').val && $('#balance_due').val(text); // safe if input exists
    }

    // Inputs that affect totals
    $('#pay_amount').on('input', updateBalanceDue);
    $('#discount').on('input', updateTotals); // don't re-render (keeps edited prices)

    // Vendor/Customer field logic
    $('#vendor_id').on('change', function() {
        if ($(this).val()) {
            $('#customer_name').val('').prop('disabled', true);
            $.get('/vendor/balance/' + $(this).val(), function(res) {
                $('#vendor-balance').show();
                $('#vendor-balance-value').text(res.balance);
            });
        } else {
            $('#customer_name').prop('disabled', false);
            $('#vendor-balance').hide();
        }
    });

    $('#customer_name').on('input', function() {
        if ($(this).val()) {
            $('#vendor_id').val('').prop('disabled', true);
            $('#vendor-balance').hide();
        } else {
            $('#vendor_id').prop('disabled', false);
        }
    });

    // Finalize sale
  // Finalize sale
$('#finalize-sale-btn').on('click', function() {
    if (Object.keys(cart).length === 0) return alert('Cart is empty');

    // must have either vendor or customer
    if (!$('#vendor_id').val() && !$('#customer_name').val()) {
        return alert('Select a vendor or enter a customer name');
    }

    // 🔴 Amount rule: if NO vendor selected, pay_amount must be > 0
    const vendorId   = $('#vendor_id').val();
    const payRaw     = $('#pay_amount').val();
    const payAmount  = parseFloat(payRaw);

    if (!vendorId && (!payRaw || isNaN(payAmount) || payAmount <= 0)) {
        alert('Please enter the amount');
        $('#pay_amount').focus();
        return;
    }

    // Ensure we send the latest edited prices
    $('#cart-table tbody tr').each(function() {
        const imei = $(this).data('imei');
        const price = parseFloat($(this).find('.price-input').val()) || 0;
        if (cart[imei]) cart[imei].selling_price = price;
    });

    let saleData = {
        mobiles: Object.values(cart),
        vendor_id: vendorId,
        customer_name: $('#customer_name').val(),
        discount: $('#discount').val(),
        pay_amount: $('#pay_amount').val(),
        _token: '{{ csrf_token() }}'
    };

    $.post('/sales/store', saleData, function(response) {
        alert(response.message);
        if (response.success && response.receipt_url) {
            window.open(response.receipt_url, '_blank');
            location.reload();
        }
    });
});
});

$(document).ready(function () {
    $('#vendor_id').select2({
        placeholder: "Select a vendor",
        allowClear: true,
        width: '100%'
    });
});
</script>


@endsection