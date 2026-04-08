<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>NexRig - Order Manifest</title>
    <style>
        /* Standar reset untuk Email Client */
        body { background-color: #050505; color: #cbd5e1; font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; margin: 0; padding: 0; }
        table { border-collapse: collapse; width: 100%; }
        .wrapper { background-color: #050505; padding: 40px 10px; }
        .container { max-width: 600px; margin: 0 auto; border: 1px solid #1e293b; border-radius: 12px; overflow: hidden; background-color: #0a0a0a; }
        
        /* Header & Logo */
        .header { background: linear-gradient(to right, #1e1b4b, #050505); padding: 40px 30px; border-bottom: 1px solid #2563eb; text-align: center; }
        .logo { font-size: 28px; font-weight: 900; color: #2563eb; text-transform: uppercase; letter-spacing: 5px; margin: 0; }
        .status-badge { display: inline-block; background: rgba(37, 99, 235, 0.1); border: 1px solid rgba(37, 99, 235, 0.5); color: #60a5fa; font-size: 10px; font-weight: bold; padding: 5px 12px; border-radius: 4px; margin-top: 15px; letter-spacing: 2px; }

        /* Content */
        .content { padding: 40px 30px; }
        .greeting { font-size: 18px; color: #ffffff; margin-bottom: 10px; font-weight: bold; }
        .description { font-size: 14px; color: #94a3b8; line-height: 1.6; margin-bottom: 30px; }

        /* Product Table */
        .item-row { border-bottom: 1px solid #1e293b; }
        .item-img { width: 80px; height: 80px; border-radius: 8px; border: 1px solid #334155; }
        .item-details { padding: 20px 0; }
        .item-name { color: #ffffff; font-size: 14px; font-weight: bold; text-transform: uppercase; margin: 0; }
        .item-spec { color: #64748b; font-size: 12px; margin: 4px 0 0 0; }
        .item-price { color: #ffffff; font-family: 'Courier New', monospace; font-weight: bold; text-align: right; }

        /* Total Section */
        .total-box { background-color: #0f172a; padding: 25px; margin-top: 30px; border-radius: 8px; border: 1px solid #1e293b; }
        .total-label { color: #94a3b8; font-size: 12px; font-weight: bold; letter-spacing: 1px; }
        .total-amount { color: #3b82f6; font-size: 24px; font-weight: 900; text-align: right; }

        /* Dropzone / Address */
        .dropzone { margin-top: 30px; padding: 20px; border-left: 4px solid #2563eb; background-color: rgba(255,255,255,0.02); }
        .dropzone-title { color: #2563eb; font-size: 11px; font-weight: 900; letter-spacing: 2px; margin-bottom: 8px; text-transform: uppercase; }
        .dropzone-text { color: #e2e8f0; font-size: 13px; line-height: 1.5; }

        /* Footer */
        .footer { padding: 30px; text-align: center; font-size: 10px; color: #475569; letter-spacing: 2px; text-transform: uppercase; }
        .btn { display: inline-block; background-color: #2563eb; color: #ffffff; text-decoration: none; padding: 15px 30px; border-radius: 6px; font-weight: bold; font-size: 13px; margin-top: 20px; text-transform: uppercase; letter-spacing: 1px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="header">
                <h1 class="logo">NEXRIG</h1>
                <div class="status-badge">DEPLOYMENT_INITIATED</div>
            </div>

            <div class="content">
                <div class="greeting">Halo, {{ $order->recipient_name }}</div>
                <div class="description">
                    Permintaan perakitan sistem untuk Order <span style="color: #60a5fa; font-weight: bold;">#{{ $order->id }}</span> telah diterima. 
                    Hardware Anda sedang dikarantina untuk pengecekan kualitas sebelum pengiriman.
                </div>

                <table>
                    <thead>
                        <tr style="border-bottom: 2px solid #1e293b;">
                            <th align="left" style="color: #475569; font-size: 11px; padding-bottom: 10px;">HARDWARE_MANIFEST</th>
                            <th align="right" style="color: #475569; font-size: 11px; padding-bottom: 10px;">VAL_UNIT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr class="item-row">
                            <td class="item-details">
                                <table role="presentation">
                                    <tr>
                                        <td style="padding-right: 15px;">
                                            {{-- Menampilkan Gambar Produk --}}
                                            @php
                                                $image = $item->product->images->where('is_primary', true)->first() ?? $item->product->images->first();
                                                $imageUrl = $image ? $image->full_url : 'https://via.placeholder.com/800';
                                            @endphp
                                            <img src="{{ $imageUrl }}" class="item-img" alt="{{ $item->product_name }}">
                                        </td>
                                        <td>
                                            <p class="item-name">{{ $item->product_name }}</p>
                                            <p class="item-spec">{{ Str::limit($item->product->short_description, 50) }}</p>
                                            <p style="color: #3b82f6; font-size: 11px; margin-top: 5px;">QTY: {{ $item->quantity }}</p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td align="right" class="item-price">
                                Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="total-box">
                    <table style="width: 100%;">
                        {{-- 1. Subtotal (Harga Asli Barang) --}}
                        <tr>
                            <td style="color: #64748b; font-size: 12px; padding-bottom: 8px;">Subtotal ({{ $order->items->sum('quantity') }} items)</td>
                            <td align="right" style="color: #cbd5e1; font-size: 12px; padding-bottom: 8px; font-family: monospace;">
                                Rp {{ number_format($order->total_price - ($order->total_price * 0.11) + $order->discount_amount, 0, ',', '.') }}
                            </td>
                        </tr>

                        {{-- 2. Diskon (Hanya Tampil Jika Ada Diskon) --}}
                        @if($order->discount_amount > 0)
                        <tr>
                            <td style="color: #4ade80; font-size: 12px; padding-bottom: 8px;">Discount Applied</td>
                            <td align="right" style="color: #4ade80; font-size: 12px; padding-bottom: 8px; font-family: monospace;">
                                - Rp {{ number_format($order->discount_amount, 0, ',', '.') }}
                            </td>
                        </tr>
                        @endif

                        {{-- 3. Pajak / Tax (11%) --}}
                        <tr>
                            <td style="color: #64748b; font-size: 12px; padding-bottom: 15px;">Tax (11%)</td>
                            <td align="right" style="color: #cbd5e1; font-size: 12px; padding-bottom: 15px; font-family: monospace;">
                                Rp {{ number_format($order->total_price * 0.11, 0, ',', '.') }}
                            </td>
                        </tr>

                        {{-- Garis Pemisah --}}
                        <tr>
                            <td colspan="2" style="border-top: 1px dashed #334155; padding-top: 15px;"></td>
                        </tr>

                        {{-- 4. Grand Total (Yang Harus Dibayar) --}}
                        <tr>
                            <td class="total-label">TOTAL_PAYMENT_REQUIRED</td>
                            <td align="right" class="total-amount">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </div>

                <div class="dropzone">
                    <div class="dropzone-title">📍 DROP ZONE</div>
                    <div class="dropzone-text">
                        <strong>{{ $order->shipping_name }}</strong><br>
                        {{ $order->shipping_address }}<br>
                        {{ $order->shipping_city }}, {{ $order->shipping_postal_code }}<br>
                        <span style="color: #64748b; font-size: 11px;">Phone: {{ $order->shipping_phone }}</span>
                    </div>
                </div>

                <div style="text-align: center; margin-top: 40px;">
                    <p style="color: #475569; font-size: 12px; margin-bottom: 20px;">Klik tombol di bawah untuk memantau status transmisi logistik Anda.</p>
                    <a href="{{ route('orders.show', $order->id) }}" class="btn">Monitor Deployment</a>
                </div>
            </div>

            <div class="footer">
                NexRig Industrial Systems &bull; High Performance Computing <br>
                &copy; {{ date('Y') }} NEXRIG CORP. SEMARANG, INDONESIA.
            </div>
        </div>
    </div>
</body>
</html>