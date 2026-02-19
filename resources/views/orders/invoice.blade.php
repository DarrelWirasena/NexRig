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
           TOKENS
        ══════════════════════════════════════ */
        :root {
            --bg-base:    #050505;
            --bg-card:    #0a0a0a;
            --bg-deep:    #050014;
            --bg-surface: #111111;

            --blue:       #2563eb;
            --blue-light: #3b82f6;
            --blue-dim:   rgba(37,99,235,0.15);
            --blue-glow:  rgba(37,99,235,0.4);

            --white:      #ffffff;
            --gray-300:   #cbd5e1;
            --gray-400:   #94a3b8;
            --gray-500:   #64748b;
            --gray-600:   #475569;
            --border:     rgba(255,255,255,0.07);
            --border-mid: rgba(255,255,255,0.10);

            --font-display: 'Playfair Display', Georgia, serif;
            --font-body:    'DM Sans', sans-serif;
            --font-mono:    'DM Mono', 'Courier New', monospace;

            --pad-x: 56px;
        }

        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: var(--font-body);
            background: var(--bg-base);
            color: var(--white);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        /* Hide all scrollbars — page still scrollable, bars invisible */
        * { scrollbar-width: none; -ms-overflow-style: none; }
        *::-webkit-scrollbar { display: none; }

        /* ══════════════════════════════════════
           PRINT BAR
        ══════════════════════════════════════ */
        .print-bar {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(5,5,5,0.95);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-mid);
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }
        .print-bar-left    { display: flex; align-items: center; gap: 12px; min-width: 0; }
        .print-bar-logo    { font-family: var(--font-display); font-size: 17px; font-weight: 700; color: var(--white); flex-shrink: 0; }
        .print-bar-logo em { font-style: normal; color: var(--blue-light); }
        .print-bar-divider { width: 1px; height: 18px; background: var(--border-mid); flex-shrink: 0; }
        .print-bar-label   { font-family: var(--font-mono); font-size: 11px; color: var(--gray-400); letter-spacing: 1px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        @media (max-width: 400px) { .print-bar-label, .print-bar-divider { display: none; } }

        .print-bar-actions { display: flex; gap: 8px; flex-shrink: 0; }

        .btn {
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all .2s;
            white-space: nowrap;
        }
        .btn-ghost { background: transparent; border: 1px solid var(--border-mid); color: var(--gray-300); }
        .btn-ghost:hover { border-color: var(--blue); color: var(--white); }
        .btn-blue  { background: var(--blue); color: #fff; font-weight: 700; }
        .btn-blue:hover { background: var(--blue-light); transform: translateY(-1px); box-shadow: 0 4px 20px var(--blue-glow); }
        .btn-back-text { display: inline; }
        @media (max-width: 480px) { .btn-back-text { display: none; } }

        /* ══════════════════════════════════════
           PAGE + CARD
        ══════════════════════════════════════ */
        .page-wrap {
            max-width: 900px;
            margin: 32px auto 60px;
            padding: 0 16px;
        }
        @media (min-width: 640px) { .page-wrap { padding: 0 24px; margin: 48px auto 80px; } }

        .invoice-card {
            background: var(--bg-card);
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid var(--border-mid);
            box-shadow: 0 0 0 1px rgba(37,99,235,0.12), 0 24px 80px rgba(0,0,0,0.6);
            animation: fadeUp .55s cubic-bezier(.16,1,.3,1) both;
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ══════════════════════════════════════
           HERO
        ══════════════════════════════════════ */
        .invoice-hero {
            background: var(--bg-deep);
            padding: 36px 24px 0;
            position: relative;
            overflow: hidden;
        }
        @media (min-width: 640px) { .invoice-hero { padding: 52px var(--pad-x) 0; } }

        .invoice-hero::before {
            content: '';
            position: absolute;
            top: -80px; right: -80px;
            width: 260px; height: 260px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(37,99,235,0.22) 0%, transparent 70%);
            pointer-events: none;
        }
        .invoice-hero::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--blue), var(--blue-light), var(--blue), transparent);
        }

        .hero-top {
            display: flex;
            flex-direction: column;
            gap: 24px;
            margin-bottom: 32px;
        }
        @media (min-width: 600px) {
            .hero-top { flex-direction: row; justify-content: space-between; align-items: flex-start; margin-bottom: 40px; }
        }

        .brand-wordmark {
            font-family: var(--font-display);
            font-size: 26px;
            font-weight: 900;
            color: var(--white);
            letter-spacing: -0.5px;
            margin-bottom: 6px;
        }
        @media (min-width: 640px) { .brand-wordmark { font-size: 32px; } }
        .brand-wordmark em { font-style: normal; color: var(--blue-light); }
        .brand-address { font-size: 11px; color: var(--gray-400); line-height: 1.7; font-weight: 300; }

        .inv-block { text-align: left; }
        @media (min-width: 600px) { .inv-block { text-align: right; } }
        .inv-eyebrow { font-family: var(--font-mono); font-size: 9px; letter-spacing: 4px; text-transform: uppercase; color: var(--blue-light); margin-bottom: 4px; }
        .inv-num {
            font-family: var(--font-display);
            font-size: 32px;
            font-weight: 900;
            color: var(--white);
            line-height: 1;
            margin-bottom: 10px;
            text-shadow: 0 0 40px var(--blue-glow);
        }
        @media (min-width: 640px) { .inv-num { font-size: 40px; } }
        .inv-dates { font-size: 11px; color: var(--gray-400); line-height: 1.8; font-weight: 300; }

        .status-pill {
            display: inline-block;
            margin-top: 10px;
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            border: 1px solid;
        }

        .hero-meta-strip {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            border-top: 1px solid var(--border);
        }
        @media (min-width: 540px) { .hero-meta-strip { grid-template-columns: repeat(4, 1fr); } }

        .hms-cell {
            padding: 14px 0;
            text-align: center;
            border-right: 1px solid var(--border);
        }
        .hms-cell:nth-child(2) { border-right: none; }
        .hms-cell:nth-child(3), .hms-cell:nth-child(4) { border-top: 1px solid var(--border); }
        @media (min-width: 540px) {
            .hms-cell { padding: 18px 0; }
            .hms-cell:nth-child(2) { border-right: 1px solid var(--border); }
            .hms-cell:nth-child(3), .hms-cell:nth-child(4) { border-top: none; }
            .hms-cell:last-child { border-right: none; }
        }
        .hms-label { font-family: var(--font-mono); font-size: 7.5px; letter-spacing: 2px; text-transform: uppercase; color: var(--blue-light); display: block; margin-bottom: 4px; }
        .hms-value { font-size: 12px; font-weight: 600; color: var(--white); }

        /* ══════════════════════════════════════
           ADDRESSES
        ══════════════════════════════════════ */
        .address-row {
            display: grid;
            grid-template-columns: 1fr;
            border-bottom: 1px solid var(--border);
        }
        @media (min-width: 560px) { .address-row { grid-template-columns: 1fr 1fr; } }

        .addr-block { padding: 24px; }
        @media (min-width: 640px) { .addr-block { padding: 32px var(--pad-x); } }
        .addr-block:first-child { border-bottom: 1px solid var(--border); }
        @media (min-width: 560px) { .addr-block:first-child { border-bottom: none; border-right: 1px solid var(--border); } }

        .addr-label {
            font-family: var(--font-mono);
            font-size: 8.5px;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--blue-light);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .addr-label::after { content: ''; flex: 1; height: 1px; background: linear-gradient(90deg, var(--blue), transparent); opacity: 0.3; }
        .addr-name { font-family: var(--font-display); font-size: 15px; font-weight: 700; color: var(--white); margin-bottom: 7px; }
        .addr-detail { font-size: 12px; color: var(--gray-400); line-height: 1.85; }

        /* ══════════════════════════════════════
           ITEMS
        ══════════════════════════════════════ */
        .items-section { padding: 0 16px 8px; }
        @media (min-width: 640px) { .items-section { padding: 0 var(--pad-x) 8px; } }

        .section-heading {
            font-family: var(--font-mono);
            font-size: 8.5px;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--blue-light);
            padding: 24px 0 16px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .section-heading::after { content: ''; flex: 1; height: 1px; background: linear-gradient(90deg, var(--border-mid), transparent); }

        /* Table — no scroll, fully fluid */
        .items-table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        .items-table thead tr { background: var(--bg-deep); }
        .items-table thead th {
            padding: 11px 10px;
            font-family: var(--font-mono);
            font-size: 8px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--blue-light);
            font-weight: 500;
            text-align: left;
            overflow: hidden;
        }
        .items-table thead th:first-child { border-radius: 8px 0 0 8px; width: 32px; text-align: center; padding-left: 12px; }
        .items-table thead th:nth-child(3) { width: 110px; }  /* unit price */
        .items-table thead th:nth-child(4) { width: 44px; text-align: center; }  /* qty */
        .items-table thead th:last-child   { border-radius: 0 8px 8px 0; text-align: right; width: 120px; padding-right: 12px; }

        .items-table tbody tr { border-bottom: 1px solid var(--border); transition: background .15s; }
        .items-table tbody tr:last-child { border-bottom: none; }
        .items-table tbody tr:hover { background: rgba(37,99,235,0.04); }
        .items-table tbody td { padding: 14px 10px; vertical-align: middle; overflow: hidden; }
        .items-table tbody td:first-child { padding-left: 12px; }
        .items-table tbody td:last-child  { padding-right: 12px; text-align: right; }

        .product-cell { display: flex; align-items: center; gap: 10px; }
        .product-img-wrap {
            width: 46px; height: 46px;
            border-radius: 8px;
            background: var(--bg-deep);
            border: 1px solid rgba(37,99,235,0.2);
            overflow: hidden;
            flex-shrink: 0;
            display: flex; align-items: center; justify-content: center;
        }
        .product-img-wrap img { width: 100%; height: 100%; object-fit: cover; }
        .product-img-placeholder { font-size: 18px; opacity: 0.3; }
        .product-name { font-family: var(--font-display); font-size: 13px; font-weight: 600; color: var(--white); margin-bottom: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .product-series { font-size: 10px; color: var(--gray-500); font-family: var(--font-mono); }

        .td-no    { font-family: var(--font-mono); font-size: 10px; color: var(--gray-600); text-align: center; }
        .td-price { font-size: 12px; color: var(--gray-400); white-space: nowrap; }
        .td-qty   { text-align: center; font-weight: 700; font-size: 13px; color: var(--white); }
        .td-total { font-family: var(--font-display); font-size: 14px; font-weight: 700; color: var(--white); white-space: nowrap; }

        /* Hide unit price column on small screens */
        @media (max-width: 480px) {
            .items-table thead th:nth-child(3),
            .items-table tbody td:nth-child(3) { display: none; }
        }

        /* Mobile item cards */
        .items-mobile { display: none; }
        @media (max-width: 400px) {
            .items-table  { display: none; }
            .items-mobile { display: flex; flex-direction: column; gap: 8px; }
        }
        .item-card { background: var(--bg-surface); border: 1px solid var(--border); border-radius: 10px; padding: 14px; }
        .item-card-top { display: flex; align-items: center; gap: 12px; margin-bottom: 10px; }
        .item-card-img { width: 44px; height: 44px; border-radius: 8px; background: var(--bg-deep); border: 1px solid rgba(37,99,235,0.2); overflow: hidden; flex-shrink: 0; display: flex; align-items: center; justify-content: center; }
        .item-card-img img { width: 100%; height: 100%; object-fit: cover; }
        .item-card-name   { font-family: var(--font-display); font-size: 13px; font-weight: 700; color: var(--white); margin-bottom: 2px; }
        .item-card-series { font-size: 10px; color: var(--gray-500); font-family: var(--font-mono); }
        .item-card-row    { display: flex; justify-content: space-between; align-items: center; padding: 7px 0; border-top: 1px solid var(--border); font-size: 12px; }
        .item-card-label  { color: var(--gray-400); }
        .item-card-value  { color: var(--white); font-weight: 600; }
        .item-card-total  { font-family: var(--font-display); font-size: 15px; font-weight: 700; color: var(--white); }

        /* ══════════════════════════════════════
           BOTTOM: BARCODE + TOTALS
        ══════════════════════════════════════ */
        .bottom-section {
            display: flex;
            flex-direction: column;
            gap: 28px;
            padding: 28px 16px 32px;
            border-top: 1px solid var(--border);
        }
        @media (min-width: 620px) {
            .bottom-section {
                display: grid;
                grid-template-columns: auto 1fr;
                gap: 0;
                padding: 36px var(--pad-x) 40px;
                align-items: start;
            }
        }

        /* Barcode column */
        .barcode-col {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            padding-bottom: 24px;
            border-bottom: 1px solid var(--border);
        }
        @media (min-width: 620px) {
            .barcode-col {
                padding-bottom: 0;
                border-bottom: none;
                padding-right: 40px;
                border-right: 1px solid var(--border);
                min-width: 175px;
            }
        }
        .barcode-label {
            font-family: var(--font-mono);
            font-size: 7.5px;
            letter-spacing: 2.5px;
            text-transform: uppercase;
            color: var(--blue-light);
            text-align: center;
        }
        #barcode { max-width: 160px; display: block; }

        /* Totals column */
        .totals-col { width: 100%; }
        @media (min-width: 620px) { .totals-col { padding-left: 40px; } }

        .totals-heading {
            font-family: var(--font-mono);
            font-size: 8.5px;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: var(--blue-light);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .totals-heading::after { content: ''; flex: 1; height: 1px; background: linear-gradient(90deg, var(--border-mid), transparent); }

        .totals-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 9px 0;
            border-bottom: 1px solid var(--border);
            font-size: 13px;
        }
        .totals-row:last-of-type { border-bottom: none; }
        .tr-label { color: var(--gray-400); }
        .tr-value { font-weight: 600; color: var(--white); }
        .tr-value.free { color: #4ade80; font-weight: 700; }

        .grand-total-box {
            margin-top: 16px;
            background: var(--blue-dim);
            border: 1px solid rgba(37,99,235,0.3);
            border-radius: 12px;
            padding: 18px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            overflow: hidden;
        }
        .grand-total-box::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--blue-light), transparent);
        }
        .gt-label { font-family: var(--font-mono); font-size: 9px; letter-spacing: 3px; text-transform: uppercase; color: var(--blue-light); }
        .gt-value { font-family: var(--font-display); font-size: clamp(20px, 5vw, 26px); font-weight: 900; color: var(--white); text-shadow: 0 0 30px var(--blue-glow); }

        /* ══════════════════════════════════════
           PAYMENT STRIP
        ══════════════════════════════════════ */
        .payment-strip {
            margin: 0 16px 32px;
            background: var(--bg-surface);
            border: 1px solid var(--border-mid);
            border-radius: 12px;
            padding: 16px 20px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        @media (min-width: 560px) {
            .payment-strip {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                margin: 0 var(--pad-x) 40px;
                padding: 20px 28px;
            }
        }
        .payment-left { display: flex; align-items: center; gap: 14px; }
        .payment-icon-box {
            width: 40px; height: 40px;
            background: var(--blue-dim);
            border: 1px solid rgba(37,99,235,0.3);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 17px;
            flex-shrink: 0;
        }
        .pi-label  { font-family: var(--font-mono); font-size: 8px; letter-spacing: 2.5px; text-transform: uppercase; color: var(--blue-light); margin-bottom: 3px; }
        .pi-method { font-weight: 700; font-size: 13px; color: var(--white); }
        .pi-note   { font-size: 11px; color: var(--gray-500); margin-top: 2px; }

        .payment-status { display: flex; align-items: center; gap: 8px; font-size: 12px; font-weight: 700; color: var(--gray-300); }
        .payment-status-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: var(--blue-light);
            box-shadow: 0 0 8px var(--blue-glow);
            animation: pulse 2s infinite;
            flex-shrink: 0;
        }
        @keyframes pulse {
            0%, 100% { box-shadow: 0 0 8px var(--blue-glow); }
            50%       { box-shadow: 0 0 16px var(--blue-glow), 0 0 24px rgba(37,99,235,0.2); }
        }

        /* ══════════════════════════════════════
           THANK YOU FOOTER
        ══════════════════════════════════════ */
        .thank-you-section {
            background: var(--bg-deep);
            padding: 40px 24px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        @media (min-width: 640px) { .thank-you-section { padding: 52px var(--pad-x); } }
        .thank-you-section::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--blue), var(--blue-light), var(--blue), transparent);
        }
        .thank-you-section::after {
            content: '';
            position: absolute;
            bottom: -60px; left: 50%;
            transform: translateX(-50%);
            width: 300px; height: 180px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(37,99,235,0.12) 0%, transparent 70%);
            pointer-events: none;
        }
        .ty-bg-text {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: var(--font-display);
            font-size: clamp(48px, 12vw, 90px);
            font-weight: 900;
            color: rgba(37,99,235,0.05);
            pointer-events: none;
            white-space: nowrap;
        }
        .ty-content  { position: relative; z-index: 1; }
        .ty-headline { font-family: var(--font-display); font-size: clamp(20px, 5vw, 28px); font-weight: 700; font-style: italic; color: var(--white); margin-bottom: 10px; }
        .ty-sub      { font-size: 13px; color: var(--gray-400); margin-bottom: 24px; line-height: 1.7; }
        .ty-divider  { width: 48px; height: 1px; background: linear-gradient(90deg, transparent, var(--blue-light), transparent); margin: 18px auto; }

        .ty-footer-info { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px 24px; max-width: 320px; margin: 0 auto; }
        @media (min-width: 480px) { .ty-footer-info { display: flex; justify-content: center; gap: 32px; flex-wrap: wrap; max-width: none; } }
        .ty-info-item  { display: flex; flex-direction: column; align-items: center; gap: 3px; }
        .ty-info-label { font-family: var(--font-mono); font-size: 7.5px; letter-spacing: 2px; text-transform: uppercase; color: var(--blue-light); }
        .ty-info-value { font-size: 12px; color: var(--gray-300); }

        /* ══════════════════════════════════════
           PRINT
        ══════════════════════════════════════ */
        @media print {
            body { background: #000; }
            .print-bar { display: none; }
            .page-wrap { margin: 0; padding: 0; max-width: 100%; }
            .invoice-card { border-radius: 0; box-shadow: none; animation: none; }
            .items-mobile { display: none !important; }
            .items-table  { display: table !important; }
        }
    </style>
</head>
<body>

{{-- ════════ PRINT BAR ════════ --}}
<div class="print-bar">
    <div class="print-bar-left">
        <span class="print-bar-logo">Nex<em>Rig</em></span>
        <div class="print-bar-divider"></div>
        <span class="print-bar-label">INV-#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
    </div>
    <div class="print-bar-actions">
        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-ghost">
            ← <span class="btn-back-text">Back</span>
        </a>
        <button class="btn btn-blue" onclick="window.print()">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <polyline points="6 9 6 2 18 2 18 9"/>
                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/>
                <rect x="6" y="14" width="12" height="8"/>
            </svg>
            Print
        </button>
    </div>
</div>

{{-- ════════ INVOICE ════════ --}}
<div class="page-wrap">
<div class="invoice-card">

    {{-- HERO --}}
    <div class="invoice-hero">
        <div class="hero-top">
            <div>
                <div class="brand-wordmark">Nex<em>Rig</em></div>
                <div class="brand-address">
                    Jl. Pemuda No. 123, Semarang<br>
                    Jawa Tengah 50132, Indonesia<br>
                    support@NexRig.id · +62 812-3456-7890
                </div>
            </div>
            <div class="inv-block">
                <div class="inv-eyebrow">Official Invoice</div>
                <div class="inv-num">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</div>
                <div class="inv-dates">
                    Issued: {{ $order->created_at->format('d F Y') }}<br>
                    Time: {{ $order->created_at->format('H:i') }} WIB
                </div>
                @php
                    $statusDef = match($order->status) {
                        'pending'    => ['label' => 'Pending',    'bg' => 'rgba(245,158,11,0.15)',  'color' => '#fcd34d', 'border' => 'rgba(245,158,11,0.4)'],
                        'processing' => ['label' => 'Processing', 'bg' => 'rgba(37,99,235,0.15)',   'color' => '#93c5fd', 'border' => 'rgba(59,130,246,0.4)'],
                        'shipped'    => ['label' => 'Shipped',    'bg' => 'rgba(168,85,247,0.15)',  'color' => '#d8b4fe', 'border' => 'rgba(168,85,247,0.4)'],
                        'completed'  => ['label' => 'Completed',  'bg' => 'rgba(34,197,94,0.15)',   'color' => '#86efac', 'border' => 'rgba(34,197,94,0.4)'],
                        'cancelled'  => ['label' => 'Cancelled',  'bg' => 'rgba(239,68,68,0.15)',   'color' => '#fca5a5', 'border' => 'rgba(239,68,68,0.4)'],
                        default      => ['label' => ucfirst($order->status), 'bg' => 'rgba(100,100,100,0.15)', 'color' => '#aaa', 'border' => 'rgba(100,100,100,0.3)'],
                    };
                @endphp
                <div class="status-pill" style="background:{{ $statusDef['bg'] }};color:{{ $statusDef['color'] }};border-color:{{ $statusDef['border'] }};">
                    {{ strtoupper($statusDef['label']) }}
                </div>
            </div>
        </div>

        <div class="hero-meta-strip">
            <div class="hms-cell">
                <span class="hms-label">Order ID</span>
                <span class="hms-value">#{{ $order->id }}</span>
            </div>
            <div class="hms-cell">
                <span class="hms-label">Date</span>
                <span class="hms-value">{{ $order->created_at->format('d/m/Y') }}</span>
            </div>
            <div class="hms-cell">
                <span class="hms-label">Items</span>
                <span class="hms-value">{{ $order->items->count() }} pcs</span>
            </div>
            <div class="hms-cell">
                <span class="hms-label">Payment</span>
                <span class="hms-value">Transfer</span>
            </div>
        </div>
    </div>

    {{-- ADDRESSES --}}
    <div class="address-row">
        <div class="addr-block">
            <div class="addr-label">From</div>
            <div class="addr-name">NexRig Indonesia</div>
            <div class="addr-detail">
                Jl. Pemuda No. 123, Semarang<br>
                Jawa Tengah 50132<br>
                support@NexRig.id<br>
                +62 812-3456-7890
            </div>
        </div>
        <div class="addr-block">
            <div class="addr-label">Ship To</div>
            <div class="addr-name">{{ $order->shipping_name ?? $order->user->name }}</div>
            <div class="addr-detail">
                {{ $order->shipping_address ?? 'Address not provided' }}<br>
                @if($order->shipping_city)
                    {{ $order->shipping_city }}{{ $order->shipping_postal_code ? ', ' . $order->shipping_postal_code : '' }}<br>
                @endif
                @if($order->shipping_phone){{ $order->shipping_phone }}@endif
            </div>
        </div>
    </div>

    {{-- ITEMS --}}
    <div class="items-section">
        <div class="section-heading">Items Ordered</div>

        {{-- Table (hidden on very small screens) --}}
        <table class="items-table">
            <thead>
                <tr>
                    <th style="text-align:center;">#</th>
                    <th>Product</th>
                    <th>Unit Price</th>
                    <th style="text-align:center;">Qty</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $index => $item)
                @php $img = $item->product->images->where('is_primary', true)->first() ?? $item->product->images->first(); @endphp
                <tr>
                    <td class="td-no">{{ $index + 1 }}</td>
                    <td>
                        <div class="product-cell">
                            <div class="product-img-wrap">
                                @if($img)
                                    <img src="{{ $img->src }}" alt="{{ $item->product->name }}">
                                @else
                                    <span class="product-img-placeholder">📦</span>
                                @endif
                            </div>
                            <div style="min-width:0;">
                                <div class="product-name">{{ $item->product->name }}</div>
                                <div class="product-series">{{ $item->product->series->name ?? 'Component' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="td-price">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td class="td-qty">{{ $item->quantity }}</td>
                    <td class="td-total">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Mobile cards (< 400px) --}}
        <div class="items-mobile">
            @foreach($order->items as $index => $item)
            @php $img = $item->product->images->where('is_primary', true)->first() ?? $item->product->images->first(); @endphp
            <div class="item-card">
                <div class="item-card-top">
                    <div class="item-card-img">
                        @if($img) <img src="{{ $img->src }}" alt="{{ $item->product->name }}">
                        @else <span style="font-size:18px;opacity:.3;">📦</span> @endif
                    </div>
                    <div>
                        <div class="item-card-name">{{ $item->product->name }}</div>
                        <div class="item-card-series">{{ $item->product->series->name ?? 'Component' }}</div>
                    </div>
                </div>
                <div class="item-card-row">
                    <span class="item-card-label">Unit Price</span>
                    <span class="item-card-value">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                </div>
                <div class="item-card-row">
                    <span class="item-card-label">Quantity</span>
                    <span class="item-card-value">{{ $item->quantity }} pcs</span>
                </div>
                <div class="item-card-row" style="border-top-color:rgba(37,99,235,0.25);">
                    <span class="item-card-label" style="font-weight:600;color:var(--gray-300);">Subtotal</span>
                    <span class="item-card-total">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- BARCODE + TOTALS --}}
    @php
        $taxAmount  = $order->total_price * 0.11;
        $subtotal   = $order->total_price - $taxAmount;
        $barcodeVal = 'INV' . str_pad($order->id, 6, '0', STR_PAD_LEFT);
    @endphp
    <div class="bottom-section">

        {{-- Barcode only --}}
        <div class="barcode-col">
            <div class="barcode-label">Invoice Barcode</div>
            <svg id="barcode"></svg>
        </div>

        {{-- Totals --}}
        <div class="totals-col">
            <div class="totals-heading">Order Summary</div>
            <div class="totals-row">
                <span class="tr-label">Subtotal ({{ $order->items->count() }} items)</span>
                <span class="tr-value">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
            </div>
            <div class="totals-row">
                <span class="tr-label">Shipping Cost</span>
                <span class="tr-value free">FREE</span>
            </div>
            <div class="totals-row">
                <span class="tr-label">PPN (11%)</span>
                <span class="tr-value">Rp {{ number_format($taxAmount, 0, ',', '.') }}</span>
            </div>
            <div class="grand-total-box">
                <span class="gt-label">Grand Total</span>
                <span class="gt-value">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    {{-- PAYMENT --}}
    <div class="payment-strip">
        <div class="payment-left">
            <div class="payment-icon-box">🏦</div>
            <div>
                <div class="pi-label">Payment Method</div>
                <div class="pi-method">Bank Transfer — Manual Verification</div>
                <div class="pi-note">Simpan bukti transfer sebagai bukti pembayaran.</div>
            </div>
        </div>
        <div class="payment-status">
            <div class="payment-status-dot"></div>
            {{ ucfirst($order->status) }}
        </div>
    </div>

    {{-- FOOTER --}}
    <div class="thank-you-section">
        <div class="ty-bg-text">THANK YOU</div>
        <div class="ty-content">
            <div class="ty-headline">Thank you for your order.</div>
            <div class="ty-sub">
                We appreciate your trust in NexRig.<br>
                Your order is being handled with the utmost care.
            </div>
            <div class="ty-divider"></div>
            <div class="ty-footer-info">
                <div class="ty-info-item">
                    <span class="ty-info-label">Website</span>
                    <span class="ty-info-value">NexRig.id</span>
                </div>
                <div class="ty-info-item">
                    <span class="ty-info-label">Email</span>
                    <span class="ty-info-value">support@NexRig.id</span>
                </div>
                <div class="ty-info-item">
                    <span class="ty-info-label">Phone</span>
                    <span class="ty-info-value">+62 812-3456-7890</span>
                </div>
                <div class="ty-info-item">
                    <span class="ty-info-label">Hours</span>
                    <span class="ty-info-value">Mon–Sat, 09:00–18:00</span>
                </div>
            </div>
        </div>
    </div>

</div>
</div>

<script>
    JsBarcode("#barcode", "{{ $barcodeVal }}", {
        format: "CODE128",
        lineColor: "#e2e8f0",
        background: "transparent",
        width: 1.6,
        height: 50,
        displayValue: true,
        fontSize: 10,
        fontOptions: "bold",
        font: "DM Mono, monospace",
        textMargin: 6,
    });
</script>
</body>
</html>