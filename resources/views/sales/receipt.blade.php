<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Sale Receipt #{{ $sale->id }}</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Noto+Nastaliq+Urdu:wght@400;700&display=swap"
        rel="stylesheet">
    <style>
        html,
        body {
            background: #fff;
            margin: 0;
            padding: 0;
            width: 100%;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }

        .receipt {
            width: 72mm;
            margin: 16px auto;
            font-family: 'Poppins', Arial, sans-serif;
            font-size: 12px;
            color: #000;
        }

        @media print {

            html,
            body {
                background: #fff;
                margin: 0 !important;
                padding: 0 !important;
                width: 80mm !important;
                min-height: unset !important;
                display: block;
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
            text-align: center;
        }

        .bold {
            font-weight: bold !important;
        }

        .shop-logo {
            font-size: 20px;
            margin-bottom: 2px;
            letter-spacing: 1px;
        }

        .main-label {
            font-size: 14px;
        }

        .divider {
            border-top: 2px dashed #000;
            margin: 8px 0;
        }

        .receipt-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 4px;
        }

        .receipt-table th,
        .receipt-table td {
            border: 1px solid #000;
            padding: 2px 3px;
            font-size: 13px;
            word-break: break-word;
            text-align: center;
        }

        .receipt-table th {
            background: #fafafa;
        }

        .receipt-table td:first-child,
        .receipt-table th:first-child {
            text-align: left;
        }

        .totals {
            font-size: 14px;
            font-weight: bold;
        }

        .total-label {
            text-align: left;
        }

        .total-value {
            text-align: right;
            width: 70px;
        }

        .policy {
            font-size: 10.5px;
            line-height: 1.5;
            margin-bottom: 6px;
            margin-top: 6px;
        }

        .urdu {
            font-family: 'Noto Nastaliq Urdu', 'Noto Sans Arabic', serif;
            font-size: 13px;
            font-weight: bold;
            direction: rtl;
            text-align: right;
            letter-spacing: 0.2px;
            line-height: 1.65;
        }

        .address-note {
            font-size: 10px;
            text-align: center;
            margin-top: 4px;
        }
    </style>
</head>

<body>
    <div class="receipt">
        <div class="center shop-logo bold">AMZ</div>
        <div class="center shop-logo bold">Shahzad Mobiles</div>
        <div class="center main-label">Hasilpur Branch</div>
        <div class="center" style="font-size:12px; margin-bottom: 2px;">
            <span class="bold">Ph: 0322-3190100, 0301-7662525</span>
        </div>

        <div class="divider"></div>
        <table style="width:100%;margin-bottom:0;">
            <tr>
                <td class="bold">Invoice#</td>
                <td>{{ $sale->id }}</td>
            </tr>
            <tr>
                <td class="bold">Date</td>
                <td>{{ \Carbon\Carbon::parse($sale->created_at)->format('d M Y, H:i') }}</td>
            </tr>
            <tr>
                <td class="bold">Sold By</td>
                <td>{{ $sale->seller->name ?? '-' }}</td>
            </tr>
            @if($sale->vendor)
            <tr>
                <td class="bold">Vendor</td>
                <td>{{ $sale->vendor->name }}</td>
            </tr>
            @elseif($sale->customer_name)
            <tr>
                <td class="bold">Customer</td>
                <td>{{ $sale->customer_name }}</td>
            </tr>
            @endif
        </table>

        <div class="divider"></div>
        <table class="receipt-table">
            <thead>
                <tr>
                    <th style="width:40%;">Mobile Name</th>
                    <th style="width:30%;">Company</th>
                    <th style="width:30%;">Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->saleMobiles as $sm)
                <tr>
                    <td style="text-align:left;">{{ $sm->mobile->mobile_name ?? '-' }}</td>
                    <td>{{ $sm->mobile->company->name ?? ($sm->mobile->brand ?? '-') }}</td>
                    {{-- ORIGINAL price per item --}}
                    <td>{{ number_format($sm->selling_price, 0) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @php
        // Safe calculations for display (do not depend on $sale->total)
        $gross = (float)($sale->total_amount ?? $sale->saleMobiles->sum('selling_price'));
        $discount = (float)($sale->discount ?? 0);
        $grand = max(round($gross - $discount, 2), 0);
        $paid = (float)($sale->paid_amount ?? $sale->pay_amount ?? 0);
        $balance = max(round($grand - $paid, 2), 0);
        @endphp

        <div class="divider"></div>
        <table style="width:100%;">
            <tr>
                <td class="totals total-label">Total</td>
                <td class="totals total-value">Rs. {{ number_format($gross, 0) }}</td>
            </tr>
            <tr>
                <td class="totals total-label">Discount</td>
                <td class="totals total-value">Rs. {{ number_format($discount, 0) }}</td>
            </tr>
            <tr>
                <td class="totals total-label">Grand Total</td>
                <td class="totals total-value">Rs. {{ number_format($grand, 0) }}</td>
            </tr>
            <tr>
                <td class="totals total-label">Paid</td>
                <td class="totals total-value">Rs. {{ number_format($paid, 0) }}</td>
            </tr>
            <tr>
                <td class="totals total-label">Balance</td>
                <td class="totals total-value">Rs. {{ number_format($balance, 0) }}</td>
            </tr>
        </table>

        <div class="divider"></div>
        <div class="policy">
            <div class="bold center" style="font-size:11.5px; margin-bottom:2px;">Return & Exchange Policy:</div>
        </div>
        <div class="urdu">
            پی ٹی اے موقع پر چیک کریں، بعد میں دکاندار ذمہ دار نہ ہوگا<br>
            کلیم کمپنی کی ذمہ داری ہے، دکاندار کلیم دینے کا پابند نہ ہوگا<br>
        </div>
        <div class="divider"></div>
        <div class="address-note"><b>Address: Baldia road Hasilpur</b></div>
        <div class="center bold" style="font-size:13px;">Thank you for shopping!</div>

        <div class="no-print center" style="margin-top:10px;">
            <button onclick="window.print()" style="padding:5px 16px;font-size:13px;">Print</button>
        </div>
    </div>
</body>

</html>