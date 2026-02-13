<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice #ORD-2023-001</title>
    <style>
        /* Reset Dasar */
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f5f5f5; /* Background abu-abu biar kertasnya kelihatan menonjol */
            margin: 0;
            padding: 20px;
            color: #333;
        }

        /* Kertas A4 */
        .invoice-box {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 16px;
            line-height: 24px;
        }

        /* Header Layout */
        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
        }
        
        .company-details { text-align: right; }
        .invoice-title { font-size: 32px; font-weight: bold; color: #333; }
        .invoice-details { margin-top: 10px; font-size: 14px; color: #555; }

        /* Alamat */
        .address-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
        }
        .address-box h3 { margin-top: 0; font-size: 14px; text-transform: uppercase; color: #888; }
        .address-box p { margin: 0; font-weight: bold; }

        /* Tabel Produk */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th {
            text-align: left;
            background: #f8f9fa;
            padding: 10px;
            border-bottom: 2px solid #ddd;
        }
        table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        table tr:last-child td { border-bottom: none; }
        .text-right { text-align: right; }

        /* Total */
        .total-section {
            width: 100%;
            display: flex;
            justify-content: flex-end;
        }
        .total-table { width: 300px; }
        .total-table td { border: none; padding: 5px 10px; }
        .grand-total { font-size: 18px; font-weight: bold; color: #000; border-top: 2px solid #333 !important; }

        /* Tombol Print (Akan hilang saat di-print) */
        .print-btn-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .btn-print {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            font-size: 16px;
        }

        /* Sembunyikan elemen tidak penting saat mode print/save pdf */
        @media print {
            body { background: none; -webkit-print-color-adjust: exact; }
            .invoice-box { box-shadow: none; padding: 0; margin: 0; width: 100%; }
            .print-btn-container { display: none; }
        }
    </style>
</head>
<body>

    <div class="print-btn-container">
        <button class="btn-print" onclick="window.print()">🖨️ Cetak / Simpan PDF</button>
        <button class="btn-print" style="background: #6c757d; margin-left: 10px;" onclick="window.close()">Tutup</button>
    </div>

    <div class="invoice-box">
        <div class="header">
            <div>
                <div class="invoice-title">INVOICE</div>
                <div class="invoice-details">
                    Status: <span style="color:green; font-weight:bold">LUNAS</span><br>
                    Invoice #: <strong>INV-2024-001</strong><br>
                    Tanggal: 14 Februari 2024
                </div>
            </div>
            <div class="company-details">
                <strong>Nama Toko Anda</strong><br>
                support@tokoadmin.com<br>
                Jl. Teknologi No. 12, Jakarta
            </div>
        </div>

        <div class="address-section">
            <div class="address-box">
                <h3>Ditagihkan Kepada:</h3>
                <p>Budi Santoso</p>
                <div>budi@example.com</div>
                <div>0812-3456-7890</div>
            </div>
            <div class="address-box text-right">
                <h3>Dikirim Ke:</h3>
                <p>Rumah Budi (Kantor)</p>
                <div>Jl. Sudirman Kav 50<br>Jakarta Selatan, 12190</div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Deskripsi Item</th>
                    <th class="text-right">Harga</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Sepatu Running Nike Air (Size 42)</td>
                    <td class="text-right">Rp 1.200.000</td>
                    <td class="text-right">1</td>
                    <td class="text-right">Rp 1.200.000</td>
                </tr>
                <tr>
                    <td>Kaos Kaki Sport (Putih)</td>
                    <td class="text-right">Rp 50.000</td>
                    <td class="text-right">2</td>
                    <td class="text-right">Rp 100.000</td>
                </tr>
            </tbody>
        </table>

        <div class="total-section">
            <table class="total-table">
                <tr>
                    <td>Subtotal</td>
                    <td class="text-right">Rp 1.300.000</td>
                </tr>
                <tr>
                    <td>Ongkos Kirim (JNE)</td>
                    <td class="text-right">Rp 20.000</td>
                </tr>
                <tr>
                    <td>Pajak (PPN 11%)</td>
                    <td class="text-right">Rp 143.000</td>
                </tr>
                <tr>
                    <td class="grand-total">TOTAL BAYAR</td>
                    <td class="grand-total text-right">Rp 1.463.000</td>
                </tr>
            </table>
        </div>
        
        <br>
        <p style="font-size: 12px; color: #777; text-align: center; margin-top: 30px;">
            Terima kasih telah berbelanja! Ini adalah bukti pembayaran yang sah.
        </p>
    </div>

</body>
</html>