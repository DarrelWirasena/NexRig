<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }} — NexRig</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,700&family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.6/dist/JsBarcode.all.min.js"></script>

    <style>
        /* ══════════════════════════════════════
           TOKENS & BASE (COMPACT VERSION)
        ══════════════════════════════════════ */
        :root {
            --bg-base: #050505;
            --bg-card: #0a0a0a;
            --bg-deep: #050014;
            --bg-surface: #111111;
            --blue: #2563eb;
            --blue-light: #3b82f6;
            --blue-dim: rgba(37, 99, 235, 0.15);
            --blue-glow: rgba(37, 99, 235, 0.4);
            --white: #ffffff;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
            --border: rgba(255, 255, 255, 0.07);
            --border-mid: rgba(255, 255, 255, 0.10);
            --font-display: 'Playfair Display', Georgia, serif;
            --font-body: 'DM Sans', sans-serif;
            --font-mono: 'DM Mono', 'Courier New', monospace;
            --pad-x: 32px; /* Dikurangi dari 56px */
        }

        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: var(--font-body); background: var(--bg-base); color: var(--white); min-height: 100vh; -webkit-font-smoothing: antialiased; overflow-x: hidden; }
        * { scrollbar-width: none; -ms-overflow-style: none; }
        *::-webkit-scrollbar { display: none; }

        /* ══════════════════════════════════════
           PRINT BAR
        ══════════════════════════════════════ */
        .print-bar {
            position: sticky; top: 0; z-index: 100; background: rgba(5, 5, 5, 0.95); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-mid); padding: 10px 24px; display: flex; align-items: center; justify-content: space-between;
        }
        .print-bar-logo { font-family: var(--font-display); font-size: 16px; font-weight: 700; color: var(--white); }
        .print-bar-logo em { font-style: normal; color: var(--blue-light); }
        .print-bar-label { font-family: var(--font-mono); font-size: 11px; color: var(--gray-400); letter-spacing: 1px; }
        .btn { padding: 6px 14px; border-radius: 6px; font-size: 11px; font-weight: 600; cursor: pointer; border: none; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: all .2s; }
        .btn-ghost { background: transparent; border: 1px solid var(--border-mid); color: var(--gray-300); }
        .btn-ghost:hover { border-color: var(--blue); color: var(--white); }
        .btn-blue { background: var(--blue); color: #fff; }

        /* ══════════════════════════════════════
           PAGE + CARD
        ══════════════════════════════════════ */
        .page-wrap { max-width: 850px; margin: 20px auto 40px; padding: 0 16px; }
        .invoice-card { background: var(--bg-card); border-radius: 16px; overflow: hidden; border: 1px solid var(--border-mid); box-shadow: 0 10px 40px rgba(0,0,0,0.5); }

        /* ══════════════════════════════════════
           HERO (DIPADATKAN)
        ══════════════════════════════════════ */
        .invoice-hero { background: var(--bg-deep); padding: 24px var(--pad-x) 0; position: relative; border-bottom: 1px solid var(--blue); }
        .hero-top { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; flex-wrap: wrap; gap: 16px; }
        .brand-wordmark { font-family: var(--font-display); font-size: 24px; font-weight: 900; letter-spacing: -0.5px; margin-bottom: 2px; }
        .brand-wordmark em { font-style: normal; color: var(--blue-light); }
        .brand-address { font-size: 10px; color: var(--gray-400); line-height: 1.5; }
        
        .inv-block { text-align: right; }
        .inv-eyebrow { font-family: var(--font-mono); font-size: 8px; letter-spacing: 3px; text-transform: uppercase; color: var(--blue-light); margin-bottom: 2px; }
        .inv-num { font-family: var(--font-display); font-size: 26px; font-weight: 900; line-height: 1; margin-bottom: 6px; text-shadow: 0 0 20px var(--blue-glow); }
        .inv-dates { font-size: 10px; color: var(--gray-400); line-height: 1.5; }
        .status-pill { display: inline-block; margin-top: 6px; padding: 4px 10px; border-radius: 12px; font-size: 8px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; border: 1px solid; }

        .hero-meta-strip { display: grid; grid-template-columns: repeat(4, 1fr); border-top: 1px solid var(--border); }
        .hms-cell { padding: 12px 0; text-align: center; border-right: 1px solid var(--border); }
        .hms-cell:last-child { border-right: none; }
        .hms-label { font-family: var(--font-mono); font-size: 7px; letter-spacing: 1.5px; text-transform: uppercase; color: var(--blue-light); display: block; margin-bottom: 2px; }
        .hms-value { font-size: 11px; font-weight: 600; }

        /* ══════════════════════════════════════
           ADDRESSES
        ══════════════════════════════════════ */
        .address-row { display: grid; grid-template-columns: 1fr 1fr; border-bottom: 1px solid var(--border); }
        .addr-block { padding: 16px var(--pad-x); border-right: 1px solid var(--border); }
        .addr-block:last-child { border-right: none; }
        .addr-label { font-family: var(--font-mono); font-size: 8px; letter-spacing: 2px; text-transform: uppercase; color: var(--blue-light); margin-bottom: 8px; display: flex; align-items: center; gap: 8px; }
        .addr-label::after { content: ''; flex: 1; height: 1px; background: linear-gradient(90deg, var(--blue), transparent); opacity: 0.3; }
        .addr-name { font-family: var(--font-display); font-size: 14px; font-weight: 700; margin-bottom: 4px; }
        .addr-detail { font-size: 11px; color: var(--gray-400); line-height: 1.6; }

        /* ══════════════════════════════════════
           ITEMS (TABEL LEBIH RAPAT)
        ══════════════════════════════════════ */
        .items-section { padding: 0 var(--pad-x); }
        .section-heading { font-family: var(--font-mono); font-size: 8px; letter-spacing: 2px; text-transform: uppercase; color: var(--blue-light); padding: 16px 0 12px; display: flex; align-items: center; gap: 8px; }
        .section-heading::after { content: ''; flex: 1; height: 1px; background: linear-gradient(90deg, var(--border-mid), transparent); }
        
        .items-table { width: 100%; border-collapse: collapse; table-layout: fixed; margin-bottom: 12px;}
        .items-table thead tr { background: var(--bg-deep); }
        .items-table th { padding: 8px 10px; font-family: var(--font-mono); font-size: 8px; letter-spacing: 1px; text-transform: uppercase; color: var(--blue-light); font-weight: 500; text-align: left; }
        .items-table th:first-child { width: 30px; text-align: center; border-radius: 6px 0 0 6px; }
        .items-table th:nth-child(3) { width: 100px; }
        .items-table th:nth-child(4) { width: 40px; text-align: center; }
        .items-table th:last-child { width: 110px; text-align: right; border-radius: 0 6px 6px 0; }
        
        .items-table td { padding: 8px 10px; border-bottom: 1px solid var(--border); font-size: 11px; vertical-align: middle; }
        .items-table tr:last-child td { border-bottom: none; }
        .items-table td:first-child { text-align: center; color: var(--gray-600); font-family: var(--font-mono); font-size: 9px;}
        .items-table td:last-child { text-align: right; font-weight: 700; font-family: var(--font-display); font-size: 12px;}
        
        .product-cell { display: flex; align-items: center; gap: 8px; }
        .product-img-wrap { width: 32px; height: 32px; border-radius: 6px; background: var(--bg-deep); border: 1px solid rgba(37,99,235,0.2); overflow: hidden; flex-shrink: 0; display: flex; align-items: center; justify-content: center; }
        .product-img-wrap img { width: 100%; height: 100%; object-fit: cover; }
        .product-name { font-family: var(--font-display); font-size: 12px; font-weight: 600; margin-bottom: 1px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .product-series { font-size: 9px; color: var(--gray-500); font-family: var(--font-mono); }
        .td-qty { text-align: center; font-weight: 700; color: var(--white); }

        /* ══════════════════════════════════════
           BOTTOM: BARCODE + TOTALS
        ══════════════════════════════════════ */
        .bottom-section { display: flex; border-top: 1px solid var(--border); }
        .barcode-col { padding: 20px var(--pad-x); border-right: 1px solid var(--border); display: flex; flex-direction: column; align-items: center; justify-content: center; min-width: 160px; }
        .barcode-label { font-family: var(--font-mono); font-size: 7px; letter-spacing: 2px; text-transform: uppercase; color: var(--blue-light); margin-bottom: 8px; }
        #barcode { max-width: 130px; }
        
        .totals-col { flex: 1; padding: 20px var(--pad-x); }
        .totals-row { display: flex; justify-content: space-between; padding: 6px 0; font-size: 11px; border-bottom: 1px solid var(--border); }
        .totals-row:last-of-type { border-bottom: none; }
        .tr-label { color: var(--gray-400); }
        .tr-value { font-weight: 600; color: var(--white); }
        .tr-value.free { color: #4ade80; font-weight: 700; }
        
        .grand-total-box { margin-top: 12px; background: var(--blue-dim); border: 1px solid rgba(37,99,235,0.3); border-radius: 8px; padding: 12px 16px; display: flex; justify-content: space-between; align-items: center; }
        .gt-label { font-family: var(--font-mono); font-size: 8px; letter-spacing: 2px; text-transform: uppercase; color: var(--blue-light); }
        .gt-value { font-family: var(--font-display); font-size: 20px; font-weight: 900; color: var(--white); text-shadow: 0 0 20px var(--blue-glow); }

        /* ══════════════════════════════════════
           PAYMENT STRIP (DIPADATKAN)
        ══════════════════════════════════════ */
        .payment-strip { margin: 0 var(--pad-x) 24px; background: var(--bg-surface); border: 1px solid var(--border-mid); border-radius: 8px; padding: 12px 16px; display: flex; align-items: center; justify-content: space-between; }
        .payment-left { display: flex; align-items: center; gap: 12px; }
        .payment-icon-box { width: 32px; height: 32px; background: var(--blue-dim); border: 1px solid rgba(37,99,235,0.3); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 14px; }
        .pi-label { font-family: var(--font-mono); font-size: 7px; letter-spacing: 2px; text-transform: uppercase; color: var(--blue-light); margin-bottom: 2px; }
        .pi-method { font-weight: 700; font-size: 11px; color: var(--white); }
        .pi-note { font-size: 9px; color: var(--gray-500); }
        
        .payment-status { display: flex; align-items: center; gap: 6px; font-size: 11px; font-weight: 700; }
        .payment-status-dot { width: 6px; height: 6px; border-radius: 50%; }

        /* ══════════════════════════════════════
           FOOTER (MINIMALIS)
        ══════════════════════════════════════ */
        .thank-you-section { background: var(--bg-deep); padding: 20px var(--pad-x); text-align: center; border-top: 1px solid var(--border-mid); }
        .ty-headline { font-family: var(--font-display); font-size: 16px; font-weight: 700; font-style: italic; color: var(--white); margin-bottom: 4px; }
        .ty-sub { font-size: 10px; color: var(--gray-400); margin-bottom: 12px; }
        .ty-footer-info { display: flex; justify-content: center; gap: 24px; flex-wrap: wrap; }
        .ty-info-item { display: flex; flex-direction: column; align-items: center; gap: 2px; }
        .ty-info-label { font-family: var(--font-mono); font-size: 6.5px; letter-spacing: 1px; text-transform: uppercase; color: var(--blue-light); }
        .ty-info-value { font-size: 10px; color: var(--gray-300); }

        @media print {
            body { background: #fff; color: #000; }
            .print-bar { display: none; }
            .page-wrap { margin: 0; padding: 0; max-width: 100%; }
            .invoice-card { border-radius: 0; box-shadow: none; border: none; }
            .invoice-hero { background: #f8fafc; border-bottom: 2px solid #000; }
            .brand-wordmark, .inv-num, .hms-value, .addr-name, .product-name, .gt-value, .pi-method { color: #000; text-shadow: none; }
            .brand-address, .inv-dates, .addr-detail, .tr-label, .pi-note, .ty-sub, .ty-info-value { color: #475569; }
            .hms-label, .inv-eyebrow, .addr-label, .section-heading, .items-table th, .gt-label, .pi-label, .ty-info-label { color: #64748b; }
            .items-table tr { border-bottom-color: #e2e8f0; }
            .bottom-section, .hero-meta-strip, .address-row, .addr-block { border-color: #e2e8f0; }
            .grand-total-box, .payment-strip, .thank-you-section { background: #f8fafc; border-color: #e2e8f0; }
            * { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        }
    </style>
</head>

<body>

    {{-- PRINT BAR --}}
    <div class="print-bar">
        <div class="print-bar-left">
            <span class="print-bar-logo">Nex<em>Rig</em></span>
            <span class="print-bar-label" style="margin-left:12px; border-left:1px solid #333; padding-left:12px;">INV-#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
        </div>
        <div class="print-bar-actions">
            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-ghost">Back</a>
            <button class="btn btn-blue" onclick="window.print()">Print</button>
        </div>
    </div>

    <div class="page-wrap">
        <div class="invoice-card">

            {{-- HERO --}}
            <div class="invoice-hero">
                <div class="hero-top">
                    <div>
                        <div class="brand-wordmark">Nex<em>Rig</em></div>
                        <div class="brand-address">
                            Jl. Pemuda No. 123, Semarang<br>
                            support@NexRig.id · +62 812-3456-7890
                        </div>
                    </div>
                    <div class="inv-block">
                        <div class="inv-eyebrow">Official Invoice</div>
                        <div class="inv-num">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</div>
                        <div class="inv-dates">
                            {{ $order->created_at->format('d F Y, H:i') }} WIB
                        </div>
                    </div>
                </div>

                <div class="hero-meta-strip">
                    <div class="hms-cell"><span class="hms-label">Order ID</span><span class="hms-value">#{{ $order->id }}</span></div>
                    <div class="hms-cell"><span class="hms-label">Date</span><span class="hms-value">{{ $order->created_at->format('d/m/Y') }}</span></div>
                    <div class="hms-cell"><span class="hms-label">Items</span><span class="hms-value">{{ $order->items->count() }} pcs</span></div>
                    <div class="hms-cell"><span class="hms-label">Status</span><span class="hms-value">{{ ucfirst($order->status) }}</span></div>
                </div>
            </div>

            {{-- ADDRESSES --}}
            <div class="address-row">
                <div class="addr-block">
                    <div class="addr-label">From</div>
                    <div class="addr-name">NexRig Indonesia</div>
                    <div class="addr-detail">Jl. Pemuda No. 123, Semarang<br>Jawa Tengah 50132</div>
                </div>
                <div class="addr-block">
                    <div class="addr-label">Ship To</div>
                    <div class="addr-name">{{ $order->shipping_name ?? $order->user->name }}</div>
                    <div class="addr-detail">
                        {{ $order->shipping_address ?? 'Address not provided' }}<br>
                        @if($order->shipping_city){{ $order->shipping_city }}{{ $order->shipping_postal_code ? ', ' . $order->shipping_postal_code : '' }}<br>@endif
                        @if($order->shipping_phone){{ $order->shipping_phone }}@endif
                    </div>
                </div>
            </div>

            {{-- ITEMS --}}
            <div class="items-section">
                <div class="section-heading">Items Ordered</div>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $index => $item)
                        @php $img = $item->product->images->where('is_primary', true)->first() ?? $item->product->images->first(); @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <div class="product-cell">
                                    <div class="product-img-wrap">
                                        @if($img)<img src="{{ $img->src }}" alt="{{ $item->product->name }}">
                                        @else <span style="font-size:12px;opacity:.3;">📦</span> @endif
                                    </div>
                                    <div style="min-width:0;">
                                        <div class="product-name">{{ $item->product->name }}</div>
                                        <div class="product-series">{{ $item->product->series->name ?? 'Component' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="color: var(--gray-400);">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="td-qty">{{ $item->quantity }}</td>
                            <td>Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- BARCODE + TOTALS --}}
            @php
            $taxAmount = $order->total_price / 1.11;
            $subtotal = $order->total_price - $taxAmount;
            $barcodeVal = 'INV' . str_pad($order->id, 6, '0', STR_PAD_LEFT);
            @endphp
            <div class="bottom-section">
                <div class="barcode-col">
                    <div class="barcode-label">Scan Barcode</div>
                    <svg id="barcode"></svg>
                </div>
                <div class="totals-col">
                    <div class="totals-row"><span class="tr-label">Subtotal</span><span class="tr-value">Rp {{ number_format($subtotal, 0, ',', '.') }}</span></div>
                    <div class="totals-row"><span class="tr-label">Shipping</span><span class="tr-value free">FREE</span></div>
                    <div class="totals-row"><span class="tr-label">PPN (11%)</span><span class="tr-value">Rp {{ number_format($taxAmount, 0, ',', '.') }}</span></div>
                    <div class="grand-total-box">
                        <span class="gt-label">Grand Total</span>
                        <span class="gt-value">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            {{-- PAYMENT STRIP --}}
            <div class="payment-strip">
                <div class="payment-left">
                    <div class="payment-icon-box">🏦</div>
                    <div>
                        <div class="pi-label">Payment Method</div>
                        <div class="pi-method">{{ strtoupper(str_replace('_', ' ', $order->payment_type ?? 'Midtrans Gateway')) }}</div>
                        <div class="pi-note">Auto Verification via Midtrans</div>
                    </div>
                </div>
                
                @php
                    $payStatusText = ''; $payStatusColor = ''; $dotGlow = '';
                    if ($order->status === 'pending') {
                        $payStatusText = 'BELUM DIBAYAR'; $payStatusColor = '#fcd34d'; $dotGlow = 'rgba(252, 211, 77, 0.4)';
                    } elseif (in_array($order->status, ['processing', 'shipped', 'completed'])) {
                        $payStatusText = 'LUNAS'; $payStatusColor = '#4ade80'; $dotGlow = 'rgba(74, 222, 128, 0.4)';
                    } else {
                        $payStatusText = 'DIBATALKAN'; $payStatusColor = '#f87171'; $dotGlow = 'rgba(248, 113, 113, 0.4)';
                    }
                @endphp
                <div class="payment-status" style="color: {{ $payStatusColor }};">
                    <div class="payment-status-dot" style="background: {{ $payStatusColor }}; box-shadow: 0 0 12px {{ $dotGlow }};"></div>
                    {{ $payStatusText }}
                </div>
            </div>

            {{-- FOOTER --}}
            <div class="thank-you-section">
                <div class="ty-headline">Thank you for your order.</div>
                <div class="ty-sub">Your order is being handled with the utmost care by NexRig.</div>
                <div class="ty-footer-info">
                    <div class="ty-info-item"><span class="ty-info-label">Website</span><span class="ty-info-value">NexRig.id</span></div>
                    <div class="ty-info-item"><span class="ty-info-label">Email</span><span class="ty-info-value">support@NexRig.id</span></div>
                    <div class="ty-info-item"><span class="ty-info-label">Phone</span><span class="ty-info-value">+62 895-0709-4710</span></div>
                </div>
            </div>

        </div>
    </div>

    <script>
        JsBarcode("#barcode", "{{ $barcodeVal }}", {
            format: "CODE128", lineColor: "#e2e8f0", background: "transparent",
            width: 1.2, height: 40, displayValue: true, fontSize: 10,
            fontOptions: "bold", font: "DM Mono, monospace", textMargin: 4,
        });
    </script>
</body>
</html>