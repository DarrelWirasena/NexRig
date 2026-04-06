<!DOCTYPE html>
<html>
<head>
    <style>
        body { background-color: #050014; color: #ffffff; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 20px; line-height: 1.6; }
        .container { max-width: 600px; margin: 0 auto; background-color: #0a0a0a; border: 1px solid #1a1a24; border-radius: 12px; overflow: hidden; }
        .header { background: linear-gradient(90deg, #1e3a8a 0%, #0f172a 100%); padding: 30px 20px; text-align: center; }
        .header h1 { margin: 0; color: #fff; font-style: italic; text-transform: uppercase; font-size: 24px; letter-spacing: 2px; }
        .content { padding: 30px 20px; }
        .product-box { background-color: #111422; border: 1px solid #1e293b; border-radius: 8px; padding: 20px; margin: 20px 0; text-align: center; }
        .product-name { font-size: 20px; font-weight: bold; margin-bottom: 10px; color: #fff; }
        .product-price { font-size: 18px; color: #3b82f6; font-weight: bold; margin-bottom: 15px; }
        .btn { display: inline-block; padding: 12px 24px; background-color: #2563eb; color: #ffffff; text-decoration: none; border-radius: 6px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; font-size: 14px; }
        .footer { padding: 20px; text-align: center; font-size: 12px; color: #64748b; border-top: 1px solid #1a1a24; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>NexRig Alert</h1>
        </div>
        <div class="content">
            <h2>Hello, {{ $user->name }}! 🚀</h2>
            <p>Kabar gembira! Rig incaranmu yang ada di dalam Wishlist sekarang <strong>SUDAH KEMBALI TERSEDIA (RESTOCKED)</strong>.</p>
            
            <div class="product-box">
                <div class="product-name">{{ $product->name }}</div>
                <div class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                <p style="font-size: 12px; color: #94a3b8; margin-bottom: 20px;">Segera amankan rig ini sebelum kehabisan lagi!</p>
                <a href="{{ route('products.show', $product->slug) }}" class="btn">Deploy Now</a>
            </div>
            
            <p>Jika kamu sudah mendapatkan rig lain, kamu bisa mengabaikan email ini. Terima kasih telah memilih NexRig!</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} NexRig. The Ultimate Gaming Experience.<br>
            Jl. Pemuda No. 123, Semarang, Indonesia.
        </div>
    </div>
</body>
</html>