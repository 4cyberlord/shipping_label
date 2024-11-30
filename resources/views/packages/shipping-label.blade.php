<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shipping Label</title>
    <style>
        @page {
            size: 4in 6in;
            margin: 0;
        }
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Arial', sans-serif;
            font-size: 9px;
            width: 4in;
            height: 6in;
        }
        .label {
            width: 4in;
            height: 6in;
            position: relative;
            border: 1px solid #000;
            overflow: hidden;
        }
        .top-banner {
            background: #000;
            color: white;
            padding: 8px;
            text-align: center;
        }
        .company-name {
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 1px;
        }
        .express-tag {
            font-size: 7px;
            margin-top: 2px;
            letter-spacing: 1px;
        }
        .tracking-section {
            padding: 6px;
            text-align: center;
            border-bottom: 1px solid #000;
        }
        .tracking-label {
            font-size: 7px;
            color: #666;
            text-transform: uppercase;
        }
        .tracking-number {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            font-weight: bold;
            letter-spacing: 1px;
            margin-top: 2px;
        }
        .shipping-info {
            display: flex;
            justify-content: space-between;
            padding: 6px 12px;
            border-bottom: 1px solid #000;
            background: #f8f8f8;
        }
        .info-item {
            text-align: center;
        }
        .info-label {
            font-size: 7px;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 2px;
        }
        .info-value {
            font-size: 10px;
            font-weight: bold;
        }
        .addresses {
            padding: 10px;
        }
        .address-block {
            margin-bottom: 10px;
            position: relative;
            border: 1px solid #000;
            padding: 16px 10px 10px;
        }
        .address-label {
            position: absolute;
            top: -6px;
            left: 8px;
            background: #000;
            color: white;
            padding: 2px 8px;
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .address-content {
            font-size: 9px;
            line-height: 1.4;
        }
        .address-content strong {
            display: block;
            font-size: 10px;
            margin-bottom: 3px;
        }
        .barcode-section {
            position: absolute;
            bottom: 25px;
            left: 0;
            right: 0;
            text-align: center;
            padding: 8px 0;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
        }
        .footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            padding: 6px;
            font-size: 7px;
            background: #000;
            color: white;
        }
    </style>
</head>
<body>
    <div class="label">
        <div class="top-banner">
            <div class="company-name">RYYHUB</div>
            <div class="express-tag">EXPRESS SHIPPING</div>
        </div>

        <div class="tracking-section">
            <div class="tracking-label">Tracking Number</div>
            <div class="tracking-number">{{ $package->tracking_number }}</div>
        </div>

        <div class="shipping-info">
            <div class="info-item">
                <div class="info-label">Date</div>
                <div class="info-value">{{ $package->created_at->format('d M Y') }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Weight</div>
                <div class="info-value">{{ $package->weight }} KG</div>
            </div>
            <div class="info-item">
                <div class="info-label">Cost</div>
                <div class="info-value">GHS {{ number_format($package->shipping_cost, 2) }}</div>
            </div>
        </div>

        <div class="addresses">
            <div class="address-block">
                <div class="address-label">From</div>
                <div class="address-content">
                    <strong>{{ $package->fromAddress->company_name }}</strong>
                    {{ $package->fromAddress->address_line_1 }}<br>
                    @if($package->fromAddress->address_line_2)
                        {{ $package->fromAddress->address_line_2 }}<br>
                    @endif
                    {{ $package->fromAddress->city }}, {{ $package->fromAddress->state }}<br>
                    {{ $package->fromAddress->zip_code }}
                </div>
            </div>

            <div class="address-block">
                <div class="address-label">To</div>
                <div class="address-content">
                    <strong>{{ $package->toAddress->contact_name }}</strong>
                    {{ $package->toAddress->address_line_1 }}<br>
                    @if($package->toAddress->address_line_2)
                        {{ $package->toAddress->address_line_2 }}<br>
                    @endif
                    {{ $package->toAddress->city }}, {{ $package->toAddress->state }}<br>
                    {{ $package->toAddress->zip_code }}<br>
                    {{ $package->toAddress->country }}
                </div>
            </div>
        </div>

        <div class="barcode-section">
            {!! DNS1D::getBarcodeHTML($package->tracking_number, 'C128', 2.5, 50) !!}
        </div>

        <div class="footer">
            ELECTRONIC RATE APPROVED #{{ substr($package->tracking_number, -8) }}
        </div>
    </div>
</body>
</html>

