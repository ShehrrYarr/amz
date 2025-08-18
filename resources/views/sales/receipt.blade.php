<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Receipt #{{ $sale->id }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Print-friendly fonts --}}
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Noto+Nastaliq+Urdu:wght@400;700&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --ink: #000;
        }

        * {
            box-sizing: border-box
        }

        html,
        body {
            margin: 0;
            padding: 0;
            background: #fff;
            color: var(--ink);
            font-family: 'Poppins', Arial, sans-serif;
        }

        /* Canvas */
        .receipt {
            width: 72mm;
            /* printable width for 80mm roll */
            margin: 12px auto;
        }

        /* Brand bar */
        .brandbar {
            background: #000;
            color: #fff;
            text-align: center;
            border-radius: 10px;
            padding: 8px 8px 9px;
        }

        .brandbar .amz {
            display: inline-block;
            font-weight: 700;
            letter-spacing: 4px;
            font-size: 22px;
            padding: 2px 10px;
            border: 2px solid #fff;
            border-radius: 8px;
        }

        .brandbar .store {
            font-size: 14px;
            font-weight: 700;
            margin-top: 5px;
        }

        .brandbar .branch {
            font-size: 11px;
            opacity: .95;
        }

        .brandbar .phones {
            font-size: 11px;
            margin-top: 3px;
            font-weight: 600;
        }

        /* Dividers */
        .cut {
            border-top: 2px dashed #000;
            margin: 8px 0;
        }

        /* Key-Value list with dotted separators — compact & legible */
        .kv {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        .kv tr {
            border-bottom: 1px dotted #000;
        }

        .kv td {
            padding: 4px 0;
        }

        .kv .k {
            width: 36%;
            font-weight: 600;
        }

        .kv .v {
            text-align: right;
        }

        /* Items list – light lines, price aligned right */
        .items {
            width: 100%;
            border-collapse: collapse;
            font-size: 12.5px;
            table-layout: fixed;
        }

        .items thead th {
            border-bottom: 2px solid #000;
            padding: 3px 0 4px;
            font-weight: 700;
        }

        .items td {
            border-bottom: 1px dotted #000;
            padding: 4px 0;
            vertical-align: top;
            word-break: break-word;
        }

        .items .name {
            width: 46%;
        }

        .items .brand {
            width: 24%;
            text-align: center;
        }

        .items .price {
            width: 30%;
            text-align: right;
        }

        /* Totals block — strong hierarchy */
        .totals {
            width: 100%;
            font-size: 12.5px;
            margin-top: 2px;
        }

        .totals td {
            padding: 3px 0;
        }

        .totals .label {
            font-weight: 600;
        }

        .totals .val {
            text-align: right;
            width: 90px;
        }

        .payable {
            border: 1px solid #000;
            border-radius: 8px;
            padding: 5px 8px;
            font-weight: 700;
            margin: 2px 0 4px;
        }

        .due {
            font-weight: 700;
        }

        /* Policy / Urdu */
        .policy-title {
            text-align: center;
            font-weight: 700;
            font-size: 11.5px;
            margin-bottom: 4px;
        }

        .urdu {
            font-family: 'Noto Nastaliq Urdu', 'Noto Sans Arabic', serif;
            direction: rtl;
            font-size: 13px;
            line-height: 1.7;
            letter-spacing: .2px;
            text-align: right;
        }

        /* Footer */
        .addr {
            text-align: center;
            font-size: 10.5px;
            margin-top: 4px;
        }

        .thanks {
            text-align: center;
            font-weight: 700;
            font-size: 13px;
            margin-top: 2px;
        }

        /* Print */
        @media print {

            html,
            body {
                width: 80mm !important;
                background: #fff;
                margin: 0 !important;
                padding: 0 !important;
            }

            .receipt {
                width: 72mm !important;
                margin: 0 auto !important;
            }

            .no-print {
                display: none !important;
            }
        }

        .center {
            text-align: center
        }

        .mt-2 {
            margin-top: 6px
        }

        .mt-4 {
            margin-top: 10px
        }

        .btn {
            padding: 6px 16px;
            font-size: 13px;
        }
    </style>
</head>

<body>
    @php
    $fmt = fn($n) => number_format((float)$n, 0);

    $subtotal = (float) ($sale->total_amount ?? 0);
    $discount = (float) ($sale->discount ?? 0);
    $paid = (float) ($sale->paid_amount ?? 0);
    $payable = max(0, $subtotal - $discount);
    $balance = $paid - $payable; // +ve => change, -ve => due
    @endphp

    <div class="receipt">

        <!-- BRAND STRIP -->
        <div class="brandbar">
            <div class="amz">AMZ</div>
            <div class="store">Shahzad Mobiles</div>
            <div class="branch">Hasilpur Branch</div>
            <div class="phones">Ph: 0301-7662525, 0322-3190100</div>
        </div>

        <div class="cut"></div>

        <!-- META -->
        <table class="kv">
            <tr>
                <td class="k">Invoice#</td>
                <td class="v">#{{ $sale->id }}</td>
            </tr>
            <tr>
                <td class="k">Date</td>
                <td class="v">{{ \Carbon\Carbon::parse($sale->created_at)->format('d M Y, H:i') }}</td>
            </tr>
            <tr>
                <td class="k">Sold By</td>
                <td class="v">{{ optional($sale->seller)->name ?? '—' }}</td>
            </tr>
            @if($sale->vendor)
            <tr>
                <td class="k">Vendor</td>
                <td class="v">{{ $sale->vendor->name }}</td>
            </tr>
            @elseif($sale->customer_name)
            <tr>
                <td class="k">Customer</td>
                <td class="v">{{ $sale->customer_name }}</td>
            </tr>
            @endif
        </table>

        <div class="cut"></div>

        <!-- ITEMS -->
        <table class="items">
            <thead>
                <tr>
                    <th class="name">Mobile</th>
                    <th class="brand">Company</th>
                    <th class="price">Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->mobiles as $sm)
                <tr>
                    <td class="name">{{ $sm->mobile->mobile_name }}</td>
                    <td class="brand">{{ optional($sm->mobile->company)->name ?? '—' }}</td>
                    <td class="price">{{ $fmt($sm->selling_price) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="cut"></div>

        <!-- TOTALS -->
        <table class="totals">
            <tr>
                <td class="label">Total</td>
                <td class="val">Rs. {{ $fmt($subtotal) }}</td>
            </tr>
            <tr>
                <td class="label">Discount</td>
                <td class="val">Rs. {{ $fmt($discount) }}</td>
            </tr>
            <tr>
                <td colspan="2" class="payable">
                    Payable: <span style="float:right;">Rs. {{ $fmt($payable) }}</span>
                </td>
            </tr>
            <tr>
                <td class="label">Paid</td>
                <td class="val">Rs. {{ $fmt($paid) }}</td>
            </tr>
            <tr>
                @if($balance < 0) <td class="label due">Due</td>
                    <td class="val due">Rs. {{ $fmt(abs($balance)) }}</td>
                    @elseif($balance > 0)
                    <td class="label due">Change</td>
                    <td class="val due">Rs. {{ $fmt($balance) }}</td>
                    @else
                    <td class="label due">Balance</td>
                    <td class="val due">Rs. 0</td>
                    @endif
            </tr>
        </table>

        <div class="cut mt-2"></div>

        <!-- POLICY -->
        <div class="policy-title">Return &amp; Exchange Policy</div>
        <div class="urdu">
            پی ٹی اے موقع پر چیک کریں، بعد میں دکاندار ذمہ دار نہ ہوگا<br>
            کلیم کمپنی کی ذمہ داری ہے، دکاندار کلیم دینے کا پابند نہیں ہوگا
        </div>

        <div class="cut mt-2"></div>

        <!-- FOOTER -->
        <div class="addr"><b>Address:</b> Baldia Road, Hasilpur</div>
        <div class="thanks">Thank you for shopping!</div>

        <div class="center no-print mt-4">
            <button class="btn" onclick="window.print()">Print</button>
        </div>
    </div>
</body>

</html>