<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px; }
        .header { background: #000; color: #fff; padding: 10px 20px; border-radius: 8px 8px 0 0; }
        .content { padding: 20px; }
        .footer { font-size: 12px; color: #777; margin-top: 20px; text-align: center; }
        .label { font-weight: bold; color: #555; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Pesan Baru dari Website NexRig</h2>
        </div>
        
        <div class="content">
            <p>Halo Admin, ada pesan baru masuk melalui formulir Contact Us:</p>
            
            <p><span class="label">Nama:</span> {{ $data['name'] }}</p>
            <p><span class="label">Email:</span> {{ $data['email'] }}</p>
            <p><span class="label">Perihal:</span> {{ ucfirst($data['subject']) }}</p>
            
            <hr>
            
            <p><span class="label">Pesan:</span></p>
            <div style="background: #f9f9f9; padding: 15px; border-left: 4px solid #2563eb;">
                {{ $data['message'] }}
            </div>
        </div>

        <div class="footer">
            Email ini dikirim otomatis oleh Sistem NexRig.<br>
            Jangan balas email ini, silakan balas ke {{ $data['email'] }}.
        </div>
    </div>
</body>
</html>