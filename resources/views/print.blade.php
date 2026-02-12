<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <script type="text/javascript">
        window.onload = function() {
            window.print();
        }

    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @page {
            size: 72mm 297mm;
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: sans-serif;
            font-size: 10px;
        }

        table {
            width: 100%;
            padding: 0;
            margin: 0 auto;
            table-layout: fixed;
        }

        th {
            text-align: left;
            padding-top: 8px;
        }

        tbody {
            font-size: 11px;
        }

        tbody td {
            padding: 2px 0;
        }

        .total {
            font-weight: bold;
            padding-top: 0.25rem;
        }

        .total-amount {
            font-weight: bold;
            text-align: right;
            padding-right: 10px;
        }

        .amount {
            text-align: right;
            padding-right: 10px;
        }

        .text-center {
            text-align: center;
        }

        h1 {
            margin: 0;
            padding: 2px 0;
            font-size: medium;
            text-align: center;
        }

        h2 {
            margin: 0;
            padding: 0;
            text-align: center;
        }

        h4 {
            margin: 0;
            padding: 0;
        }

        p {
            margin: 0;
            padding: 0;
            text-align: center;
        }

        .orderNumber {
            text-align: center;
            margin-bottom: 2px;
            font-size: 1.25rem;
            color: #fff;
            background: #000;
        }

        .invoice-content {
            padding: 0 16px;
        }

        .restaurant-header {
            text-align: center;
            margin-bottom: 5px;
            border-bottom: 1px dashed #333;
            padding-bottom: 5px;
        }

        .restaurant-name {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 2px;
        }

        .restaurant-details {
            font-size: 10px;
            line-height: 1.2;
        }

        .bill-header {
            text-align: center;
            margin: 8px 0;
        }

        .bill-no {
            font-weight: bold;
            font-size: 13px;
            text-transform: uppercase;
        }

        .time-date {
            font-size: 10px;
            margin: 3px 0;
        }

        .order-header {
            font-weight: bold;
            margin-bottom: 3px;
            border-bottom: 1px dashed #333;
            padding-bottom: 2px;
        }

    </style>
</head>
<body>
    <div style="margin: 10px;">
        <h1>{{ $company->name ?? "Company Name" }}</h1>
        <p>{{ $company->address ?? "Company Address" }}</p>
        <p>{{ $company->phone ?? "Company Phone" }}</p>
        <p>{{ $company->email ?? "Company Email" }}</p>
    </div>
    <div style="margin: 10px 10px; border: #000; border-style: dotted; padding: 6px;">
        <div style="font-weight: bold; text-align: center; padding-bottom: 6px;">Customer Details:</div>
        <div>Name: {{ $order->customer_name ?? '' }}</div>
        <div>Address: {{ $order->customer_address ?? '' }}</div>
        <div>Phone: {{ $order->customer_phone ?? '' }}</div>
    </div>
    <div class="invoice">
        @php
        $subtotal = $order->total_amount;
        $discountAmount = $order->discount_amount;
        $deliveryCharge = $order->delivery_charge;
        $totalAmount = $order->final_amount;
        $customizationAmount = $order->customization_amount;
        @endphp
        <div class="invoice-content">
            <div class="order-header">
                Order #{{ $order->id }} ({{ $order->created_at->format('h:i A') }} • {{ $order->created_at->format('jS M, Y') }})
            </div>
            <table>
                <tbody>
                    @foreach ($order->orderItems as $item)
                    <tr>
                        <td colspan="2">
                            @if ($item->is_customizable == "yes")
                            <span>Customized</span>
                            @else
                            <span></span>
                            @endif
                            {{ $item->product->name }} x{{ formatNumber($item->quantity) }}</td>
                        <td class="amount" colspan="2">Rs. {{ formatNumber($item->total) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <table>
                <tfoot>
                    <tr>
                        <td style="text-align:center;" colspan='4'>
                            <hr style="border: 2px dotted #000000; border-style: none none dotted; color: #fff; background-color: #fff;">
                        </td>
                    </tr>
                    <tr>
                        <td class="total" colspan="2">Sub Total</td>
                        <td class="total-amount" colspan="2">Rs. {{ formatNumber($subtotal) }}</td>
                    </tr>
                    @if($customizationAmount > 0)
                    <tr>
                        <td class="total" colspan="2">Customization Charge</td>
                        <td class="total-amount" colspan="2">Rs. {{ formatNumber($customizationAmount) }}</td>
                    </tr>
                    @endif
                    @if($discountAmount > 0)
                    <tr>
                        <td class="total" colspan="2">Discount</td>
                        <td class="total-amount" colspan="2">Rs. {{ formatNumber($discountAmount) }}</td>
                    </tr>
                    @endif
                    @if($deliveryCharge > 0)
                    <tr>
                        <td class="total" colspan="2">Delivery Charge</td>
                        <td class="total-amount" colspan="2">Rs. {{ formatNumber($deliveryCharge) }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td class="total" colspan="2">Grand Total</td>
                        <td class="total-amount" colspan="2">Rs. {{ formatNumber($totalAmount) }}</td>
                    </tr>
                    <tr>
                        <td style="text-align:center;" colspan='4'>
                            <hr style="border: 2px dotted #000000; border-style: none none dotted; color: #fff; background-color: #fff;">
                        </td>
                    </tr>
                </tfoot>
            </table>

            <div class="bill-footer">
                <p>Thank you for purchasing with us!</p>
                <p>We eager to serve you once again soon.</p>
            </div>
        </div>
    </div>

    @php
    function formatNumber($number) {
    $formattedNumber = rtrim(rtrim(number_format($number, 2), '0'), '.');
    if (strpos($formattedNumber, '.5') !== false) {
    $formattedNumber = str_replace('.5', '½', $formattedNumber);
    }
    return $formattedNumber;
    }
    @endphp
</body>
</html>
